<?php
class BaseView{
    protected $_dir;
    protected $_cacheOn;
    protected $_data;
    protected $_layoutName;
    protected $_layout;

    public function __construct(){
        $this->_layout = new BaseLayout();

        $this -> setDirectory("./views/")
              -> setCache(false);

        $this -> _layout -> setDirectory("./views");

        $ld = Registry::getRegistry('layoutDirectory');
        if ($ld){
            $this -> _layout -> setLayoutName($ld);
        }
    }

    public function setDirectory($dir){
      //if dir ok
      $this->_dir = $dir;
      return $this;

    }

    public function setCache($bool){
        $this->_cacheOn = $bool;
        return $this;
    }
    public function assign($name, $value=false){
      //jak podane name tylko, to to name to jest value w zasadzie, a name zgadujemy :)
        if (!$this->_data[$name]){
      $this->_data[$name] = $value;
      }else throw new Exception("$name exists.");
    }

    public function __get($what){
      return $this->_data[$what];
    }

    public function display($controller, $action){
        if (!$this->_dir){
          $this->_dir = 'views';
        }
    ob_start();
        require_once $this->_dir .'/'.strtolower($controller).'/'.strtolower($action).'.php';
        $this->_data['pageContent'] = ob_get_clean();

        $this->_layout-> setViewHandle($this);

        $this->_layout->display();

        //require_once $this->_dir .'/layouts/'.$this->_layoutName.'.phtml';
        //strtolower($controller).'/'.strtolower($action).'.phtml';

    }
};