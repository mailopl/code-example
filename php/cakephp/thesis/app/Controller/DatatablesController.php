<?php
App::uses('AppController', 'Controller');
App::uses('RestController', 'Controller');

/**
 * Datatables Controller
 *
 * This controller talks with DataTables component only.
 * Every request that datatables does, goes here.
 *
 * @author Marcin Wawrzyniak
 *
 * @property Feed $Feed
 * @property Row $Row
 * @property User $User
 * @property Schema $Schema
 */
class DatatablesController extends RestController
{
    public $uses = array(
        'Row',
        'Feed',
        'User',
        'Schema'
    );

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('*');
    }

    /**
     * This action updates a row in the user defined API.
     * Handled only by datatables.
     *
     * Requires $_REQUEST['columnName'], $_REQUEST['id'], $_REQUEST['value']
     *
     * @author Marcin Wawrzyniak
     * @return void
     */
    public function put()
    {
        $this->layout = 'ajax';

        $this->Row->id = $this->request->data['id'];

        //if the row has a feed that belongs to the user
        $this->Feed->id = $this->Row->field('feed_id');

        // check if row belongs to the user
        if ($this->Auth->user('id') != $this->Feed->field('user_id')) {
            die('ERROR');
        }
        $this->Row->saveField(trim($this->request->data['columnName']), $this->request->data['value']);

        die($this->data['value']); // datatables wants this
    }

    /**
     * This action creates a new row in the user defined API.
     * Handled only by datatables.
     *
     * Requires $_REQUEST: ['Feed']['id'], ['Column']
     *
     * @author Marcin Wawrzyniak
     * @return void
     */
    public function post()
    {
        if ($this->request->is('post')) {

            $this->Row->create();
            $this->request->data['Row']['feed_id'] = (int)$this->request->data['Row']['feed_id'];

            $this->Row->save(
                array(
                    'Row' => $this->request->data['Row']
                )
            );

            $this->log(
                'created row by datatables, ' . '(' . __CLASS__ . ':' . __FUNCTION__ . '=' . $this->Row->id . ')',
                LOG_DEBUG
            );

        }
        die($this->Row->id); //datatables wants this
    }

    /**
     * This action fetches data that user wants to edit in his API.
     * Handled only by datatables.
     *
     * Additionally you can pass $_REQUEST[]:
     *      iDisplayLength,
     *      iDisplayStart,
     *      mDataProp_0,
     *      iSortCol_0,
     *      sSortDir_0
     *
     * @author Marcin Wawrzyniak
     * @param $id integer Feed ID
     * @return void
     */
    public function get($name = null, $id = null)
    {
        $this->layout = 'ajax';
        $id = Memcached::getInstance()->get($name);

        $id == null && $this->log('Feeds::data ID null', 'debug') && die;

        $conditions = array();

        // search support through datatables
        if (!empty($this->params->query['sSearch'])) {

            $conditions['$or'] = array(); // mongodb wants this

            foreach ($this->Row->getSchema($id) as $key => $junk) {

                $conditions['$or'][] = array(
                    $key => array('$regex' => $this->params->query['sSearch']),
                    'feed_id' => (int)$id
                );
            }
        } else {
            $conditions['feed_id'] = (int)$id;
        }

        $order =
            isset($this->params->query['iSortCol_0']) &&
                isset($this->params->query['mDataProp_' . $this->params->query['iSortCol_0']]) &&
                isset($this->params->query['sSortDir_0']) ?
                array(
                    $this->params->query['mDataProp_' . $this->params->query['iSortCol_0']] =>
                    $this->params->query['sSortDir_0']
                ) : array();

        $options = array(
            'conditions' => $conditions,
            'fields' => array(
                'feed_id' => 0
            ),
            'limit' => isset($this->params->query['iDisplayLength']) ? $this->params->query['iDisplayLength'] : false,
            'offset' => isset($this->params->query['iDisplayStart']) ? $this->params->query['iDisplayStart'] : false,
            'order' => $order

        );

        $data = $this->Row->find('all', $options);

        $data = Set::extract('/Row/.', $data);

        // datatables requires DT_RowID, but mongodb can't do aliasing
        // so we need to post process the data
        foreach ($data as &$item) {
            $item['DT_RowId'] = $item['_id'];
            unset($item['_id']);
        }

        $this->set(
            'data',
            array(
                'aaData' => $data, // fetched rows
                'iTotalRecords' => $this->Row->find('count', $options), // total rows count
                'iTotalDisplayRecords' => $this->Row->find('count', $options), // fetched rows count
                'sEcho' => $this->params->query['sEcho'], // kind of CSRF protection for datatables
            )
        );
    }


    /**
     * This method deletes the row.
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete()
    {
        try {
            parent::delete(null, $this->data['id']);
        } catch (Exception $e) {
            die('ERROR');
        }
    }
}
