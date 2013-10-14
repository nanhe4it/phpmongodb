<?php defined('PMDDA') or die('Restricted access'); ?>
<?php

class DatabaseController extends Controller {

    protected $model = FALSE;

    public function getModel() {
        if (!($this->model instanceof Database)) {
            return $this->model = new Database();
        } else {
            return $this->model;
        }
    }

    public function Index() {
        $dbList = $this->getModel()->listDatabases();
        $data = array(
            'dbList' => $dbList
        );

        $this->application->view = 'Database';
        $this->display('index', $data);
    }

    public function Create() {
        $this->application->view = 'Database';
        $this->display('create');
    }

    public function Update() {
        $db=$this->request->getParam('db');
        $oldDb=$this->request->getParam('db');
        if (!empty($db) || !empty($oldDb)) {
            $this->getModel()->renamdDatabase($oldDb, $db);
            $this->message->sucess = "database rename successfully";
        } else {
            $this->message->error = "Invalid database name";
        }
        header("Location:index.php?load=Database/Index");
    }

    public function Save() {
        $db=$this->request->getParam('db');
        if (!empty($_POST['db'])) {
            $this->getModel()->createDB($db);
            $this->message->sucess = $db . " database created.";
        } else {
            $this->message->error = "Enter Database Name";
        }
        header("Location:index.php?load=Database/Index");
    }

    public function Drop() {
        $db = $this->request->getParam('db');
        if (!empty($db)) {
            $response = $this->getModel()->dropDatabase($db);

            $this->message->sucess = $db . " database droped.";
        }
        header("Location:index.php?load=Database/Index");
    }

}