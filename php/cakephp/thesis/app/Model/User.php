<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 * @property Feed $Feed
 */
class User extends AppModel
{
    public $displayField = 'email';

    public $hasMany = array(
        'Feed' => array(
            'className' => 'Feed',
            'foreignKey' => 'user_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
}
