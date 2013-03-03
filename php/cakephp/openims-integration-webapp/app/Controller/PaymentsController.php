<?php
App::uses('AppController', 'Controller');

/**
 * Payments Controller
 *
 * @property Payment $Payment
 */
class PaymentsController extends AppController
{
    /**
     * Here we manage the access rights for: guest/admin/user
     *
     */
    public function beforeFilter()
    {
        parent::beforeFilter();

        if ($this->user['role'] == 'user') { // admin
            $this->Auth->allow('billing');
        }
    }


    public function billing()
    {
        $user = $this->Session->read('User');

        $payments = $this->Payment->find(
            'all',
            array(
                'conditions' => array(
                    'Payment.user_id' => $this->user['id']
                )
            )
        );

        $this->set(compact('payments'));
    }

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $this->Payment->recursive = 0;
        $this->set('payments', $this->paginate());
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
        $this->Payment->id = $id;
        if (!$this->Payment->exists()) {
            throw new NotFoundException(__('Invalid payment'));
        }
        $this->set('payment', $this->Payment->read(null, $id));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $this->Payment->create();
            if ($this->Payment->save($this->request->data)) {
                $this->Session->setFlash(__('The payment has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The payment could not be saved. Please, try again.'));
            }
        }
        $submitters = $this->Payment->Submitter->find('list');
        $clients = $this->Payment->Client->find('list');
        $this->set(compact('submitters', 'clients'));
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
        $this->Payment->id = $id;
        if (!$this->Payment->exists()) {
            throw new NotFoundException(__('Invalid payment'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Payment->save($this->request->data)) {
                $this->Session->setFlash(__('The payment has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The payment could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->Payment->read(null, $id);
        }
        $submitters = $this->Payment->Submitter->find('list');
        $clients = $this->Payment->Client->find('list');
        $this->set(compact('submitters', 'clients'));
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
        $this->Payment->id = $id;
        if (!$this->Payment->exists()) {
            throw new NotFoundException(__('Invalid payment'));
        }
        if ($this->Payment->delete()) {
            $this->Session->setFlash(__('Payment deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Payment was not deleted'));
        $this->redirect(array('action' => 'index'));
    }
}
