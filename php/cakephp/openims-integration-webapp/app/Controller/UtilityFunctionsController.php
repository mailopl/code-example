<?php
App::uses('AppController', 'Controller');
/**
 * UtilityFunctions Controller
 *
 * @property UtilityFunction $UtilityFunction
 */
class UtilityFunctionsController extends AppController
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


    public function api_put($id)
    {
        $json = (array)json_decode(file_get_contents("php://input"));

        if ($json === null) {
            header("HTTP/1.0 400 Bad Request");
            die;
        }

        $this->UtilityFunction->id = $id;

        if (!$this->UtilityFunction->exists()) {
            header("HTTP/1.0 404 Not Found");
            die;
        }

        if ($this->UtilityFunction->save(
            array(
                "UtilityFunction" => $json
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
        $this->UtilityFunction->id = $id;

        if (!$this->UtilityFunction->exists()) {
            header("HTTP/1.0 404 Not Found");
            die;
        }

        echo json_encode(
            $this->UtilityFunction->find(
                'first',
                array(
                    'conditions' => array(
                        'UtilityFunction.id' => $id
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
            $this->UtilityFunction->find(
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
            $this->UtilityFunction->id = $id;

            if (!$this->UtilityFunction->exists()) {
                header("HTTP/1.0 404 Not Found");
                die;
            }

            if ($this->UtilityFunction->delete($id)) {
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

        $this->UtilityFunction->create();

        if ($this->UtilityFunction->save(
            array(
                "UtilityFunction" => $json
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
        $this->UtilityFunction->recursive = 1;
        $this->set('utilityFunctions', $this->paginate());
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
        $this->UtilityFunction->id = $id;
        if (!$this->UtilityFunction->exists()) {
            throw new NotFoundException(__('Invalid utility function'));
        }
        $this->set('utilityFunction', $this->UtilityFunction->read(null, $id));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $this->UtilityFunction->create();

            if ($this->UtilityFunction->findByName($this->request->data['UtilityFunction']['name'])) {
                $this->Session->setFlash(__('The utility function of this name already exists!'));
                return;
            }
            if ($this->UtilityFunction->save($this->request->data)) {
                $this->Session->setFlash(__('The utility function has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The utility function could not be saved. Please, try again.'));
            }
        }
//		$users = $this->UtilityFunction->User->find('list');
        $this->set(compact('users'));
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
        $this->UtilityFunction->id = $id;
        if (!$this->UtilityFunction->exists()) {
            throw new NotFoundException(__('Invalid utility function'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->UtilityFunction->save($this->request->data)) {
                $this->Session->setFlash(__('The utility function has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The utility function could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->UtilityFunction->read(null, $id);
        }
        //$users = $this->UtilityFunction->User->find('list');
        $this->set(compact('users'));
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
        $this->UtilityFunction->id = $id;
        if (!$this->UtilityFunction->exists()) {
            throw new NotFoundException(__('Invalid utility function'));
        }
        if ($this->UtilityFunction->delete()) {
            $this->Session->setFlash(__('Utility function deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Utility function was not deleted'));
        $this->redirect(array('action' => 'index'));
    }
}
