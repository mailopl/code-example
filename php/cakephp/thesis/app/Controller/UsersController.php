<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController
{

    public $theme = 'TwitterBootstrap';

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('register', 'login');
    }

    public function login()
    {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->redirect(
                    array(
                        'action' => 'my',
                        'controller' => 'feeds'
                    )
                );
            } else {
                $this->Session->setFlash(__('Invalid username or password, try again'), 'error');
            }
        }
    }

    public function register()
    {
        if ($this->Auth->user()) {
            $this->Session->setflash('You are already logged in', 'error');
            $this->redirect('/');
        }
        if ($this->request->is('post')) {
            $this->User->create($this->request->data);

            if (!$this->User->validates()) {
                $this->Session->setFlash(__('Please correct the form errors.'), 'error');
                return;
            }

            $this->request->data['User']['created'] = date('Y-m-d H:i:s');
            $this->request->data['User']['last_seen'] = date('Y-m-d H:i:s');
            $this->request->data['User']['password'] = $this->Auth->password($this->data['User']['password']);

            if ($this->User->save($this->request->data)) {
                $this->request->data['User']['id'] = $this->User->id;
                $this->Auth->login($this->request->data['User']);

                $this->Session->setFlash(__('You are now logged in'), 'success');
                $this->redirect(array('action' => 'my', 'controller' => 'feeds'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'), 'error');
            }
        }
    }

    public function logout()
    {
        $this->Session->setFlash('You are logged out', 'success');
        $this->Auth->logout();
        $this->Session->renew();
        $this->redirect(array('action' => 'index', 'controller' => "feeds"));
    }

}
