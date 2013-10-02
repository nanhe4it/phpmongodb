<?php

class Model  {
    protected $mongo;

    public function __construct() {
        $this->mongo=PHPMongoDB::getInstance()->getConnection();
    }

    public function listDatabases() {
        return $this->mongo->admin->command(array("listDatabases" => 1));
    }

    public function getMongoInfo() {
        return $this->mongo->adimin->command(array('buildinfo' => true));
    }

    public function renameCollection($oldCollecton, $newCollection, $dbFrom, $dbTo = false) {
        if (!$dbTo) {
            $dbTo = $dbFrom;
        }

        try {
            $command = array("renameCollection" => "$dbFrom.$newCollection", "to" => "$dbTo.$oldCollecton");
            return $this->mongo->admin->command($command);
        } catch (MongoConnectionException $e) {
            exit($e);
        }
    }

    public function copyDatabase($fromdb, $todb, $fromhost = 'localhost') {

        $response = $this->mongo->admin->command(array('copydb' => 1, 'fromhost' => $fromhost, 'fromdb' => $fromdb, 'todb' => $todb));
        return $response;
    }

    public function listCollections($db,$includeSystemCollections = false ) {
        return $this->mongo->{$db}->listCollections($includeSystemCollections);
    }

    public function getCollectionNames($db,$includeSystemCollections = false ) {
        return $this->mongo->{$db}->getCollectionNames($includeSystemCollections);
    }
    public function count($db,$collection){
        return $this->mongo->{$db}->{$collection}->count();;
    }

    public function find($db, $collection, $query = array(), $fields = array(), $limit = 10, $skip = 0) {

        return $this->mongo->{$db}->{$collection}->find($query, $fields)->limit($limit)->skip($skip);
    }

    public function insert($db, $collection, $a = array(), $options = array()) {
        return $this->mongo->{$db}->{$collection}->insert($a);
    }

}