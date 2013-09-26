<?php

class Collection extends Model {

    public function createCollection($db, $collection) {
        $collection = $this->mongo->{$db}->createCollection($collection);
        return $collection->getName();
        die;
    }
     public function removeCollection($db, $collection, $criteria = array()) {
        return $this->mongo->{$db}->{$collection}->remove($criteria);
    }

    public function dropCollection($db, $collection) {
        return $this->mongo->{$db}->{$collection}->drop();
    }
    
    public function totalRecord($db, $collection){
        return $this->mongo->{$db}->{$collection}->count();
    }

}