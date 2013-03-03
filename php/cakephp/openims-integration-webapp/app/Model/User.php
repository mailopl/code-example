<?php
App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');

/**
 * User Model
 *
 * @property UtilityFunction $UtilityFunction
 * @property UtilityFunctionsParameter $UtilityFunctionsParameter
 */
class User extends AppModel
{

    public $actsAs = array('Containable');
    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'name';


    public $belongsTo = array(
        'Impi' => array(
            'className' => 'Impi',
            'foreignKey' => 'impi_id',
        )
    );

    /**
     * hasAndBelongsToMany associations
     *
     * @var array
     */
    public $hasAndBelongsToMany = array(
        'UtilityFunction' => array(
            'className' => 'UtilityFunction',
            'joinTable' => 'users_utility_functions',
            'foreignKey' => 'user_id',
            'associationForeignKey' => 'utility_function_id',
            'unique' => 'keepExisting',
        )
    );

    public function beforeSave($options = array())
    {
        // hash password
        if (isset($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
        return true;
    }

}
