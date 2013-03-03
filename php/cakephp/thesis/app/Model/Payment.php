<?php
App::uses('AppModel', 'Model');
/**
 * Payment Model
 *
 * @property User $User
 * @property Key $Key
 */
class Payment extends AppModel
{
    public $actsAs = array('Containable');

    public $validate = array(
        'user_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'key_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
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
        ),
        'Key' => array(
            'className' => 'Key',
            'foreignKey' => 'key_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Feed' => array(
            'className' => 'Feed',
            'foreignKey' => 'feed_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );


    /**
     * Fetches sold keys
     *
     * @param $user_id
     * @return array
     */
    public function fetchSold($user_id)
    {
        $p = $this->find(
            'all',
            array(
                'conditions' => array(
                    'Payment.user_id' => $user_id
                ),
                'contain' => array(
                    'Feed' => array(
                        'fields' => array(
                            'id',
                            'name',
                            'slug',
                            'views_count',
                            'type',
                            'rows_count'
                        )
                    ),
                    'Key' => array(
                        'fields' => array(
                            'requests'
                        )
                    ),
                    'User' => array(
                        'fields' => array(
                            'id',
                            'email'
                        )
                    ),
                )
            )
        );
        $out = array();

        foreach ($p as $item) {
            $out[$item['Feed']['name']][] = $item;
        }
        return $out;
    }
}
