<?php
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    public $components = array(
        'Session',
        'Auth' => array(
            'authenticate' => array(
                'CustomForm' => array(
                    'fields' => array(
                        'username' => 'identity',
                        'password' => 'k'
                    ),
                    'userModel' => 'Impi'
                )
            ),
            'loginRedirect' => array('controller' => 'protocols', 'action' => 'index'),
            'logoutRedirect' => array('controller' => 'pages', 'action' => 'display', 'home'),
            'authorize' => array('Controller')
        )
    );

    public $user = array();

    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->Auth->loginRedirect = array(
            'controller' => 'users',
            'action' => 'login'
        );

        if (isset($this->user['role']) && $this->user['role'] === 'admin') {
            $this->Auth->allow('*');
        } else {
            if (empty($this->user)) {
                $this->Auth->deny('*');
            }
        }

        $this->Auth->authorize = 'Controller';
        $this->user = $this->Session->read('User');

        // we can allow only certain IP to request API
        if (isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'api') {
            $this->log("API call");
            if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
                die('This IP is not allowed');
            } else {
                $this->Auth->allow(
                    'api_get',
                    'api_index',
                    'api_put',
                    'api_delete',
                    'api_post'
                );
            }
        }
    }

    public function beforeRender()
    {
        parent::beforeRender();

        $this->set('authRole', $this->user['role']); //user role: admin / user
        $this->set('auth', $this->Auth->user()); //Auth user (impo table)
        $this->set('authUser', $this->user); //Auth user (users table)
    }

    public function isAuthorized($user)
    {
        // Admin can access every action
        if (isset($this->user['role']) && $this->user['role'] === 'admin') {
            return true;
        }

        // Default deny
        return false;
    }
}
