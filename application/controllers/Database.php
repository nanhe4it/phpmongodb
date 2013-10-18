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

        
        $this->display('index', $data);
    }

    public function Create() {
        
        $this->display('create');
    }

    public function Update() {
        $db=$this->request->getParam('db');
        $oldDb=$this->request->getParam('db');
        if (!empty($db) || !empty($oldDb)) {
            $this->getModel()->renamdDatabase($oldDb, $db);
            $this->message->sucess =  I18n::t('D_R_S');
        } else {
            $this->message->error = I18n::t('I_D_N'); 
        }
        header("Location:index.php?load=Database/Index");
    }

    public function Save() {
        $db=$this->request->getParam('db');
        if (!empty($db)) {
            $this->getModel()->createDB($db);
            $this->message->sucess =I18n::t('D_C',$db);
        } else {
            $this->message->error =I18n::t('E_D_N');
        }
        header("Location:index.php?load=Database/Index");
    }

    public function Drop() {
        $db = $this->request->getParam('db');
        if (!empty($db)) {
            $response = $this->getModel()->dropDatabase($db);

            $this->message->sucess =I18n::t('D_D',$db);
        }
        header("Location:index.php?load=Database/Index");
    }
    public function ListDB(){
        $model=new Model();      
        $dbList = $model->listDatabases();
        $this->display('list', $dbList);
    }

}