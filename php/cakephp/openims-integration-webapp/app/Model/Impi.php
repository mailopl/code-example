<?php
App::uses('AppModel', 'Model');
/**
 * Impu Model
 *
 */
class Impi extends AppModel
{

    public $actsAs = array('Containable');
    /**
     * Use database config
     *
     * @var string
     */
    public $useDbConfig = 'openims';

    /**
     * Use table
     *
     * @var mixed False or table name
     */
    public $useTable = 'impi';

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'identity';

}
