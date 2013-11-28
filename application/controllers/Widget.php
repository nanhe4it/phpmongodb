<?php
defined('PMDDA') or die('Restricted access');
class WidgetController extends Controller {
    

    public function getDBList(){
        $model=new Model();      
        $dbList= $model->listDatabases();
       foreach ($dbList['databases'] as $k=>$db) {
           $dbList['databases'][$k]['noOfCollecton'] =count($model->listCollections($db['name'], TRUE));
       }
       return $dbList;
    }
     public function getCollectonList() {
        $chttp=new Chttp();
        $db = $chttp->getParam('db');
        if (!empty($db)) {
            $model = new Model(); 
            $collections = $model->listCollections($db, TRUE);
           
            $collectionList = array();
            foreach ($collections as $collection) {
                $collectionList[] = array('name' => $collection->getName(), 'count' => $collection->count());
            }
            return $collectionList;
        } else {
            return FALSE;
        }
    }
}
?>
