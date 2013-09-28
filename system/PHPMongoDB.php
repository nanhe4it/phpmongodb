<?php

class PHPMongoDB {

    protected static $instance = null;
    protected $mongo;

    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }
    public function getConnection(){
        return $this->mongo;
    }

    public function __construct() {
        $server = '127.0.0.1:27017';
        $options = array('username' => '', 'password' => '', 'db' => '');
        if (class_exists("MongoClient")) {
            $this->mongo = new MongoClient();
        } else {
            $this->mongo = new Mongo();
        }
    }

}

?>
