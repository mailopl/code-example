<?php
App::uses('AppModel', 'Model');
/**
 * Favourite Model
 *
 * @property Feed $Feed
 * @property User $User
 */
class Favourite extends AppModel
{
    protected $safeDelete = false;

    public $actsAs = array('Containable');

    public $belongsTo = array(
        'Feed' => array(
            'className' => 'Feed',
            'foreignKey' => 'feed_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}
