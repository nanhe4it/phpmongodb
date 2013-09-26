<?php

class Model extends PHPMongoDB {

    public function __construct() {
        parent::__construct();
    }

    public function listCollections($db) {
        return $this->mongo->{$db}->listCollections();
    }

    public function getCollectionNames($db) {
        return $this->mongo->{$db}->getCollectionNames();
    }

    public function find($db, $collection, $query = array(), $fields = array(), $limit = 10, $skip = 0) {

        return $this->mongo->{$db}->{$collection}->find($query,$fields)->limit($limit)->skip($skip);
    }

    public function insert($db, $collection, $a = array(), $options = array()) {
        return $this->mongo->{$db}->{$collection}->insert($a);
    }

}