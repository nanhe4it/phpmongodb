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

    public function setDB() {
        $this->db = urldecode($this->request->getParam('db'));
    }

    public function setCollection() {
        $this->collection = urldecode($this->request->getParam('collection'));
    }

    public function Index() {

        $this->setDB();
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
        $this->setDB();
        $this->setCollection();
        if (empty($this->db) || empty($this->collection)) {
            header("Location:index.php?load=Database/Index");
        }
        $this->application->view = 'Collection';
        $this->display('insert', array());
    }

    public function Indexes() {
        $this->setDB();
        $this->setCollection();
        if (empty($this->db) || empty($this->collection)) {
            header("Location:index.php?load=Database/Index");
        }
        $this->application->view = 'Collection';
        $data = $this->getModel()->getIndexInfo($this->db, $this->collection);
        $this->display('indexes', $data);
    }

    public function DeleteIndexes() {
        $this->setDB();
        $this->setCollection();
        $name = trim($this->request->getParam('name'));
        if (empty($this->db) || empty($this->collection) || empty($name)) {
            header("Location:index.php?load=Database/Index");
        }
        $model = $this->getModel();
        $indexes = $model->getIndexInfo($this->db, $this->collection);
        foreach ($indexes as $index) {
            if ($index['name'] === $name) {
                $response = $model->deleteIndex($this->db, $this->collection, $index['key']);
                $this->message->sucess = I18n::t('I_D');
                break;
            }
        }

        $url = Theme::URL('Collection/Indexes', array('db' => $this->db, 'collection' => $this->collection));
        header("Location:$url");
    }

    public function CreateIndexes() {
        $this->setDB();
        $this->setCollection();
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
        $this->setDB();
        $this->setCollection();
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
        $this->setDB();
        $this->setCollection();
        $id = $this->request->getParam('id');
        $idType = $this->request->getParam('id_type');
        $format = $this->request->getParam('format');
        $cryptography = new Cryptography();
        $model = $this->getModel();
        if ($this->request->isPost()) {

            if ($this->request->getParam('format') == 'array') {
                $data = $cryptography->stringToArray($this->request->getParam('data'));
                $response = $model->updateById($this->db, $this->collection, $id, $data, 'array', $idType);
            } else if ($this->request->getParam('format') == 'json') {
                $response = $model->updateById($this->db, $this->collection, $id, $this->request->getParam('data'), 'json', $idType);
            }
            if (isset($response) && $response['ok'] == 1) {
                $this->message->sucess = I18n::t('U_S');
            }
        }

        if (!empty($this->db) && !empty($this->collection) && !empty($id) && !empty($idType)) {
            $cursor = $model->findById($this->db, $this->collection, $id, $idType);
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
        $this->setDB();
        $this->setCollection();
        $id = $this->request->getParam('id');
        $idType = $this->request->getParam('id_type');
        if (!empty($this->db) && !empty($this->collection) && !empty($id)) {
            $response = $this->getModel()->removeById($this->db, $this->collection, $id, $idType);
            if ($response['n'] == 1 && $response['ok'] == 1) {
                $this->message->sucess = I18n::t('R_S_D');
            }
            $this->url = "index.php?load=Collection/Record&db=" . $this->db . "&collection=" . $this->collection;
        } else {
            $this->url = "index.php";
        }
        header("Location:" . $this->url);
    }

    public function SaveRecord() {
        $this->setDB();
        $this->setCollection();
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
                    $response = $this->getModel()->insert($this->db, $this->collection, $this->request->getParam('data'), 'json');
                    if ($response['ok'] == 1) {
                        $this->message->sucess = I18n::t('R_I');
                    } else {
                        $this->message->error = I18n::t('I_J');
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
            $this->message->sucess = count($a) . I18n::t('R_I');
            $this->getModel()->insert($this->db, $this->collection, $a);
        } else {
            $this->message->error = I18n::t('E_F_N_A_V');
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
            $this->message->error = I18n::t('I_D_N');
            $this->setURL('db');
            return false;
        }
        return true;
    }

    private function isValidCollection($collection = NULL) {
        if (empty($collection) || !isset($collection)) {
            $this->message->error = I18n::t('E_C_N');
            $this->setURL('collection');
            return false;
        } else if (!$this->isValidName($collection)) {
            $this->message->error = I18n::t('Y_C_N_U_C_F_C_N');
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

    public function CreateCollection() {
        $this->setDB();
        if (!empty($this->db)) {
            $this->setCollection();
            $capped = $this->request->getPost('capped');
            $capped = !empty($capped) ? TRUE : FALSE;
            $size = $this->request->getPost('size');
            $size = !empty($size) ? $size : 0;
            $max = $this->request->getPost('max');
            $max = !empty($max) ? $max : 0;
            if (!empty($this->collection)) {
                $this->getModel()->createCollection($this->db, $this->collection, $capped, $size, $max);
                $this->message->sucess = I18n::t('C_C', $this->collection);
            }else{
                $this->message->error = I18n::t('E_C_N');
            }
            $this->url = "index.php?load=Collection/Index&db=" . $this->db;
        }
        header("Location:" . $this->url);
    }

    public function RenameCollection() {
        $this->setDB();
        $this->setCollection();
        $oldCollection = urldecode($this->request->getParam('old_collection'));
        if ($this->validation($this->db, $this->collection)) {
            if ($this->isValidCollection($oldCollection)) {
                $response = $this->getModel()->renameCollection($this->collection, $oldCollection, $this->db);
                if ($response['ok'] == '1') {
                    $this->message->sucess = I18n::t('C_R_S');
                } else {
                    $this->message->error = $response['errmsg'];
                }
                $this->url = "index.php?load=Collection/Index&db=" . $this->db;
            }
        }
        header("Location:" . $this->url);
    }

    public function DropCollection() {
        $this->setDB();
        $this->setCollection();
        if ($this->validation($this->db, $this->collection)) {
            $response = $this->getModel()->dropCollection($this->db, $this->collection);
            if ($response['ok'] == '1') {
                $this->message->sucess = I18n::t('C_D', $this->collection);
            } else {
                $this->message->error = $response['errmsg'];
            }
            $this->url = "index.php?load=Collection/Index&db=" . $this->db;
        }
        header("Location:" . $this->url);
    }

    public function Remove() {
        $this->setDB();
        $this->setCollection();
        if ($this->validation($this->db, $this->collection)) {
            $response = $this->getModel()->removeCollection($this->db, $this->collection);
            $this->message->sucess = I18n::t('C_R', $this->collection);
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
        $this->setDB();
        $this->setCollection();
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
        $this->setDB();
        $this->setCollection();
        if ($this->request->isPost()) {
            if ($_FILES['import_file']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['import_file']['tmp_name'])) { //checks that file is uploaded
                $handle = @fopen($_FILES['import_file']['tmp_name'], "r");
                if ($handle) {
                    while (($record = fgets($handle)) !== false) {
                        $response = $this->getModel()->insert($this->db, $this->collection, $record, 'json');
                        if ($response['ok'] == 1) {
                            $this->message->sucess = I18n::t('A_D_I_S');
                        } else {
                            $this->message->error = $response['errmsg'];
                        }
                    }
                    if (!feof($handle)) {
                        $this->message->error = I18n::t('E_U_F');
                    }
                    fclose($handle);
                }
            }
        }
        $this->application->view = 'Collection';
        $this->display('import');
    }
    public function Search(){
        $this->setDB();
        $this->setCollection();
        $this->display('search');
    }
}