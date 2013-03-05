<?php

//Automagic function loading. We dont need to do anything here. No more includes dood!
function __autoload($class_name){

    if (strripos($class_name, 'Base') !== false){
            require_once './library/Odd/'. $class_name . '.php';
    }else if (strripos($class_name, 'Controller')){
        //if (strripos($class_name, 'Base') !== false){
        //    require_once './library/Odd/'. $class_name . '.php';
        //}else{
            require_once './controllers/'. $class_name . '.php';
        //}
    }else if (strripos($class_name, 'Model') !== false){

        $class_name = substr($class_name,
                        0,
                        strripos($class_name, 'Model'));


        require_once './models/'. $class_name . '.php';

    }else{
        require_once './library/Odd/'. $class_name . '.php';
    }

}

function OddExceptionHandler($exception) {
  echo "Uncaught exception: <br /><pre>" , $exception->getMessage(), "</pre>\n";
}

set_exception_handler('OddExceptionHandler');