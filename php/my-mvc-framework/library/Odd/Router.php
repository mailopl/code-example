<?php
class Router{
    protected $_get;
    protected $_config;

    public function __construct($config = false){
        if (isset($config) && $config instanceof Config){
          $this->_config = $config;
        }
        global $_GET;
        $this->_get = $_GET;

        if ( !$this->_get['model']){
          $this->_get['model'] = 'index';
        }
        if ( !$this->_get['action'] ){
          $this->_get['action'] = 'index';
        }

    }
    public function __get($what){
      return $this->_get[$what];
    }

    public function redirect($array){
        if (isset($array['controller'])){
          //w array od groma informacji musi byc, trzeba to ywzglednic
            //$array['controller']
            //$array['action']
            //header("Location:");
        }else{
          //header("Location:");
        }
    }

};