<?php
App::uses('FormAuthenticate', 'Controller/Component/Auth');

class CustomFormAuthenticate extends FormAuthenticate
{

    protected function _password($password)
    {
        return self::hash($password);
    }

    public static function hash($password)
    {
        return $password;
    }
}