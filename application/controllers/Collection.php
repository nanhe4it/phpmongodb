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
              
            );
            //$this->debug($data);
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
            $format = array('json', 'array', 'document');
            $this->display('record', array('record' => $record, 'format' => $format));
        } else {

            header("Location:" . $this->url);
        }
    }

    public function EditRecord() {
        $this->db = $this->request->getParam('db');
        $this->collection = $this->request->getParam('collection');
        $id = $this->request->getParam('id');
        $idType=$this->request->getParam('id_type');
        $format = $this->request->getParam('format');
        $cryptography = new Cryptography();
        $model = $this->getModel();
        if ($this->request->isPost()) {
            
            if ($this->request->getParam('format') == 'array') {
                $data = $cryptography->stringToArray($this->request->getParam('data'));
                $response = $model->updateById($this->db, $this->collection, $id, $data,'array',$idType);
            } else if ($this->request->getParam('format') == 'json') {
                $response = $model->updateById($this->db, $this->collection, $id, $this->request->getParam('data'), 'json',$idType);
            }
            if (isset($response) && $response['ok'] == 1) {
                $this->message->sucess = "Updated successfully.";
            }
        }
        
        if (!empty($this->db) && !empty($this->collection) && !empty($id) && !empty($idType)) {
            $cursor = $model->findById($this->db, $this->collection, $id,$idType);
            unset($cursor['_id']);

            $record['json'] = $cryptography->arrayToJSON($cursor);
            $record['array'] = $cryptography->arrayToString($cursor);
            $this->application->view = 'Collection';
            $this->display('edit', array('record' => $record, 'format' => $format, 'id' => $id));
        } else {
            $this->url = "index.php";
            header("Location:" . $this->url);
        }
    }

    public function DeleteRecord() {
        $this->db = $this->request->getParam('db');
        $this->collection = $this->request->getParam('collection');
        $id = $this->request->getParam('id');
        $idType=$this->request->getParam('id_type');
        if (!empty($this->db) && !empty($this->collection) && !empty($id)) {
            $response = $this->getModel()->removeById($this->db, $this->collection, $id,$idType);
            if ($response['n'] == 1 && $response['ok'] == 1) {
                $this->message->sucess = "Record successfully deleted";
            }
            $this->url = "index.php?load=Collection/Record&db=" . $this->db . "&collection=" . $this->collection;
        } else {
            $this->url = "index.php";
        }
        header("Location:" . $this->url);
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
                    $response = $this->getModel()->insert($this->db, $this->collection, $this->request->getParam('data'),'json');
                    if ($response['ok'] == 1) {
                        $this->message->sucess = " row inserted";
                    } else {
                        $this->message->error = 'invalid json';
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
            if ($this->isValidCollection($this->request->getParam('old_collection'))) {
                $response = $this->getModel()->renameCollection($this->collection,$this->request->getParam('old_collection'), $this->db);

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
            $json = $cryptography->arrayToJSON($document);
            $json = str_replace(array("\n", "\t"), '', $json);
            $file->write($json . "\n");
        }
        if ($file->success) {

            $file->download();
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
                //echo $fileContent = file_get_contents($_FILES['import_file']['tmp_name']);
                $handle = @fopen($_FILES['import_file']['tmp_name'], "r");
                if ($handle) {
                    while (($record = fgets($handle)) !== false) {
                        $response = $this->getModel()->insert($this->db, $this->collection, $record,'json');
                        if ($response['ok'] == 1) {
                            $this->message->sucess = "All data import successfully.";
                        } else {
                            $this->message->error = $response['errmsg'];
                        }
                    }
                    if (!feof($handle)) {
                        $this->message->error = "Error: unexpected fgets() fail\n";
                    }
                    fclose($handle);
                }
            }
        }
        $this->application->view = 'Collection';
        $this->display('import');
    }

}