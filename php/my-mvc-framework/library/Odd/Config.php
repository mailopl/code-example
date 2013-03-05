<?php
class Config{
  public $data;
    public function __construct($iniFile){
        $this->data = parse_ini_file($iniFile);
    }
    public function __get($what){
      return $this->data[$what];
    }


};