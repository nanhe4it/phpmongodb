<?php

class PHPMongoDB {

    public $mongo;

    public function __construct() {
        $this->mongo = new Mongo();
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

    public function copyDatabase($fromdb,$todb,$fromhost='localhost') {

        $response = $this->mongo->admin->command(array('copydb' => 1,'fromhost' => $fromhost,'fromdb' => $fromdb,'todb' => $todb));
        return $response;
    }

}


?>
