<?php
App::uses('AppModel', 'Model');
/**
 * Protocol Model
 *
 * @property UsersUtilityFunction $UsersUtilityFunction
 */
class Protocol extends AppModel
{

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'name';


    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'UsersUtilityFunction' => array(
            'className' => 'UsersUtilityFunction',
            'foreignKey' => 'protocol_id',
            'dependent' => false,
        )
    );

    public $validate = array(
        'name' => array(
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'Protocol name must be unique in whole system.',
                'required' => true,
            ),
        ),
    );
}
