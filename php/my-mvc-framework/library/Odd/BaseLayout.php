<?php
class BaseLayout{
    protected $_view;
    protected $_dir;
    protected $_layoutName;
    protected $_data;

    public function __construct(BaseView $view=NULL){
        if (($view instanceof BaseView)){
            $this->_view = $view;
            $this->_data = $view->_data;
        }
    }

     public function setDirectory($dir){
      $this->_dir = $dir;
      return $this;

    }
    public function setViewHandle($view){
      if (($view instanceof BaseView)){
            $this->_view = $view;
            $this->_data = $view->_data;
            //var_dump($view->pageContent);
        }

    }
    public function __get($what){
        return $this->_view->$what;
    }
     public function setLayoutName($dir){
      //if dir ok
      $this->_layoutName = $dir;
      return $this;
    }

    public function display(){
        if (!$this->_layoutName){
          $this->_layoutName = 'default';
        }
        require_once $this->_dir .'/layouts/'.$this->_layoutName.'.php';
    }

};