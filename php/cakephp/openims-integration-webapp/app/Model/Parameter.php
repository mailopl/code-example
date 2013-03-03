<?php
App::uses('AppModel', 'Model');
/**
 * Parameter Model
 *
 * @property UtilityFunction $UtilityFunction
 * @property UsersUtilityFunction $UsersUtilityFunction
 */
class Parameter extends AppModel
{

    public $actsAs = array('Containable');

    public $virtualFields = array(
        'name_with_default' => 'CONCAT(Parameter.name, " (default=", Parameter.default, ")")'
    );
    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'name';

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'utility_function_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'name' => array(
            'isUniquePerUtility' => array(
                'rule' => 'isUniquePerUtility',
                'message' => 'Parameter name must be unique for utility function.',
                'required' => true,
                'on' => 'create'
            ),
        ),
    );


    public function isUniquePerUtility($var)
    {
        $exists = $this->find(
            'count',
            array(
                'conditions' => array(
                    'utility_function_id' => $this->data['Parameter']['utility_function_id'],
                    'Parameter.name' => $this->data['Parameter']['name']
                )
            )
        );

        return $exists <= 0;
    }

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'UtilityFunction' => array(
            'className' => 'UtilityFunction',
            'foreignKey' => 'utility_function_id',
        )
    );

    /**
     * hasAndBelongsToMany associations
     *
     * @var array
     */
    public $hasAndBelongsToMany = array(
        'UsersUtilityFunction' => array(
            'className' => 'UsersUtilityFunction',
            'joinTable' => 'users_utility_functions_parameters',
            'foreignKey' => 'parameter_id',
            'associationForeignKey' => 'users_utility_function_id',
            'unique' => 'keepExisting',
        )
    );

}
