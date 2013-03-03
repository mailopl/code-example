<?php
App::uses('Memcached', 'Vendor');
App::uses('AppModel', 'Model');

/**
 * Feed Model
 *
 * @property User $User
 * @property Column $Column
 * @property Row $Row
 */
class Feed extends AppModel
{
    public $actsAs = array(
        'Containable',
        'Slug' => array(
            'slugFieldSource' => 'name',
            'slugFieldTarget' => 'slug'
        )
    );

    public $displayField = 'name';

    public $validate = array(
        'user_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Repository name must not be empty.',
                'required' => true,
                'on' => 'create'
            ),
            'isUnique' => array(
                'rule' => array('isUnique'),
                'message' => 'This repository already exists. Please choose another name.',
                'fieldName' => 'name',
                'required' => true,
                'on' => 'create'
            ),
        ),
    );

    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * Tells if Feed has some premium users at the moment
     * Because we cannot delete the feed if some users are using it.
     *
     * @return bool
     */
    public function hasPremiumUsers()
    {
        $this->bindModel(
            array(
                'hasMany' => array(
                    'Key' => array(
                        'className' => 'Key',
                        'foreignKey' => false,
                        'conditions' => array('Key.feed_id = Feed.id')
                    )
                )
            )
        );

        return $this->Key->find(
            'count',
            array(
                'conditions' => array(
                    'Key.feed_id' => $this->id,
                    'Key.requests >' => 0,
                    'Key.status' => null,
                    'Key.transaction_status >' => 0,
                )

            )
        ) > 0;
    }

    /**
     * This method updates Memcache data accordingly to the changes made in MySQL
     * When a type of the feed or status is changed, the update is send.
     *
     * @param array $options
     * @return bool
     */
    public function beforeSave(array $options = array())
    {
        parent::beforeSave($options);

        // if we only modify the type, we need to ensure that memcached gets updated
        if (!empty($this->id)) {
            // if there's only one field, and it's type
            if (!empty($this->data['Feed']['type'])) {
                if ($this->data['Feed']['type'] == 'free') {
                    //we need to remove from memcached
                    $cacheKey = $this->field('slug') . '/type';
                    Memcached::getInstance()->delete($cacheKey);

                } else {
                    // we need to set to memcached
                    $cacheKey = $this->field('slug') . '/type';
                    Memcached::getInstance()->set($cacheKey, 'premium');
                }
            }
        } else {
            // if the feed is premium, tell it to memcached
            if (isset($this->data['Feed']['type']) && $this->data['Feed']['type'] == 'premium') {
                $cacheKey = $this->data['Feed']['slug'] . '/type';
                Memcached::getInstance()->set($cacheKey, 'premium');
            }
        }
        return true;
    }

    /**
     * This method - after the record was inserted to MySQL, saves association slug<->id to memcached.
     *
     * @param bool $created
     * @return bool|void
     */
    public function afterSave($created)
    {
        parent::afterSave($created);

        if ($created && isset($this->data['Feed']['slug']) && !empty($this->data['Feed']['slug'])) {
            // and always when created, tell memcached that ID of that feed (slug) is:
            Memcached::getInstance()->set($this->data['Feed']['slug'], $this->id);
        }

        if (isset($this->data['Feed']['completed'])) {
            Memcached::getInstance()->set($this->field('slug') . '/completed', $this->data['Feed']['completed']);
        }

        return true;
    }
}
