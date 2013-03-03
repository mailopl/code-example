<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));


// get
Router::connect('/api/users/:user/:resource/:id', array('prefix'=>'api', 'controller'=>'users','action'=>'get','api'=>true));
// get index
Router::connect('/api/users/:user/:resource/*', array('prefix'=>'api', 'controller'=>'users','action'=>'index', 'api'=>true));



// put ale POST
Router::connect('/api/parameter/*', array('prefix'=>'api', 'api'=>true,'controller'=>'parameters','action'=>'put',"[method]" => "POST"));
// delete
Router::connect('/api/parameters/*', array('prefix'=>'api', 'api'=>true,'controller'=>'parameters','action'=>'delete',"[method]" => "DELETE"));
// post
Router::connect('/api/parameters/*', array('prefix'=>'api', 'api'=>true,'controller'=>'parameters','action'=>'post',"[method]" => "POST"));
// get
Router::connect('/api/parameters/:id/*', array('prefix'=>'api', 'controller'=>'parameters','action'=>'get','api'=>true),array('pass' => array('id')));
// get index
Router::connect('/api/parameters/*', array('prefix'=>'api', 'controller'=>'parameters','action'=>'index', 'api'=>true));


// put ale POST
Router::connect('/api/protocol/*', array('prefix'=>'api', 'api'=>true,'controller'=>'protocols','action'=>'put',"[method]" => "POST"));
// delete
Router::connect('/api/protocols/*', array('prefix'=>'api', 'api'=>true,'controller'=>'protocols','action'=>'delete',"[method]" => "DELETE"));
// post
Router::connect('/api/protocols/*', array('prefix'=>'api', 'api'=>true,'controller'=>'protocols','action'=>'post',"[method]" => "POST"));
// get
Router::connect('/api/protocols/:id/*', array('prefix'=>'api', 'controller'=>'protocols','action'=>'get','api'=>true), array('pass' => array('id')));
// get index
Router::connect('/api/protocols/*', array('prefix'=>'api', 'controller'=>'protocols','action'=>'index', 'api'=>true));



// put ale POST
Router::connect('/api/utility_function/*', array('prefix'=>'api', 'api'=>true, 'controller'=>'utility_functions','action'=>'put',"[method]" => "POST"));
// delete
Router::connect('/api/utility_functions/*', array('prefix'=>'api', 'api'=>true,'controller'=>'utility_functions','action'=>'delete',"[method]" => "DELETE"));
// post
Router::connect('/api/utility_functions/*', array('prefix'=>'api', 'api'=>true,'controller'=>'utility_functions','action'=>'post',"[method]" => "POST"));
// get
Router::connect('/api/utility_functions/:id/*', array('prefix'=>'api', 'controller'=>'utility_functions','action'=>'get','api'=>true),array('pass' => array('id')));
// get index
Router::connect('/api/utility_functions/*', array('prefix'=>'api', 'controller'=>'utility_functions','action'=>'index', 'api'=>true));



/**
 *
 * Load all plugin routes.  See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
CakePlugin::routes();


/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';
