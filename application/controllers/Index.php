<?php
defined('PMDDA') or die('Restricted access');
class IndexController extends Controller{
    public function Index(){
        $data=array(
            'phpversion'=>phpversion(),
            'webserver'=>$_SERVER['SERVER_SOFTWARE'],
            'mongoinfo'=>  $this->getModel()->getMongoInfo(),
        );
       
        $this->display('index',$data);
    }
    public function SetLanguage(){
        echo $this->request->getParam('language');
        die;
    }
    
}