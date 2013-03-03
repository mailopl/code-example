<?php
App::uses('AppController', 'Controller');

/**
 * REST Controller
 *
 * @property Feed $Feed
 * @property Row $Row
 * @property Schema $Schema
 * @property User $User
 */
class RestController extends AppController
{
    public $layout = 'ajax';

    public $uses = array(
        'Row',
        'Schema',
        'Feed',
        'User'
    );

    // data that can be infered from memcached
    // filled in, in _keyAuth method
    public $collectedData = array(
        'isPremium' => false,
        'requestsLeft' => 0,
        'key' => false,
        'keyOk' => false
    );

    public $components = array(
        'Auth' => array(
            'authenticate' => array(
                'Basic' => array(
                    'fields' => array('username' => 'email')
                )
            ),
        ),
        'Session',
        'Email',
        'RequestHandler',
    );

    protected $acceptedOptions = array( // options that user can define in API request
        'limit' => null,
        'offset' => null,
        'fields' => null,
        'order' => null,
    );


    /**
     * GET      : Authorization basic / By key / Free
     * PUT      : ONLY Authorization basic
     * POST     : ONLY Authorization basic
     * DELETE   : ONLY Authorization basic
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('get');

        // GET method is protected additionaly by Key, or may be free
        if ('get' == $this->request->params['action']) {

            // if the user is not authenticated by Authorization Basic, we try other methods
            if (!$this->Auth->user()) {

                $this->_keyAuth(
                    $this->request->params['pass'][0],
                    $this->request
                );
            }

        } else {
            // PUT, POST and DELETE are allowed only by Authorization: Basic
            if (!$this->Auth->user()) {

                $this->Auth->autoRedirect = false;
                if (!$this->Auth->login()) {
                    die(header("HTTP/1.0 401 Unauthorized"));
                }

            }
        }
    }

    /**
     * Basically this is a REST/GET method.
     *
     * Needs additional header to work:
     *  Authorization: Basic base64(email:password)
     *
     * OR an API KEY.
     *
     * @param null $name
     * @param null $id
     */
    public function get($name = null, $id = null)
    {

        if (!$this->request->is('get')) {
            throw new BadRequestException("Only GET is allowed here.");
        }
        //$this->_keyAuth($name, $this->request);

        if (Memcached::getInstance()->get($name . '/completed') != 1) {
            throw new ForbiddenException("Forbidden, This repository is not publicily available yet");
        }
        // if this is a premium feed and key is fine
        // we decrement requests left
        if ($this->collectedData['isPremium'] && $this->collectedData['keyOk']) {
            $cacheKey = $name . '/' . $this->request->query['key'];
            Memcached::getInstance()->decrement($cacheKey);
        }

        $options = array(
            'conditions' => array(
                'feed_id' => (int)Memcached::getInstance()->get($name)
            ),
        );

        if ($options['conditions']['feed_id'] === 0) {
            throw new NotFoundException("Not found, Repository doesnt exist.");
        }

        $queryOptions = $this->_separateOptions();

        if (isset($queryOptions['fields'])) {
            $options['fields'] = $this->_fieldsDiff(
                $name,
                $queryOptions['fields']
            ); // needed bacause of mongodb limitation
            unset($queryOptions['fields']);
        } else {
            $options['fields']['_id'] = 0;
        }
        if (!empty($id)) {
            $options['conditions']['id'] = $id;
        }


        if (isset($queryOptions['order'])) {
            $tmp = explode(",", $queryOptions['order']);
            $options['order'] = array($tmp[0] => $tmp[1]);
            unset($queryOptions['order']);
        }

        //limit, offset
        foreach ($queryOptions as $key => $value) {
            $options[$key] = (int)$value;
        }

        // additionally we do not want those fields
        $options['fields']['feed_id'] = 0;

        $data = $this->Row->find('all', $options);
        $data = Set::extract('/Row/.', $data);

        $this->set('data', $data);
    }

    /**
     * Basically this is REST/PUT
     *
     * Needs additional header to work:
     *  Authorization: Basic base64(email:password)
     *
     * @param null $name
     * @param null $id
     * @throws NotFoundException
     * @throws BadRequestException
     * @throws InternalErrorException
     */
    public function put($name = null, $id = null)
    {
        if (!$this->request->is('put')) {
            throw new BadRequestException();
        }

        $this->Row->id = $id;

        $new = false;

        if (!$this->Row->exists()) {
            $new = (bool)$this->Row->create();
        }

        $feedId = Memcached::getInstance()->get($name);
        $this->Feed->id = $feedId;

        if ($this->Feed->field('user_id') != $this->Auth->user('id')) {
            throw new UnauthorizedException();
        }

        $data = array(
            'Row' => (array)json_decode($this->request->input())
        );

        // here we fetch the schema
        $schema = $this->Row->getSchema((int)Memcached::getInstance()->get($name));

        // here we ensure that newly created row contains keys present in schema
        $data['Row'] = array_intersect_key($data['Row'], $schema);

        $data['Row']['feed_id'] = (int)$feedId;

        if ($this->Row->save($data)) {
            die(header($new ? "HTTP/1.0 201 Created" : "HTTP/1.0 200 OK"));
        } else {
            throw new InternalErrorException();
        }
    }


