<?php
App::uses('AppModel', 'Model');
/**
 * UsersUtilityFunctionsParameter Model
 *
 * @property UsersUtilityFunction $UsersUtilityFunction
 * @property Parameter $Parameter
 */
class UsersUtilityFunctionsParameter extends AppModel
{
    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'users_utility_function_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'parameter_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'value' => array(
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
        'UsersUtilityFunction' => array(
            'className' => 'UsersUtilityFunction',
            'foreignKey' => 'users_utility_function_id',
        ),
        'Parameter' => array(
            'className' => 'Parameter',
            'foreignKey' => 'parameter_id',
        )
    );
}
