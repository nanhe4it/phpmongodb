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

        $this->_view = 'Database';
        $this->display('index', $data);
    }

    public function Create() {
        $this->_view = 'Database';
        $this->display('create');
    }
    public function Update(){
        if (!empty($_POST['db']) || !empty($_POST['old_db']) || $this->isValidName($_POST['db']) || $this->isValidName($_POST['old_db'])) {
            $this->getModel()->renamdDatabase($_POST['old_db'],$_POST['db']);
            $this->message->sucess = "database rename successfully";
        }else{
             $this->message->error="Invalid database name";
        }
         header("Location:index.php?load=Database/Index");
    }

    public function Save() {

        if (!empty($_POST['db'])) {
            if (!$this->isValidName($_POST['db'])) {
                $this->getModel()->createDB($_POST['db']);
                $this->message->sucess = $_POST['db'] . " database created.";
            } else {
                $this->message->error = 'You can not use characters /\. "*<>:|? for databse name';
            }
        } else {
            $this->message->error = "Enter Database Name";
        }



        header("Location:index.php?load=Database/Index");
    }

    public function Drop() {

        if (!empty($_POST['db']) && $this->isValidName($_POST['db'])) {
            $res = $this->getModel()->dropDatabase($_POST['db']);

            $this->message->sucess = $_POST['db'] . " database droped.";
        }
        header("Location:index.php?load=Database/Index");
    }

    

}