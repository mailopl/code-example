<?php
App::uses('AppController', 'Controller');
/**
 * Protocols Controller
 *
 * @property Protocol $Protocol
 */
class ProtocolsController extends AppController
{

    public $uses = array(
        'UtilityFunction',
        'Protocol',
        'Parameter',
        'UsersUtilityFunction',
        'UsersUtilityFunctionsParameter'
    );

    /**
     * Here we manage the access rights for: guest/admin/user
     *
     */
    public function beforeFilter()
    {
        parent::beforeFilter();

        if ($this->user['role'] == 'user') { //admin
            $this->Auth->allow('assign', 'assign_index');
        }
    }

    public function api_put($id)
    {
        $json = (array)json_decode(file_get_contents("php://input"));

        if ($json === null) {
            header("HTTP/1.0 400 Bad Request");
            die;
        }

        $this->Protocol->id = $id;

        if (!$this->Protocol->exists()) {
            header("HTTP/1.0 404 Not Found");
            die;
        }

        if ($this->Protocol->save(
            array(
                "Protocol" => $json
            )
        )
        ) {
            header("HTTP/1.0 200 OK");
        } else {
            header("HTTP/1.0 403 Forbidden");
        }
        die;
    }

    //GET openims/api/parameters/6
    public function api_get($id = null)
    {
        $this->Protocol->id = $id;

        if (!$this->Protocol->exists()) {
            header("HTTP/1.0 404 Not Found");
            die;
        }

        echo json_encode(
            $this->Protocol->find(
                'first',
                array(
                    'conditions' => array(
                        'Protocol.id' => $id
                    ),
                    'contain' => array()
                )
            )
        );

        die;
    }

    //GET openims/api/parameters
    public function api_index()
    {
        echo json_encode(
            $this->Protocol->find(
                'all',
                array(
                    'conditions' => array(),
                    'contain' => array()
                )
            )
        );

        die;
    }

    //DELETE openims/api/parameter/6
    public function api_delete($id = null)
    {
        try {
            $this->Protocol->id = $id;

            if (!$this->Protocol->exists()) {
                header("HTTP/1.0 404 Not Found");
                die;
            }

            if ($this->Protocol->delete($id)) {
                header("HTTP/1.0 200 OK");
            } else {
                header("HTTP/1.0 403 Forbidden");
            }
        } catch (Exception $e) {
            die(json_encode(array('msg' => $e->getMessage())));
        }
        die;
    }

    //POST openims/api/parameters
    public function api_post()
    {
        $json = (array)json_decode(file_get_contents("php://input"));

        if ($json === null) {
            header("HTTP/1.0 400 Bad Request");
            die;
        }

        $this->Protocol->create();

        if ($this->Protocol->save(
            array(
                "Protocol" => $json
            )
        )
        ) {
            header("HTTP/1.0 200 OK");
        } else {
            header("HTTP/1.0 403 Forbidden");
        }
        die;
    }

    public function assign()
    {
        if ($this->request->is('post')) {

            $this->request->data['UsersUtilityFunction']['user_id'] = $this->user['id'];

            if ($this->UsersUtilityFunction->save($this->request->data)) {

                foreach ($this->request->data['Parameter'] as $id => $p) {
                    if (!isset($p['checked'])) {
                        continue;
                    }
                    $this->UsersUtilityFunctionsParameter->create(
                        array(
                            'UsersUtilityFunctionsParameter' => array(
                                'value' => $p['default'],
                                'parameter_id' => $id,
                                'users_utility_function_id' => $this->UsersUtilityFunction->id
                                //$this->request->data['UsersUtilityFunction']['utility_function_id']
                            )
                        )
                    );
                    $this->UsersUtilityFunctionsParameter->save();
                }


                $this->Session->setFlash(__('The assignment has been saved'));
                $this->redirect(array('action' => 'assign_index'));
            } else {
                $this->Session->setFlash(__('The assignment could not be saved. Please, try again.'));
            }
            // print_r($this->request->data);die;
        }
        $this->set('protocols', $this->Protocol->find('list'));
        $this->set('functions', $this->UtilityFunction->find('list'));
        $this->set('parameters', $this->Parameter->find('list', array('fields' => array('id', 'name_with_default'))));
        $this->set('parameter_values', $this->Parameter->find('all'));
    }

    public function assign_index()
    {
        $this->set(
            'assignments',
            $this->UsersUtilityFunction->find(
                'all',
                array(
                    'conditions' => array(
                        'UsersUtilityFunction.user_id' => $this->user['id'],
                    )
                )
            )
        );
    }

    public function admin_assign_index()
    {
        $this->UsersUtilityFunction->recursive = 2;
        $this->set(
            'assignments',
            $this->UsersUtilityFunction->find(
                'all',
                array(
                    'conditions' => array(),
                    'order' => 'UsersUtilityFunction.user_id'
                )
            )
        );
    }

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $this->Protocol->recursive = 0;

        $this->set('protocols', $this->paginate('Protocol'));
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null)
    {
        $this->Protocol->id = $id;
        if (!$this->Protocol->exists()) {
            throw new NotFoundException(__('Invalid protocol'));
        }
        $this->set('protocol', $this->Protocol->read(null, $id));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $this->Protocol->create();
            if ($this->Protocol->save($this->request->data)) {
                $this->Session->setFlash(__('The protocol has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The protocol could not be saved. Please, try again.'));
            }
        }
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null)
    {
        $this->Protocol->id = $id;
        if (!$this->Protocol->exists()) {
            throw new NotFoundException(__('Invalid protocol'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Protocol->save($this->request->data)) {
                $this->Session->setFlash(__('The protocol has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The protocol could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->Protocol->read(null, $id);
        }
    }

    /**
     * delete method
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Protocol->id = $id;
        if (!$this->Protocol->exists()) {
            throw new NotFoundException(__('Invalid protocol'));
        }
        if ($this->Protocol->delete()) {
            $this->Session->setFlash(__('Protocol deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Protocol was not deleted'));
        $this->redirect(array('action' => 'index'));
    }
}
