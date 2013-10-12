<?php

defined('PMDDA') or die('Restricted access');
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

    public function Insert() {
        $this->db = $this->request->getParam('db');
        $this->collection = $this->request->getParam('collection');
        if (empty($this->db) || empty($this->collection)) {
            header("Location:index.php?load=Database/Index");
        }
        $this->application->view = 'Collection';
        $this->display('insert', array());
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
        $this->db = $this->request->getParam('db');
        $this->collection = $this->request->getParam('collection');
        $fields = $this->request->getParam('fields');
        $orders = $this->request->getParam('orders');
        $name = $this->request->getParam('name');
        $unique = $this->request->getParam('unique');
        $drop = $this->request->getParam('drop');
        $options = array();
        for ($i = 0; $i < count($orders); $i++) {
            $key[$fields[$i]] = (int) $orders[$i];
        }
        if (!empty($name)) {
            $options['name'] = $name;
        }
        if (!empty($unique)) {
            $options['unique'] = TRUE;
        }
        if (!empty($drop)) {

            $options['dropDups'] = TRUE;
        }
        $response = $this->getModel()->createIndex($this->db, $this->collection, $key, $options);

        //$this->debug($response);
        $url = Theme::URL('Collection/Indexes', array('db' => $this->db, 'collection' => $this->collection));
        header("Location:$url");
    }

    public function Record() {
        $this->db = $this->request->getParam('db');
        $this->collection = $this->request->getParam('collection');
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

        $this->db = $this->request->getParam('db');
        $this->collection = $this->request->getParam('collection');
        if ($this->validation($this->db, $this->collection)) {
            $type = isset($_REQUEST['type']) ? strtolower($_REQUEST['type']) : NULL;
            switch ($type) {
                case 'fieldvalue':
                    $a = array_combine($_REQUEST['fields'], $_REQUEST['values']);
                    $this->insertRecord($a);
                    break;
                case 'array':
                    $cryptography = new Cryptography();
                    $a = $cryptography->stringToArray($this->request->getParam('data'));
                    $this->insertRecord($a);
                    break;
                case 'json':
                    $response = $this->getModel()->insertJSON($this->db, $this->collection, $this->request->getParam('data'));
                    if ($response['ok'] == 1) {
                        $this->message->sucess = " row inserted";
                    }else{
                        $this->message->error='invalid json';
                    }
                    break;
            }
        }
        $this->url = "index.php?load=Collection/Record&db=" . $this->db . "&collection=" . $this->collection;
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
        $this->db = $this->request->getParam('db');
        $this->collection = $this->request->getParam('collection');
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
        $this->db = $this->request->getParam('db');
        $this->collection = $this->request->getParam('collection');
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

        $this->db = $this->request->getParam('db');
        $this->collection = $this->request->getParam('collection');
        if ($this->validation($this->db, $this->collection)) {
            $response = $this->getModel()->removeCollection($this->db, $this->collection);
            $this->message->sucess = $this->collection . " collection removed.";
            $this->url = "index.php?load=Collection/Index&db=" . $this->db;
        }
        header("Location:" . $this->url);
    }

    protected function quickExport() {
        $cursor = $this->getModel()->find($this->db, $this->collection);
        $file = new File('/tmp/', $this->collection . '.json');
        $file->delete();
        $cryptography = new Cryptography();
        while ($cursor->hasNext()) {
            $document = $cursor->getNext();
            $this->debug($document);
            $file->write($cryptography->arrayToJSON($document) . "\n");
        }
        if ($file->success) {

            $file->download();
        } else {
            $this->message->error = $file->message;
        }
    }

    protected function zip() {

        $cursor = $this->getModel()->find($this->db, $this->collection);
        $file = new File('/tmp/', $this->collection . '.json');
        $file->delete();
        $cryptography = new Cryptography();
        while ($cursor->hasNext()) {
            $document = $cursor->getNext();
            $file->write($cryptography->arrayToJSON($document) . "\n");
        }
        if ($file->success) {
            $response = $file->createZip(array($this->collection . '.json'), $this->collection . '.zip', TRUE);
            if ($response) {
                $file->download($this->collection . '.zip');
            } else {
                $this->message->error = $file->message;
            }
        } else {
            $this->message->error = $file->message;
        }
    }

    protected function customExport() {
        $fields = array();
        $query = array();
        $limit = $this->request->getParam('limit');
        $skip = $this->request->getParam('skip');
        $limit = empty($limit) ? false : $limit;
        $skip = empty($skip) ? false : $skip;
        $path = '/tmp/';
        $fileName = $this->request->getParam('file_name');
        $fileName = (empty($fileName) ? $this->collection : $fileName) . '.json';
        $cursor = $this->getModel()->find($this->db, $this->collection, $query, $fields, $limit, $skip);
        $file = new File($path, $fileName);
        $file->delete();
        $cryptography = new Cryptography();
        while ($cursor->hasNext()) {
            $document = $cursor->getNext();
            $file->write($cryptography->arrayToJSON($document) . "\n");
        }
        if ($this->request->getParam('text_or_save') == 'save') {
            if ($file->success) {
                if ($this->request->getParam('compression') == 'none') {
                    $file->download();
                } else {
                    $compressFile = $this->createCompress($fileName, $file);
                    if ($compressFile) {
                        $file->download($compressFile);
                    } else {
                        $this->message->error = $file->message;
                        return false;
                    }
                }
            } else {
                $this->message->error = $file->message;
                return false;
            }
        } else {
            return file_get_contents($path . $fileName);
        }
    }

    protected function createCompress($fileName, File $file) {
        if ($this->request->getParam('compression') == 'zip') {
            $response = $file->createZip(array($fileName), $this->collection . '.zip', TRUE);
            if ($response) {
                return $this->collection . '.zip';
            } else {
                return false;
            }
        }
    }

    public function Export() {
        $this->db = $this->request->getParam('db');
        $this->collection = $this->request->getParam('collection');
        $record = false;
        if ($this->request->isPost()) {
            switch ($this->request->getParam('quick_or_custom')) {
                case 'quick':
                    $this->quickExport();
                    break;
                case 'custom':
                    $record = $this->customExport();
                    break;
            }
        }
        if (!empty($this->db) || !empty($this->collection)) {
            $this->application->view = 'Collection';
            $this->display('export', array('record' => $record));
        } else {
            header("Location:index.php?load=Collection/Index");
        }
    }

    public function Import() {
        $this->db = $this->request->getParam('db');
        $this->collection = $this->request->getParam('collection');
        if ($this->request->isPost()) {
            if ($_FILES['import_file']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['import_file']['tmp_name'])) { //checks that file is uploaded
                echo file_get_contents($_FILES['import_file']['tmp_name']);
            }
        }
        $this->application->view = 'Collection';
        $this->display('import');
    }

}