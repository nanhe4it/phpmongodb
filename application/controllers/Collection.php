<?php defined('PMDDA') or die('Restricted access'); ?>
<?php

/*
 * Controller
 */

class CollectionController extends Controller {

    protected $db = NULL;
    protected $collection = NULL;
    protected $url = NULL;
    protected $model = FALSE;

    public function getModel() {
        if (!($this->model instanceof Collection)) {
            return $this->model = new Collection();
        } else {
            return $this->model;
        }
    }

    public function Index() {

        $this->db = isset($_REQUEST['db']) ? $_REQUEST['db'] : NULL;
        if ($this->isValidDB($this->db)) {
            $model = $this->getModel();
            $collections = $model->listCollections($this->db, TRUE);
            $collectionList = array();
            foreach ($collections as $collection) {
                $collectionList[] = array('name' => $collection->getName(), 'count' => $collection->count());
            }
            //$this->debug($collectionList);
            $data = array(
                'collectionList' => $collectionList,
                'model' => $model,
            );
            $this->application->view = 'Collection';
            $this->display('index', $data);
        } else {
            header("Location:index.php?load=Database/Index");
        }
    }

    public function Indexes() {
        $this->db = $this->request->getParam('db');
        $this->collection = $this->request->getParam('collection');
        if (empty($this->db) || empty($this->collection)) {
            header("Location:index.php?load=Database/Index");
        }
        $this->application->view = 'Collection';
        $data = $this->getModel()->getIndexInfo($this->db, $this->collection);
        $this->display('indexes', $data);
    }

    public function DeleteIndexes() {
        $this->db = $this->request->getParam('db');
        $this->collection = $this->request->getParam('collection');
        $name = trim($this->request->getParam('name'));
        if (empty($this->db) || empty($this->collection) || empty($name)) {
            header("Location:index.php?load=Database/Index");
        }
        $model = $this->getModel();
        $indexes = $model->getIndexInfo($this->db, $this->collection);
        foreach ($indexes as $index) {
            if ($index['name'] === $name) {
                $response = $model->deleteIndex($this->db, $this->collection, $index['key']);
                $this->message->sucess = 'Index deleted';
                break;
            }
        }

        $url = Theme::URL('Collection/Indexes', array('db' => $this->db, 'collection' => $this->collection));
        header("Location:$url");
    }

    public function CreateIndexes() {
        $this->db=$this->request->getParam('db');
        $this->collection=$this->request->getParam('collection');
        $fields=$this->request->getParam('fields');
        $orders=$this->request->getParam('orders');
        $name=$this->request->getParam('name');
        $unique=$this->request->getParam('unique');
        $options = array();
        for($i=0;$i<count($orders);$i++){
            $key[$fields[$i]]=(int)$orders[$i];
        }
        if(!empty($name)){
            $options['name']=$name;
        }
        if(!empty($unique)){
             $options['unique']=true;
        }
        $response=$this->getModel()->createIndex($this->db, $this->collection,$key,$options);
        
        //$this->debug($response);
        $url = Theme::URL('Collection/Indexes', array('db' => $this->db, 'collection' => $this->collection));
        header("Location:$url");
    }

    public function Record() {
        $this->db = isset($_REQUEST['db']) ? $_REQUEST['db'] : NULL;
        $this->collection = isset($_REQUEST['collection']) ? $_REQUEST['collection'] : NULL;
        if ($this->validation($this->db, $this->collection)) {
            $skip = (isset($_GET['start']) ? $_GET['start'] : 0);
            $limit = (isset($_GET['limit']) ? $_GET['limit'] : 10);
            $query = array();
            $fields = array();
            $cursor = $this->getModel()->find($this->db, $this->collection, $query, $fields, $limit, $skip);
            $cryptography = new Cryptography();
            $record = $cryptography->decode($cursor);
            $this->application->view = 'Collection';
            $format = array('json', 'document', 'php', 'array');
            $this->display('record', array('record' => $record, 'format' => $format));
        } else {

            header("Location:" . $this->url);
        }
    }

    public function SaveRecord() {

        $this->db = isset($_REQUEST['db']) ? $_REQUEST['db'] : NULL;
        $this->collection = isset($_REQUEST['collection']) ? $_REQUEST['collection'] : NULL;
        if ($this->validation($this->db, $this->collection)) {
            $type = isset($_REQUEST['type']) ? strtolower($_REQUEST['type']) : NULL;
            switch ($type) {
                case 'fieldvalue':
                    $a = array_combine($_REQUEST['fields'], $_REQUEST['values']);
                    $this->insertRecord($a);
                    break;
                case 'array':
                    $cryptography = new Cryptography();
                    $a = $cryptography->stringToArray($_POST['data']);
                    $this->insertRecord($a);
                    break;
                case 'json':
                    $a = json_decode($_POST['data'], true);
                    $this->insertRecord($a);
                    break;
            }
        }

        header("Location:" . $this->url);
    }

