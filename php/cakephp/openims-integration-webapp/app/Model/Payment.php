<?php
App::uses('AppModel', 'Model');
/**
 * Payment Model
 *
 * @property Submitter $Submitter
 * @property Client $Client
 */
class Payment extends AppModel
{

    public $actAs = array(
        'Containable'
    );
    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'submitter_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'user_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'session_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
    );
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Submitter' => array(
            'className' => 'User',
            'foreignKey' => 'submitter_id',
        ),
        'Client' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        )
    );
}
