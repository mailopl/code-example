<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController
{

    public $uses = array('User', 'Impi', 'Payment');

    /**
     * Here we manage the access rights for: guest/admin/user
     *
     */
    public function beforeFilter()
    {
        parent::beforeFilter();


        if (!$this->user['role']) {
            $this->Auth->allow('register');
        }
        if ($this->user['role'] == 'user') { //admin
            $this->Auth->allow('logout', 'edit');
        }
    }

    /**
     * Logs out the user
     */
    public function logout()
    {
        $this->Session->delete('User');

        $this->redirect($this->Auth->logout());
    }


    public function register()
    {
        if ($this->request->is('post')) {
            $this->User->create();

            $this->request->data['Impi']['auth_scheme'] = 127; //FIXME: co to znaczy?
            $this->request->data['User']['role'] = 'user';

            if ($this->User->saveAll($this->request->data)) {
                $this->Session->setFlash(__('You can now log in.'));
                $this->redirect(array('action' => 'login'));
            } else {
                $this->Session->setFlash(__('The registration could not be finished. Please, try again.'));
            }
        }
    }


    /**
     * Logs in the user basing on hss_db::impi table (identity, k)
     *
     * @return mixed
     */
    public function login()
    {

        $u = $this->User->find('first');
        if ($this->request->is('post')) {

            $impi = $this->Impi->find(
                'first',
                array(
                    'conditions' => array(
                        'Impi.identity' => $this->data['Impi']['identity']
                    ),
                    'fields' => 'id'
                )
            );

            //pobieramy uzytkownika impi
            $impi = $this->User->find(
                'first',
                array(
                    'conditions' => array(
                        'User.impi_id' => $impi['Impi']['id']
                    )
                )
            );

            if (!empty($impi)) {
                //porownanie hasla plaintextowego
                if ($impi['Impi']['k'] == $this->data['Impi']['k']) {

                    if ($this->Auth->login()) {
                        $this->Session->write('User', $impi['User']);

                        $this->Session->setFlash(__('You are now logged in.'), 'default', array(), 'auth');

                        return $this->redirect(
                            array(
                                'action' => 'billing',
                                'controller' => 'payments'
                            )
                        );
                    } else {
                        $this->Session->setFlash(__('Username or password is incorrect'), 'default', array(), 'auth');
                    }
                } else {
                    $this->Session->setFlash(__('Password is incorrect'), 'default', array(), 'auth');
                }
            } else {
                $this->Session->setFlash(__('Username or password is incorrect'), 'default', array(), 'auth');
            }
        }
    }

    /**
     * User edits his profile (name, surname and email)
     *
     * @throws NotFoundException
     */
    public function edit()
    {
        $id = $this->Auth->user('id');
        $user = $this->User->findByImpiId($id);

        if (empty($user)) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $user;

        }
        $utilityFunctions = $this->User->UtilityFunction->find('list');
        $this->set(compact('utilityFunctions'));
    }


    /**
     * Admin users index
     */
    public function admin_index()
    {
        $this->User->recursive = 1;
        $this->set('users', $this->paginate());
    }


    /**
     * Admin creation of user
     */
    public function admin_add()
    {
        if ($this->request->is('post')) {
            $this->User->create();

            $this->request->data['Impi']['auth_scheme'] = 127;

            if ($this->User->saveAll($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        }
        $utilityFunctions = $this->User->UtilityFunction->find('list');

        $this->set(compact('utilityFunctions', 'utilityFunctionsParameters'));
    }


    /**
     * This method allows the admin to supercharge user account
     *
     */
    public function admin_supercharge()
    {
        if ($this->request->is('post')) {
            $this->request->data['Payment']['submitter_id'] = $this->user['id'];
            $this->request->data['Payment']['session_id'] = $this->Session->id();
            $this->request->data['Payment']['created'] = date('Y-m-d H:i:s');

            $this->Payment->create();

            if ($this->Payment->save($this->request->data)) {

                $this->User->id = $this->request->data['Payment']['user_id'];
                $this->User->saveField(
                    'balance',
                    $this->User->field('balance') + $this->request->data['Payment']['amount']
                );

                $this->Session->setFlash(__('The payment has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The payment could not be saved. Please, try again.'));
            }
        }


        $this->set('users', $this->Impi->find('list'));
    }

    /**
     * Admin edits the user (additionally identity name and role)
     *
     * @param null $id
     * @throws NotFoundException
     */
    public function admin_edit($id = null)
    {
        $user = $this->User->findByImpiId($id);

        if (empty($user)) {
            throw new NotFoundException(__('Invalid user'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $user;
        }
        $utilityFunctions = $this->User->UtilityFunction->find('list');
        $this->set(compact('utilityFunctions', 'utilityFunctionsParameters'));
    }


    //GET /api/users/bob@open-ims.test/payments : pobiera liste platnosci (billing)
    //GET /api/users/bob@open-ims.test/utility_functions : pobiera liste funkcji uzytecznosci
    //GET /api/users/bob@open-ims.test/protocols : pobiera liste funkcji uzytecznosci
    public function api_index()
    {
        $resource = $this->request->params['resource'];
        $user = $this->request->params['user'];

        $resource = Inflector::camelize(Inflector::singularize($resource));

        if (!in_array($resource, array('UtilityFunction', 'Payment', 'Protocol'))) {
            die(header("HTTP/1.0 403 Forbidden"));
        }

        $userResource = $resource;
        switch ($resource) {
            case 'Protocol':
            case 'UtilityFunction':
                $userResource = 'UsersUtilityFunction';
                break;
            case 'Parameter':
                $userResource = 'UsersUtilityFunctionsParameter';
                break;
        }


        $model = ClassRegistry::init($userResource);

        if (empty($model)) {
            die(header("HTTP/1.0 403 Forbidden"));
        }

        try {
            $impi = $this->Impi->find(
                'first',
                array(
                    'conditions' => array(
                        'Impi.identity' => $user
                    ),
                    'fields' => 'id'
                )
            );

            $data = $model->find(
                'all',
                array(
                    'conditions' => array(
                        $userResource . '.' . 'user_id' => $impi['Impi']['id']
                    )
                )
            );

            if ($resource != 'Protocol') {
                $data = Set::extract('/' . $resource . '/.', $data);
            }

        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }

        die(json_encode($data));
    }

    //GET /api/users/bob@open-ims.test/utility_functions/1 : pobiera konkretna funkcje
    //GET /api/users/bob@open-ims.test/parameters/1 : pobiera parametry dla funkcji 1
    public function api_get()
    {
        $id = $this->request->params['id'];
        $resource = $this->request->params['resource'];
        $user = $this->request->params['user'];

        $resource = Inflector::camelize(
            Inflector::singularize($resource)
        ); // change utility_functions to UtilityFunction

        try {
            $impi = $this->Impi->find(
                'first',
                array( //we fetch user ID from hss_db, could be  done by cross-db join
                    'conditions' => array(
                        'Impi.identity' => $user
                    ),
                    'fields' => 'id'
                )
            );

            if ($resource == 'UtilityFunction') {
                $userResource = 'UsersUtilityFunction';
                $model = ClassRegistry::init($userResource);


                $data = $model->find(
                    'all',
                    array(
                        'conditions' => array(
                            $userResource . '.' . 'user_id' => $impi['Impi']['id'],
                            $userResource . '.' . 'utility_function_id' => $id
                        ),
                        'contain' => array()
                    )
                );

                $data = Set::extract('/' . $resource . '/.', $data);
            } else {
                if ($resource == 'Parameter') {
                    $userResource = 'UsersUtilityFunctionsParameter';
                    $model = ClassRegistry::init($userResource);


                    $data = $model->find(
                        'all',
                        array(
                            'conditions' => array(
                                $userResource . '.' . 'users_utility_function_id' => $id,
                            ),
                            'contain' => array()
                        )
                    );

                    $data = Set::extract('/' . $resource . '/.', $data);
                } else {
                    die(header("HTTP/1.0 403 Forbidden"));
                }
            }
        } catch (Exception $e) {
            die(json_encode(array('msg' => $e->getMessage())));
        }

        die(json_encode($data));
    }
}