    private function insertRecord($a) {
        unset($a['']);
        if (count($a) > 0) {
            $this->message->sucess = count($a) . " row inserted";
            $this->getModel()->insert($this->db, $this->collection, $a);
        } else {
            $this->message->error = "Enter Field Name and Value";
        }
        $this->url = "index.php?load=Collection/Record&db=" . $this->db . "&collection=" . $this->collection;
    }

    private function validation($db = NULL, $collection = NULL) {
        if (!$this->isValidDB($db)) {
            return false;
        }
        if (!$this->isValidCollection($collection)) {
            return false;
        }
        return true;
    }

    private function isValidDB($db = NULL) {
        if (empty($db) || !isset($db) || !$this->isValidName($db)) {

            $this->message->error = 'Invalid databse';
            $this->setURL('db');
            return false;
        }

        return true;
    }

    private function isValidCollection($collection = NULL) {
        if (empty($collection) || !isset($collection)) {
            $this->message->error = "Enter Collection name";
            $this->setURL('collection');
            return false;
        } else if (!$this->isValidName($collection)) {
            $this->message->error = 'You can not use characters /\. "*<>:|? for collection name';
            $this->setURL('collection');
            return false;
        } else {
            return true;
        }
    }

    private function setURL($type = NULL) {
        switch ($type) {
            case 'db':
            case 'database':
                $this->url = "index.php?load=Database/Index";
                break;
            case 'collection':
                $this->url = (empty($this->db) ? "index.php?load=Database/Index" : "index.php?load=Collection/Index&db=" . $this->db);
                break;
            default :
                $this->url = "index.php";
                break;
        }
    }

    public function Save() {

        $this->db = $this->request->getPost('db');
        $this->collection = $this->request->getPost('collection');
        $capped = $this->request->getPost('capped');
        $capped = !empty($capped) ? TRUE : FALSE;
        $size = $this->request->getPost('size');
        $size = !empty($size) ? $size : 0;
        $max = $this->request->getPost('max');
        $max = !empty($max) ? $max : 0;

        if (!empty($this->db) && !empty($this->collection)) {
            $this->getModel()->createCollection($this->db, $this->collection, $capped, $size, $max);
            $this->message->sucess = $this->collection . " collection created.";
            $this->url = "index.php?load=Collection/Index&db=" . $this->db;
        }
        header("Location:" . $this->url);
    }

    public function Update() {
        $this->db = isset($_POST['db']) ? $_POST['db'] : NULL;
        $this->collection = isset($_POST['collection']) ? $_POST['collection'] : NULL;
        if ($this->validation($this->db, $this->collection)) {
            if ($this->isValidCollection($_POST['old_collection'])) {
                $response = $this->getModel()->renameCollection($this->collection, $_POST['old_collection'], $this->db);

                if ($response['ok'] == '1') {
                    $this->message->sucess = " collection rename successfully";
                } else {
                    $this->message->error = $response['errmsg'];
                }
                $this->url = "index.php?load=Collection/Index&db=" . $this->db;
            }
        }
        header("Location:" . $this->url);
    }

    public function Drop() {
        $this->db = isset($_POST['db']) ? $_POST['db'] : NULL;
        $this->collection = isset($_POST['collection']) ? $_POST['collection'] : NULL;
        if ($this->validation($this->db, $this->collection)) {
            $response = $this->getModel()->dropCollection($this->db, $this->collection);
            if ($response['ok'] == '1') {
                $this->message->sucess = $this->collection . " collection droped.";
            } else {
                $this->message->error = $response['errmsg'];
            }

            $this->url = "index.php?load=Collection/Index&db=" . $this->db;
        }
        header("Location:" . $this->url);
    }

    public function Remove() {

        $this->db = isset($_POST['db']) ? $_POST['db'] : NULL;
        $this->collection = isset($_POST['collection']) ? $_POST['collection'] : NULL;
        if ($this->validation($this->db, $this->collection)) {
            $response = $this->getModel()->removeCollection($this->db, $this->collection);
            $this->message->sucess = $this->collection . " collection removed.";
            $this->url = "index.php?load=Collection/Index&db=" . $this->db;
        }
        header("Location:" . $this->url);
    }

    public function Export() {
        $this->db = isset($_POST['db']) ? $_POST['db'] : NULL;
        $this->collection = isset($_POST['collection']) ? $_POST['collection'] : NULL;
        if ($this->validation($this->db, $this->collection)) {
            $collection = $this->getModel()->find($this->db, $this->collection);
        } else {
            header("Location:index.php?load=Collection/Index");
        }
    }

}