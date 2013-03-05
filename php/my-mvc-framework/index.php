<?php

session_start();

$debug = array();

require "./library/OddCore.php"; //autoload stuff

//Data for development stage (from config file)
Registry::setRegistry('stage', 'development');

$config = new Config('./config/application.ini');

Registry::setRegistry('db', new MySql($config->db_user,
                                      $config->db_password,
                                      $config->db_host,
                                      $config->db_database));
                                      

Registry::setRegistry('layoutDirectory', 'default'); //default layout directory


$bootstrap = new Bootstrap(new Router($config));
