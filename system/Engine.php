<?php

session_start();
define('PMDDA', TRUE);
class Engine {

    protected $system;
    
    public function __construct() {

        $this->load();
    }

    public function start() {

       $this->system = new System();
       $this->system->start();
    }
    

    public function load() {
        spl_autoload_register('self::autoloadSystem');
        spl_autoload_register('self::autoloadController');
        spl_autoload_register('self::autoloadModel');
    }

    public static function autoloadSystem($class) {

        $fileWithPath = dirname(__FILE__) . '/' . $class . '.php';
        self::includes($fileWithPath);
    }

    public static function autoloadController($class) {
        $fileWithPath = getcwd() . '/application/controllers/' . str_replace('Controller','', $class) . '.php';
        self::includes($fileWithPath);
    }

    public static function autoloadModel($class) {
        $fileWithPath = getcwd() . '/application/models/' .$class. '.php';
        self::includes($fileWithPath);
        
    }

    public static function includes($fileWithPath) {
        if (is_readable($fileWithPath)) {
            require_once ($fileWithPath);
        }
    }

}

?>