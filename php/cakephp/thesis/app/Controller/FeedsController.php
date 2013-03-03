<?php
App::uses('AppController', 'Controller');

/**
 * Feeds Controller
 *
 * @author Marcin Wawrzyniak
 *
 * @property Feed $Feed
 * @property Row $Row
 * @property User $User
 * @property Schema $Schema
 */
class FeedsController extends AppController
{
    public $uses = array(
        'Row',
        'Key',
        'Feed',
        'User',
        'Schema',
        'Favourite'
    );

    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->Auth->allow(
            'index',
            'view'
        );
    }

    /**
     * Public feeds index
     *
     * @author Marcin Wawrzyniak
     * @return void
     */
    public function index()
    {
        $this->Feed->recursive = 1;

        $this->paginate = array(
            'conditions' => array(
                'Feed.completed' => 1,
                'Feed.status' => AppModel::STATUS_ACTIVE
            )
        );

        $this->set('feeds', $this->paginate('Feed'));
    }

    /**
     * Imports CSV file, based on uploaded tmp file and feed id
     *
     * Requires $_FILE['file']['tmp_name'] and $_POST['feed_id']
     *
     * @author Marcin Wawrzyniak
     * @return void
     */
    public function import()
    {
        $data = $this->Row->import(
            $this->request->params['form']['file']['tmp_name'], //tmp file
            (int)$this->request->data['feed_id'] // feed to import to
        );
        die($data ? 'OK' : 'ERROR');
    }

    /**
     * Shows the user feeds
     */
    public function my()
    {
        $this->Feed->recursive = 1;

        $this->paginate = array(
            'conditions' => array(
                'Feed.user_id' => $this->Auth->user('id'),
                'Feed.status' => null
            )
        );

        $this->set('feeds', $this->paginate('Feed'));
    }

    /**
     * This method allows to add feed to favourites, or remove it.
     * It makes use of memcached instead of session.
     *
     * @param null $id
     * @throws NotFoundException
     */
    public function fav($id = null)
    {
        $this->Feed->id = $id;

        if (!$this->Feed->existsForUser()) {
            throw new NotFoundException(__('Invalid feed'));
        }

        $fav = $this->Favourite->find(
            'first',
            array(
                'conditions' => array(
                    'Favourite.feed_id' => $id,
                    'Favourite.user_id' => $this->Auth->user('id')
                ),
                'contain' => array()
            )
        );

        if (empty($fav)) { // not in favourites
            if ($this->Favourite->save(
                array(
                    'Favourite' => array(
                        'feed_id' => $id,
                        'user_id' => $this->Auth->user('id')
                    )
                )
            )
            ) {

                $this->Feed->increment('likes', $id);

                $cacheKey = 'Feed#' . $id . '/' . $this->Auth->user('id');

                Memcached::getInstance()->set($cacheKey, 1);

                $this->Session->setFlash('Added to favourites', 'success');

            } else {

                $this->Session->setFlash('Error adding to favourites', 'error');

            }
        } else {

            if ($this->Favourite->delete($fav['Favourite']['id'])) {

                $this->Feed->decrement('likes', $id);

                $cacheKey = 'Feed#' . $id . '/' . $this->Auth->user('id');
                Memcached::getInstance()->delete($cacheKey);

                $this->Session->setFlash('Removed from favourites', 'success');

            } else {

                $this->Session->setFlash('Error removing from favourites', 'error');

            }
        }


        $this->redirect($this->referer());
    }

    /**
     * This method shows the user favourites.
     *
     * @return void
     */
    public function favs()
    {
        $this->Favourite->recursive = 1;
        $this->paginate = array(
            'conditions' => array(
                'Favourite.user_id' => $this->Auth->user('id')
            )
        );
        $this->set('feeds', $this->paginate('Favourite'));
    }

    /**
     * Public method, shows the feed details
     *
     * @author Marcin Wawrzyniak
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null)
    {
        $this->Feed->id = $id;

        if (!$this->Feed->existsForUser()) {
            throw new NotFoundException(__('Invalid feed'));
        }

        $starred = false;

        if ($this->Auth->user()) {
            $cacheKey = 'Feed#' . $id . '/' . $this->Auth->user('id');
            $starred = Memcached::getInstance()->get($cacheKey);
        }

        $feed = $this->Feed->read(null, $id);
        if ($feed['Feed']['type'] == 'free' && $feed['Feed']['completed']) {
            // we need to fetch the schema to show first 100 rows
            $schema = $this->Schema->find(
                'first',
                array(
                    'conditions' => array(
                        'id' => (int)$id
                    )
                )
            );
            unset($schema['Schema']['_id']);
            $columns = array_keys($schema['Schema']);

            $this->set('columns', $columns);
            // and rows
            $this->set(
                'rows',
                $this->Row->find(
                    'all',
                    array(
                        'limit' => 100,
                        'conditions' => array(
                            'Row.feed_id' => (int)$id
                        ),
                        'fields' => array('_id' => 0, 'feed_id' => 0)
                    )
                )
            );
        }

        $this->set('starred', $starred);
        $this->set('feed', $feed);

        $this->Feed->increment('views_count', $id);
    }

    /**
     * This methods adds a feed. Modifies MySQL Feeds table and Mongo's Schema table.
     *
     * @author Marcin Wawrzyniak
     * @return void
     */
    public function add()
    {

        if ($this->request->is('post')) {
            if (strlen(trim(join('', $this->request->data['Schema']['new']))) == 0) {
                $this->Feed->validationErrors['fields'] = __('You have to specify at least one field.');
            }
            App::uses('AppModel', 'Model');

            $this->request->data['Feed']['user_id'] = $this->Auth->user('id');

            if ($this->Feed->save($this->request->data)) {

                // this is wild ...
                // $this->request->data['Dropdown'] is a two dimensional array
                // each element of that array, holds another array
                // that another array is array of default values
                // so you get like Dropdown[0] = array('default1','defalt2'...);
                $dropdown = array();
                foreach ($this->request->data['Dropdown'] as $field => $values) {
                    if (strlen(join('', $values)) > 0) { // if there's at least one default value
                        // if $field is send in form, fetch it's name

                        // data['Schema']['new'] is an array of values (field names)
                        // following condition checks if index of some Dropdown item
                        // exists in the submitted Schema array
                        if (isset($this->request->data['Schema']['new'][$field])) {
                            // $dropdown[ FIELD_NAME_BASED_ON_DROPDOWN_INDEX ]
                            $dropdown[$this->request->data['Schema']['new'][$field]] = array_filter(
                                $values,
                                function ($var) {
                                    return !empty($var); // can contain only not empty values
                                }
                            );
                        }
                    }
                }

                // prepare schema document
                $schema = array_combine(
                    array_values($this->request->data['Schema']['new']),
                    array_pad(array(), 10, array())
                );
                unset($schema['']); // clear junks
                $schema['_id'] = (int)$this->Feed->id; // and tell id

                // append default values if submitted
                foreach ($dropdown as $key => $val) {
                    $schema[$key] = $val;
                }

                // and finally save the schema
                try {
                    $this->Schema->save(array('Schema' => $schema));
                } catch (MongoConnectionException $exception) {
                    // here mongo connection is down, so just kindly remove the data from SQL
                    // and redirect

                    $this->Feed->delete($this->Feed->id);

                    $this->Session->setFlash('Something went wrong, please try again later.', 'error');
                    $this->redirect(array('action' => 'my'));
                }

                $this->Session->setFlash(__('The repository has been saved'), 'success');
                $this->redirect(array('action' => 'edit', $this->Feed->id));
            } else {
                $this->Session->setFlash(__('The repository could not be saved. Please, try again.'), 'error');
            }
        }
        $users = $this->Feed->User->find('list');
        $this->set(compact('users'));
    }

    /**
     * This method edits the feed
     *
     * @author Marcin Wawrzyniak
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null)
    {
        $this->Feed->id = $id;

        if (!$this->Feed->existsForUser()) {
            throw new NotFoundException(__('Invalid repository'));
        }

        $schema = $this->Schema->find(
            'first',
            array(
                'conditions' => array(
                    'id' => (int)$id
                )
            )
        );

        $this->set('schema', $schema);
        $this->set('defaults', $schema['Schema']);

        // columns without _id
        unset($schema['Schema']['_id']);
        $columns = array_keys($schema['Schema']);
        $this->set('columns', $columns);


        $this->set(
            'data',
            $this->Feed->find(
                'first',
                array(
                    'conditions' => array(
                        'Feed.id' => $id,
                        'Feed.user_id' => $this->Auth->user('id')
                    )
                )
            )
        );

        if ($this->request->is('post') || $this->request->is('put')) {

            $dropdown = array();
            foreach ($this->request->data['Dropdown'] as $field => $values) {
                if (strlen(join('', $values)) > 0) {

                    // for explanation just check out add() action
                    // this is the same, but handles old (already saved) non-numeric fields
                    if (is_numeric($field) && isset($this->request->data['Schema']['new'][$field])) {

                        $dropdown[$this->request->data['Schema']['new'][$field]] = array_filter(
                            $values,
                            function ($var) {
                                return !empty($var);
                            }
                        );

                    } else {

                        $dropdown[$field] = array_filter(
                            $values,
                            function ($var) {
                                return !empty($var);
                            }
                        );

                    }

                } else {
                    if (!is_numeric($field)) {
                        $dropdown[$field] = array();
                    }
                }
            }

            if (strlen(trim(join('', $this->request->data['Schema']['old']))) == 0) {
                $this->Feed->validationErrors['fields'] = __('You have to specify at least one field.');
                // return;
            }

            if ($this->Feed->save($this->request->data, true, array('user_id', 'description', 'completed', 'type'))) {
                $this->request->data['Schema']['_id'] = (int)$this->request->data['Schema']['id'];

                // before we change column names etc
                foreach ($dropdown as $key => $val) {
                    $schema[$key] = $val;
                }

                $cacheKey = $this->Feed->field('slug') . '.type';

                // we need to save default schema values if any
                $this->Schema->id = (int)$this->request->data['Schema']['id'];
                $this->Schema->save($schema);

                // iterate over already existing fields in mongo
                foreach ($this->request->data['Schema']['old'] as $oldColumn => $newColumn) {

                    if ($oldColumn == $newColumn) {
                        continue;
                    }

                    if (empty($newColumn)) {

                        // we remove some column from schema
                        $this->Schema->updateAll(
                            array('$unset' => array($oldColumn => "1")),
                            array('_id' => (int)$this->request->data['Schema']['id'])
                        );

                        // and any rows
                        $this->Row->updateAll(
                            array('$unset' => array($oldColumn => "1")),
                            array('feed_id' => (int)$id)
                        );

                    } else {

                        // we rename old column to new column in schema
                        $this->Schema->updateAll(
                            array('$rename' => array($oldColumn => $newColumn)),
                            array('_id' => (int)$this->request->data['Schema']['id'])
                        );

                        // and in all existing rows

                        $this->Row->updateAll(
                            array('$rename' => array($oldColumn => $newColumn)),
                            array('feed_id' => (int)$id)
                        );

                    }
                }

                // iterate over new fields (not currently present in mongo)
                foreach ($this->request->data['Schema']['new'] as $oldColumn => $newColumn) {

                    if (empty($newColumn)) {
                        continue;
                    }

                    $this->Schema->updateAll(
                        array('$set' => array($newColumn => $dropdown[$newColumn])),
                        array('_id' => (int)$this->request->data['Schema']['id'])
                    );

                    $this->Row->updateAll(
                        array('$set' => array($newColumn => "")),
                        array('feed_id' => (int)$id)
                    );

                }

                $this->Session->setFlash(__('The repository has been saved'), 'success');
                $this->redirect(array('action' => 'edit', $id));
            } else {
                $this->Session->setFlash(__('The repository could not be saved. Please, try again.'), 'error');
                return;
            }
        } else {

            $this->request->data = $this->Feed->find(
                'first',
                array(
                    'conditions' => array(
                        'Feed.id' => $id,
                        'Feed.user_id' => $this->Auth->user('id')
                    )
                )
            );


            if (empty($this->request->data)) {
                throw new NotFoundException('Following address does not exist for currently logged in user.');
            }
        }
    }

    /**
     * This method deletes the feed.
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id)
    {
        $this->layout = 'ajax';

        if (!$this->request->is('post')) {
            die('ERROR');
        }

        $this->Feed->id = $id;

        $userHasRightsToThatFeed = $this->Feed->find(
            'first',
            array(
                'conditions' => array(
                    'Feed.id' => $id,
                    'Feed.user_id' => $this->Auth->user('id'),
                    'Feed.status' => false
                )
            )
        );

        if ($userHasRightsToThatFeed['Feed']['status'] === AppModel::STATUS_DELETED) {
            $this->Session->setFlash('This repository is already deleted!', 'error');
            $this->redirect($this->referer());
        }

        if (!$userHasRightsToThatFeed) {
            $this->Session->setFlash('You have no rights to delete that repository!', 'error');
            $this->redirect($this->referer());
        }

        if (!$this->Feed->existsForUser()) {
            $this->Session->setFlash('That repository does not exist!', 'error');
            $this->redirect($this->referer());
        }

        if ($this->Feed->hasPremiumUsers()) {
            $this->Session->setFlash('Cannot delete because you have some premium users!', 'error');
            $this->redirect($this->referer());
        }

        if ($this->Feed->deleteSafe()) {
            if ($this->Feed->field('type') == 'premium') {
                Memcached::getInstance()->delete($this->Feed->field('slug') . '/type');
            }

            Memcached::getInstance()->delete($this->Feed->field('slug') . '/completed');
            Memcached::getInstance()->delete($this->Feed->field('slug'));

            $this->Row->deleteAll(array('Row.feed_id' => $this->Feed->field('id')));

            $this->Session->setFlash('Repository deleted!', 'success');
        } else {
            $this->Session->setFlash('Unexpected error, please try again.', 'error');
        }
        $this->redirect($this->referer());
    }

    public function toggle()
    {
        $field = $this->request->params['pass'][0];
        $id = $this->request->params['pass'][1];

        if (empty($id) || empty($field)) {
            throw new BadRequestException();
        }

        $this->Feed->id = $id;

        if (!$this->Feed->exists()) {
            throw new NotFoundException();
        }

        if ($field == 'type') {
            $newType = $this->Feed->field($field) == 'free' ? 'premium' : 'free';
            $this->Feed->saveField($field, $newType);
        } else {
            if ($field == 'completed') {
                // check if there are some users of this API

                if ($this->Feed->hasPremiumUsers()) {
                    $this->Session->setFlash(
                        __("Cannot change completed status, because some users still use that API."),
                        "error"
                    );
                    $this->redirect($this->referer());
                }

                $newVal = !(int)$this->Feed->field($field);
                $this->Feed->saveField($field, $newVal);
                Memcached::getInstance()->set($this->Feed->field('slug') . '/completed', $newVal);
            } else {
                throw new BadMethodCallException();
            }
        }

        $this->redirect($this->referer());
    }


    /*public function websocket()
    {
        $data = array();

        $keys = $this->Key->find('all', array(
            'fields' => array('id','key', 'requests'),
            'contain' => array('Feed' => array('fields' => array('slug'))),
            'order' => 'requests asc',
            'conditions' => array(
                'type' => 'premium'
            ),
            'group' => 'Key.id',
            'limit' => 100
        ));


        foreach($keys as $key) {
            $requests = Memcached::getInstance()->get($key['Feed']['slug'] . '/' . $key['Key']['key']);

            $data[] = array(
                'slug' => $key['Feed']['slug'],
                'requests' => $requests,
                'key'=>$key['Key']['key']
            );
        }


        sleep(2);
        echo json_encode($data);
        die;
    }
    public function admin()
    {

    }*/
}
