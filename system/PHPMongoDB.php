<?php
/**
 * @package PHPmongoDB
 * @version 1.0.0
 */
defined('PMDDA') or die('Restricted access');

class PHPMongoDB {

    protected static $instance = null;
    protected $mongo;
    /**
     * @param string $server [optional]
     * @param array $options [optional]
     * @return mixed (Object of MongoClient|Mongo
     */
    public static function getInstance($server='', array $options = array()) {
        if (is_null(self::$instance)) {
            self::$instance = new self($server, $options);
        }

        return self::$instance;
    }
    /**
     * 
     * @return mixed (Object of MongoClient|Mongo
     */
    public function getConnection(){
        return $this->mongo;
    }
    /**
     * 
     * @param string $server  [optional]
     * @param array $options [optional]
     */
    private function __construct($server='', array $options = array()) {
        
        if (class_exists("MongoClient")) {
            $this->mongo = new MongoClient($server, $options) or die('nanhe');
        } else {
            $this->mongo =new Mongo($server, $options);
        }
    }

}

?>
