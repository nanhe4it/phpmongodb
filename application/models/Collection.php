<?php

class Collection extends Model {

    public function createCollection($db, $collection, $capped = false, $size = 0, $max = 0) {
        try {
             $this->mongo->{$db}->createCollection($collection, $capped, $size, $max);
             if(!$capped){
                $this->mongo->{$db}->selectCollection($collection)->ensureIndex(array("_id" => 1));
             }
            return TRUE;
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    public function removeCollection($db, $collection, $criteria = array()) {
        try {
            return $this->mongo->{$db}->{$collection}->remove($criteria);
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    public function dropCollection($db, $collection) {
        try {
            return $this->mongo->{$db}->{$collection}->drop();
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    public function totalRecord($db, $collection) {

        try {
            return $this->mongo->{$db}->{$collection}->count();
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

}