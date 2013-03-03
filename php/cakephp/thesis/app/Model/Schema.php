<?php
App::uses('AppModel', 'Model');

class Schema extends AppModel
{
    public $useDbConfig = 'mongo';
    public $safeDelete = false;
}

