<?php

class TestController extends Controller {

    //http://phpmongodb.loc/index.php?load=Test/index&theme=false
    protected function executeAND($query) {
        $key = array_search('$and', $query);

        if (!$key)
            return array_values($query);
        if ($query[$key - 2] == '=') {
            $left = array($query[$key - 3] => $query[$key - 1]);
        } else {
            $left = array($query[$key - 3] => array($query[$key - 2] => $query[$key - 1]));
        }
        if ($query[$key + 2] == '=') {
            $right = array($query[$key + 1] => $query[$key + 3]);
        } else {
            $right = array($query[$key + 1] => array($query[$key + 2] => $query[$key + 3]));
        }
        $and = array('$and' => array($left, $right));
        for ($i = $key - 3; $i <= $key + 3; $i++) {
            unset($query[$i]);
        }
        $query[$key + 3] = $and;
        ksort($query);
        return $this->executeAND(array_values($query));
    }

    protected function executeOR($query) {
        $key = array_search('$or', $query);

        if (!$key)
            return array_values($query);
        if (!is_array($query[$key - 1])) {
            if ($query[$key - 2] == '=') {
                $left = array($query[$key - 3] => $query[$key - 1]);
            } else {
                $left = array($query[$key - 3] => array($query[$key - 2] => $query[$key - 1]));
            }
            for ($i = $key - 3; $i < $key; $i++) {
                unset($query[$i]);
            }
        } else {
            $left = $query[$key - 1];
            unset($query[$key - 1]);
        }
        if (!is_array($query[$key + 1])) {
            if ($query[$key + 2] == '=') {
                $right = array($query[$key + 1] => $query[$key + 3]);
            } else {
                $right = array($query[$key + 1] => array($query[$key + 2] => $query[$key + 3]));
            }

            for ($i = $key + 1; $i <= $key + 3; $i++) {
                unset($query[$i]);
            }
        } else {
            $right = $query[$key + 1];
            unset($query[$key + 1]);
        }
        $query[$key] = array('$or' => array($left, $right));
        ;
        ksort($query);
        return $this->executeOR(array_values($query));
    }

    public function Index() {

        $query = array('a', '=', 1, '$and', 'b', '$ne', 2, '$or', 'c', '$lt', 6, '$and', 'd', '$gt', 8);
        //$query = array('a', '=', 2, '$and', 'a', '=', 2);
        $query = array('a', '=', 1, '$or', 'a', '=', 1, '$or', 'a', '=', 1);
        $query = $this->executeAND($query);
        $query = $this->executeOR($query);

        $this->debug($query);



        $where = $query[0];
        $results = $this->getModel()->find('test', 'number', $where);
        foreach ($results as $result) {
            $this->debug($result);
        }
    }

    //http://phpmongodb.loc/index.php?load=Test/nanhe&theme=false
    public function Nanhe() {
        $var = '1';
        $where = array('a' => is_numeric($var) ? doubleval($var) : $var);
        $where = array('$or' => array($where, array('a' => "'1'")));
        $this->debug($where);
        $results = $this->getModel()->find('test', 'number', $where);
        foreach ($results as $result) {
            $this->debug($result);
        }
    }

    //http://phpmongodb.loc/index.php?load=Test/execute&theme=false
    public function Execute() {
        $x=array(1);
        $m = new MongoClient();
        $db = $m->test;
       
        $inset = "db.getCollection('foo').insert({'name':'nanhe','age':30});";
        $response = $db->execute($inset);
        print_r($response); //Array ( [retval] => [ok] => 1 ) 
       
        $response = $m->test->execute("db.getCollection('foo').insert({'name':'happy','age':18});");
        print_r($response); //Array ( [retval] => [ok] => 1 ) 
        
        $response = $m->test->execute("db.foo.insert({'name':'prince','age':16});");
        print_r($response); //Array ( [retval] => [ok] => 1 ) 
        
        $response= $m->test->execute("return db.foo.count();"); 
        print_r($response); //Array ( [retval] => 3 [ok] => 1 ) 
        
        $response= $m->test->execute("return db.foo.findOne();"); 
        print_r($response); //Array ( [retval] => Array ( [_id] => MongoId Object ( [$id] => 5287ccbe60e2eac9a0e2f1c6 ) [name] => nanhe [age] => 30 ) [ok] => 1 ) 
        
        /*
         * If you want use find function then use toArray because The find() function returns a cursor, which can't be returned from JavaScript.
         */
        $response= $m->test->execute("return db.foo.find().toArray();"); 
        print_r($response); //[$id] => 5287cd2260e2eac9a0e2f1ca ) [name] => happy [age] => 18 ) [2] => Array ( [_id] => MongoId Object ( [$id] => 5287cd2260e2eac9a0e2f1cb ) [name] => prince [age] => 16 ) [3] => Array ( [_id] => MongoId Object ( [$id] => 5287cdea60e2eac9a0e2f1cc ) [name] => nanhe [age] => 30 ) [4] => Array ( [_id] => MongoId Object ( [$id] => 5287cdea60e2eac9a0e2f1cd ) [name] => happy [age] => 18 ) [5] => Array ( [_id] => MongoId Object ( [$id] => 5287cdea60e2eac9a0e2f1ce ) [name] => prince [age] => 16 ) ) [ok] => 1 ) 
       
        $response= $m->test->execute("return db.foo.find({'name':'nanhe'}).toArray();"); 
        print_r($response); //Array ( [retval] => Array ( [0] => Array ( [_id] => MongoId Object ( [$id] => 5287ce9b60e2eac9a0e2f1d2 ) [name] => nanhe [age] => 30 ) ) [ok] => 1 ) 
        // $id value will be different in your case 
    }

    public function debug($a) {
        echo "<pre>";
        print_r($a);
        echo "</pre>";
    }

}