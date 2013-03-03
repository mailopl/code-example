<?php
App::uses('AppController', 'Controller');

/**
 * Parameters Controller
 *
 * @property Parameter $Parameter
 */
class ParametersController extends AppController
{
    /**
     * Here we manage the access rights for: guest/admin/user
     *
     */
    public function beforeFilter()
    {
        parent::beforeFilter();

        if ($this->user['role'] == 'user') { //admin
            $this->Auth->allow('index');
        }
    }

    //PUT openims/api/parameter/5
    public function api_put($id)
    {
        $json = (array)json_decode(file_get_contents("php://input"));

        if ($json === null) {
            header("HTTP/1.0 400 Bad Request");
            die;
        }

        $this->Parameter->id = $id;

        if (!$this->Parameter->exists()) {
            header("HTTP/1.0 404 Not Found");
            die;
        }

        if ($this->Parameter->save(
            array(
                "Parameter" => $json
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
        $this->Parameter->id = $id;

        if (!$this->Parameter->exists()) {
            header("HTTP/1.0 404 Not Found");
            die;
        }

        echo json_encode(
            $this->Parameter->find(
                'first',
                array(
                    'conditions' => array(
                        'Parameter.id' => $id
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
            $this->Parameter->find(
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
            $this->Parameter->id = $id;
            if (!$this->Parameter->exists()) {
                header("HTTP/1.0 404 Not Found");
                die;
            }

            if ($this->Parameter->delete($id)) {
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

        $this->Parameter->create();

        if ($this->Parameter->save(
            array(
                "Parameter" => $json
            )
        )
        ) {
            header("HTTP/1.0 200 OK");
        } else {
            header("HTTP/1.0 403 Forbidden");
        }
        die;
    }

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $this->Parameter->recursive = 0;
        $this->set('parameters', $this->paginate());
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
        $this->Parameter->id = $id;
        if (!$this->Parameter->exists()) {
            throw new NotFoundException(__('Invalid parameter'));
        }
        $this->set('parameter', $this->Parameter->read(null, $id));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $this->Parameter->create();
            if ($this->Parameter->save($this->request->data)) {
                $this->Session->setFlash(__('The parameter has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The parameter could not be saved. Please, try again.'));
            }
        }
        $utilityFunctions = $this->Parameter->UtilityFunction->find('list');
        $usersUtilityFunctions = $this->Parameter->UsersUtilityFunction->find('list');
        $this->set(compact('utilityFunctions', 'usersUtilityFunctions'));
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
        $this->Parameter->id = $id;
        if (!$this->Parameter->exists()) {
            throw new NotFoundException(__('Invalid parameter'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Parameter->save($this->request->data)) {
                $this->Session->setFlash(__('The parameter has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The parameter could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->Parameter->read(null, $id);
        }
        $utilityFunctions = $this->Parameter->UtilityFunction->find('list');
        $usersUtilityFunctions = $this->Parameter->UsersUtilityFunction->find('list');
        $this->set(compact('utilityFunctions', 'usersUtilityFunctions'));
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
        $this->Parameter->id = $id;
        if (!$this->Parameter->exists()) {
            throw new NotFoundException(__('Invalid parameter'));
        }
        if ($this->Parameter->delete()) {
            $this->Session->setFlash(__('Parameter deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Parameter was not deleted'));
        $this->redirect(array('action' => 'index'));
    }
}
