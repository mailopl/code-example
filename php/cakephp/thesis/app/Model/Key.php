<?php
App::uses('AppModel', 'Model');

/**
 * Key Model
 *
 * @property User $User
 */
class Key extends AppModel
{
    public $actsAs = array('Containable');

    public $validate = array(
        'user_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'transaction_status' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'key' => array(
            'notempty' => array(
                'rule' => array('notempty'),
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
        'Feed' => array(
            'className' => 'Feed',
            'foreignKey' => 'feed_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}
