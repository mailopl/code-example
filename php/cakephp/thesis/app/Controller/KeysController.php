<?php
App::uses('AppController', 'Controller');

/**
 * Keys Controller
 *
 * @property Key $Key
 * @property Feed $Feed
 * @property Payment $Payment
 */
class KeysController extends AppController
{
    public $uses = array(
        'Key',
        'Feed',
        'Payment'
    );

    /**
     * List of the user keys
     *
     * @return void
     */
    public function my()
    {
        $this->Key->recursive = 1;

        $this->paginate = array(
            'conditions' => array(
                'Key.user_id' => $this->Auth->user('id')
            ),
            'group' => 'Key.id',
            'order' => 'feed_id'

        );

        $this->set('keys', $this->paginate('Key'));
    }

    /**
     * List of the keys that someone bought for the logged in user
     *
     * @return void
     */
    public function sold()
    {
        $this->Key->recursive = 1;

        $payments = $this->Payment->fetchSold($this->Auth->user('id'));

        $this->set('data', $payments);
    }

    /**
     * Artificial finalization of the "buy" process.
     * In commerce product you would replace that with paypal or something.
     *
     * @param int $state
     * @throws NotFoundException
     */
    public function buy($state = 1)
    {
        if ($state == 1) {
            $this->Key->create($this->request->data);


            $feed_id = $this->Key->data['Key']['feed_id'];
            $feed = $this->Feed->find(
                'first',
                array(
                    'conditions' => array(
                        'Feed.id' => $feed_id
                    )
                )
            );

            if (empty($feed)) {
                throw new NotFoundException();
            }

            $key = sha1(uniqid("", true));
            $amount = ($this->Key->data['Key']['amount'] + 1) * 100000;

            $this->Key->save(
                array(
                    'Key' => array(
                        'user_id' => $this->Auth->user('id'),
                        'transaction_status' => $state,
                        'key' => $key,
                        'requests' => $amount,
                        'created' => date('Y-m-d H:i:s')
                    )
                )
            );

            $this->Payment->save(
                array(
                    'Payment' => array(
                        'user_id' => $this->Auth->user('id'),
                        'key_id' => $this->Key->id,
                        'feed_id' => $feed_id,
                        'price' => (Configure::read('Premium.requestPrice') * ($this->Key->data['Key']['amount'] + 1)),
                        'created' => date('Y-m-d H:i:s')
                    )
                )
            );

            $cacheKey = $feed['Feed']['slug'] . '/' . $key;
            Memcached::getInstance()->set($cacheKey, $amount);

            $this->set('key', $key);
            $this->set('amount', $amount);
            $this->set('feed', $feed);

            $this->Email->subject = 'Apigeum API key';
            $this->Email->template = 'buy';
            $this->Email->sendAs = 'html';
            $this->Email->from = 'noreply@apigeum.com';
            $this->Email->to = 'wawrzyniak.mm@gmail.com';

            if (!$this->Email->send()) {
                $this->Session->setFlash('Sorry, cannot send an email.', 'error');
            }

        }
    }

    /**
     * Deletes the Key
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
        $this->Key->id = $id;

        if (!$this->Key->exists()) {
            throw new NotFoundException(__('Invalid key'));
        }

        if ($this->Key->deleteSafe()) {
            $this->Session->setFlash(__('Key deleted'), 'success');
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Key was not deleted'), 'error');
        $this->redirect(array('action' => 'index'));
    }
}
