<?php
App::uses('AppModel', 'Model');
/**
 * UtilityFunction Model
 *
 * @property Parameter $Parameter
 * @property User $User
 * @property TionsParameter $TionsParameter
 */
class UtilityFunction extends AppModel
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
        'Parameter' => array(
            'className' => 'Parameter',
            'foreignKey' => 'utility_function_id',
            'dependent' => false,
        )
    );

    public $validate = array(
        'name' => array(
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'Function name must be unique in whole system.',
                'required' => true,
            ),
        ),
    );
}
