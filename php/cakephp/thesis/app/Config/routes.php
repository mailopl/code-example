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
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'info'));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

/**
 * Load all plugin routes.  See the CakePlugin documentation on 
 * how to customize the loading of plugin routes.
 */
CakePlugin::routes();

/**
 * Custom routes
 */

/**
 * DELETE for datatables
 */
Router::connect(
    '/api/dt/*',
    array(
        'controller' => 'datatables',
        'action' => 'delete',
        '[method]'=>'DELETE'
    ),
    array(
        'pass' => array('name','id')
    )
);
/**
 * DELETE for API
 */
Router::connect(
    '/api/*',
    array(
        'controller' => 'rest',
        'action' => 'delete',
        '[method]'=>'DELETE'
    ),
    array(
        'pass' => array('name','id')
    )
);

/**
 * GET for datatables
 */
Router::connect(
    '/api/dt/*',
    array(
        'controller' => 'datatables',
        'action' => 'get',
        '[method]'=>'GET'
    ),
    array(
        'pass' => array('name','id')
    )
);
/**
 * GET for API
 */
Router::connect(
    '/api/*',
    array(
        'controller' => 'rest',
        'action' => 'get',
        '[method]'=>'GET'
    ),
    array(
        'pass' => array('name','id')
    )
);
/**
 * PUT for datatables
 */
Router::connect(
    '/api/dt/*',
    array(
        'controller' => 'datatables',
        'action' => 'put',
        '[method]'=>'PUT'
    ),
    array(
        'pass' => array('name','id')
    )
);
/**
 * PUT for API
 */
Router::connect(
    '/api/*',
    array(
        'controller' => 'rest',
        'action' => 'put',
        '[method]'=>'PUT'
    ),
    array(
        'pass' => array('name','id')
    )
);

/**
 * POST for datatables
 */
Router::connect(
    '/api/dt/*',
    array(
        'controller' => 'datatables',
        'action' => 'post',
        '[method]'=>'POST'
    ),
    array(
        'pass' => array('name','id')
    )
);
/**
 * POST for API
 */
Router::connect(
    '/api/*',
    array(
        'controller' => 'rest',
        'action' => 'post',
        '[method]'=>'POST'
    ),
    array(
        'pass' => array('name','id')
    )
);


Router::parseExtensions();


/**
 * Load the CakePHP default routes. Remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';


