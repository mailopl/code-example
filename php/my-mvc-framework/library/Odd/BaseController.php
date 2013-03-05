<?php
class BaseController{
    protected $view;
    protected $db;
    protected $session;
    protected $_bs;
    protected $config;
    public function __construct(){
      $this->view = new View();
      $this->db = Registry::getRegistry('db');
      global $config;
      $this->config = $config;

    }
    public function __call($funct, $params){
        $method = $funct .'Action';
        $this->_bs->_data[$params[0] . 'Obj']->$method();

        $controller = substr($params[0], 0, strripos($params[0], 'Controller'));
        $action = substr($method, 0, strripos($method, 'Action'));
        $this->view->display( $controller ,  $action );

    }
    public function setBootstrapHandle($bs){
      $this->_bs = $bs;
    }

};