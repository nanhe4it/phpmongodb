<?php defined('PMDDA') or die('Restricted access'); ?>
<?php
class Database extends Model{
    public function createDB($name) {
        try {

            return $this->mongo->selectDB($name)->execute("function(){}");
        } catch (MongoConnectionException $e) {

            exit($e->getMessage());
        }
    }
    public function dropDatabase($db) {
        return $this->mongo->{$db}->drop();
    }
    
    public function renamdDatabase($oldDB,$newDB){
        $response=$this->copyDatabase($oldDB, $newDB);
        if($response['ok']==1){
           return $this->mongo->{$oldDB}->command(array('dropDatabase' => 1));
        }
        return $response;
    }
    public function repair($db){
        return $this->mongo->{$db}->repair();
    }
    
}