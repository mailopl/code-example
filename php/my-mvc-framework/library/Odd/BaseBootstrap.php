<?php
class BaseBootstrap{
    public $_data;

    public function __construct(Router $fc){

        global $GLOBALS;
        if (!($fc instanceof Router)){
            throw new Exception("Not proper Router obj.");
        }
        $_data = array();

        //Create class name, basing on GET
        $className = ucfirst($fc -> model) .'Controller';
        $classMethod = $fc  -> action; 

        //creates someClassObj and deploys someClassObj->someMethod();

        //jesli ponizej, zamiast globals, zrobimy $_data to bedzie ladneij

        //eval('$this->_data["'.$className.'Obj"]=new '.$className.'();');
        //eval('$this->_data["'.$className.'Obj"]->setBootstrapHandle($this);');
        //eval('$this->_data["'.$className.'Obj"]->'.$classMethod  .'("'.$className.'");');
        $this->_data[$className . 'Obj'] = new $className();
        $this->_data[$className . 'Obj']->setBootstrapHandle($this);
        $this->_data[$className . 'Obj']->$classMethod($className);

    }
};