<?php
App::uses('AppModel', 'Model');
/**
 * UsersUtilityFunction Model
 *
 * @property User $User
 * @property UtilityFunction $UtilityFunction
 * @property Protocol $Protocol
 * @property Parameter $Parameter
 */
class UsersUtilityFunction extends AppModel
{
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),
        'UtilityFunction' => array(
            'className' => 'UtilityFunction',
            'foreignKey' => 'utility_function_id',
        ),
        'Protocol' => array(
            'className' => 'Protocol',
            'foreignKey' => 'protocol_id',
        )
    );

    /**
     * hasAndBelongsToMany associations
     *
     * @var array
     */
    public $hasAndBelongsToMany = array(
        'Parameter' => array(
            'className' => 'Parameter',
            'joinTable' => 'users_utility_functions_parameters',
            'foreignKey' => 'users_utility_function_id',
            'associationForeignKey' => 'parameter_id',
            'unique' => 'keepExisting'
        )
    );

}
