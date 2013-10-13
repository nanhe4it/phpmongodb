<?php
defined('PMDDA') or die('Restricted access');
/*
 * Model
 */

class Collection extends Model {

    public function createCollection($db, $collection, $capped = false, $size = 0, $max = 0) {
        try {
            $this->mongo->{$db}->createCollection($collection, $capped, $size, $max);
            if (!$capped) {
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

    public function getIndexInfo($db, $collection) {
        try {
            return $this->mongo->{$db}->{$collection}->getIndexInfo();
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    public function deleteIndex($db, $collection, $name) {
        try {
            return $this->mongo->{$db}->command(array("deleteIndexes" => $this->mongo->{$db}->{$collection}->getName(), "index" => $name));
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    public function createIndex($db, $collection, $key, $options = array()) {

        try {
            return $this->mongo->{$db}->{$collection}->ensureIndex($key, $options);
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    public function insertJSON($db, $collection, $json) {
        try {
            $code = "db.getCollection('" . $collection . "').insert(" . $json . ");";
            return $this->mongo->{$db}->execute($code);
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }
    public function removeById($db, $collection, $id) {
        try {
            
            return $this->mongo->{$db}->{$collection}->remove(array('_id' => new MongoId($id)),  array("justOne" => true));
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }
    public function findById($db, $collection, $id) {
        try {
            
            return $this->mongo->{$db}->{$collection}->findOne(array('_id' => new MongoId($id)));
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

}

//End of class