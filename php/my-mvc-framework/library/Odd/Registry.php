<?php
/*
It's a method to store data instead of using ugly globals
*/
class Registry{
  public static $data = array();
    public static function setRegistry($name, $value){
        self::$data[$name] = $value;
    }
    public static function getRegistry($name){
      return self::$data[$name];
    }

};