    /**
     * This is basically a REST/POST method.
     * Allows to create new Row, checks if fields match to the Schema collection.
     *
     * @param null $name
     * @throws BadRequestException
     * @throws InternalErrorException
     */
    public function post($name = null)
    {
        if (!$this->request->is('post')) {
            throw new BadRequestException();
        }
        $this->Row->create();

        $data = $this->Row->getSchema((int)Memcached::getInstance()->get($name));
        foreach ($data as $key => $item) { // quick and dirty hack
            $data[$key] = "";
        }

        // if theres no JSON data
        if (!$this->request->input()) {
            throw new BadRequestException();
        }

        // we allow only fields already defined in the Schema collection
        foreach ((array)json_decode($this->request->input()) as $key => $value) {
            if (isset($data[$key])) {
                $data[$key] = $value;
            }
        }

        $clean = array('Row' => $data);
        $clean['Row']['feed_id'] = (int)Memcached::getInstance()->get($name);

        if ($this->Row->save($clean)) {
            die(header("HTTP/1.0 201 Created"));
        } else {
            throw new InternalErrorException();
        }
    }

    /**
     * Basically this is REST/DELETE.
     *
     * This method deletes the resource.
     * Needs additional header to work:
     *  Authorization: Basic base64(email:password)
     *
     * @param null $name
     * @param null $id
     * @throws NotFoundException
     * @throws BadRequestException
     * @throws InternalErrorException
     */
    public function delete($name = null, $id = null)
    {

        if (!$this->request->is('delete')) {
            throw new BadRequestException();
        }

        $this->Row->id = $id;

        if (!$this->Row->exists()) {
            throw new NotFoundException();
        }

        $this->Feed->id = $this->Row->field('feed_id');

        if ($this->Feed->field('user_id') != $this->Auth->user('id')) {
            throw new UnauthorizedException();
        }

        if ($this->Row->delete($id)) {
            die(header("HTTP/1.0 204 No Content"));
        } else {
            throw new InternalErrorException();
        }
    }

    protected function _keyAuth($repoName = null, $request = null)
    {

        // if repoName is not null
        if ($repoName === null) {
            throw new ForbiddenException("Forbidden, Missing repository name");
        }

        // if it exists in memcached (so probably exists in MySQL)
        if (Memcached::getInstance()->get($repoName) === false) {
            throw new NotFoundException();
        }

        // check if it is free or premium
        if (Memcached::getInstance()->get($repoName . '/type') == 'premium') {
            $this->collectedData['isPremium'] = true;

            if (!isset($request->query['key'])) {
                throw new UnauthorizedException("Unauthorized, No key parameter");
            }

            $this->collectedData['key'] = $request->query['key'];

            // if it's premium, check if proper key exists
            $cacheKey = $repoName . '/' . $request->query['key'];
            $requestsLeft = Memcached::getInstance()->get($cacheKey);

            if ($requestsLeft === false) {
                throw new ForbiddenException("Unauthorized, This key doesnt exist");
            }

            $this->collectedData['keyOk'] = true;


            $this->collectedData['requestsLeft'] = $requestsLeft;
            if ($requestsLeft == 0) {
                throw new ForbiddenException('Unauthorized, This key expired');
            }
        }
    }

    /**
     * This method computes the difference between the feed schema, and
     * ?fields parameter that user given, because mongodb does not support
     * "complex" field querying
     *
     * @param $name
     * @param $options
     * @return array
     */
    protected function _fieldsDiff($name, $fields)
    {
        // mongodb allows only to disable particular fields like _id => 0,
        // so we need to fetch the schema, compute diff between schema, and fields
        // that user given, and disable them.

        $schema = $this->Schema->find(
            'first',
            array(
                'conditions' => array(
                    'id' => (int)Memcached::getInstance()->get($name)
                ),

                //'fields' => array('_id' => 0)
            )
        );


        if (empty($schema)) {
            throw new NotFoundException();
        }
        $array = array_diff(array_keys($schema['Schema']), explode(",", $fields));
        return array_fill_keys($array, 0);
    }
}
