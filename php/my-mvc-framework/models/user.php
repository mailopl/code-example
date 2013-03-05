<?php
class UserModel extends BaseModel{
    protected $_table = 'users';
    $hasMany = 'Comment';
    public function __construct(){
        parent::__construct();
    }
};