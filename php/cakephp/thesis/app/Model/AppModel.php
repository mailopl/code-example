<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model
{
    const STATUS_DELETED = 666; // when the record is deleted
    const STATUS_ACTIVE = null; // initial state of feed

    protected $safeDelete = true; // if true, won't delete the record from db but set status field to DELETED
    /**
     * Safely deletes the record from database by setting the status to STATUS_COMPLETED
     * WARNING: you have to handle it in your queries or beforeFind()
     *
     * @return bool
     */
    public function deleteSafe()
    {
        return $this->saveField('status', AppModel::STATUS_DELETED);
    }


    /**
     * This method just handles status field in beforeFind()
     *
     * @param array $conditions
     * @return array|mixed
     */
    public function beforeFind($conditions)
    {
        if ($this->safeDelete) {

            if (
                !isset($conditions['conditions'][$this->name . '.status']) &&
                !isset($conditions['conditions']['status']) &&
                isset($conditions['conditions'][$this->name . '.status']) &&
                !($conditions['conditions'][$this->name . '.status'] !== false)
            ) {

                $conditions['conditions']['and'] = array(
                    'or' => array(
                        $this->name . '.status !=' => AppModel::STATUS_DELETED,
                        $this->name . '.status' => AppModel::STATUS_ACTIVE

                    )
                );
            }
            if (isset($conditions['conditions'][$this->name . '.status']) &&
                $conditions['conditions'][$this->name . '.status'] === false
            ) {
                unset($conditions['conditions'][$this->name . '.status']);

            }
        }
        return $conditions;
    }

    /**
     * This method is the same as exists() but makes use of `status` field
     *
     * @override exists($id=null)
     * @param null $id
     * @return bool
     */
    public function existsForUser($id = null)
    {
        if ($id === null) {
            $id = $this->getID();
        }
        if ($id === false) {
            return false;
        }
        $conditions = array(
            $this->alias . '.' . $this->primaryKey => $id,
            'or' => array( // only here modified
                $this->alias . '.' . 'status !=' => AppModel::STATUS_DELETED,
                $this->alias . '.' . 'status' => AppModel::STATUS_ACTIVE

            )
        );
        $query = array('conditions' => $conditions, 'recursive' => -1, 'callbacks' => false);
        return ($this->find('count', $query) > 0);
    }


    /**
     * This method decrements given $field with given $id.
     *
     * @param null $field
     * @param null $id
     * @param bool $incOrDec
     * @return bool
     */
    public function decrement($field = null, $id = null, $incOrDec = true)
    {
        return $this->increment($field, $id, false);
    }

    /**
     * This method increments given $field with given $id.
     *
     * @param null $field
     * @param null $id
     * @param bool $incOrDec
     * @return bool
     */
    public function increment($field = null, $id = null, $incOrDec = true)
    {
        if ($field === null || $id === null) {
            return false;
        }

        $this->unbindModel(array('belongsTo' => array('User')));
        return $this->updateAll(
            array($this->alias . '.' . $field => $this->alias . '.' . $field . ($incOrDec ? '+1' : '-1')),
            array($this->alias . '.' . '.id' => $id)
        );
    }


}
