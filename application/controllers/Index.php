<?php
defined('PMDDA') or die('Restricted access');
class IndexController extends Controller{
    public function Index(){
        $data=array(
            'phpversion'=>phpversion(),
            'webserver'=>$_SERVER['SERVER_SOFTWARE'],
            'mongoinfo'=>  $this->getModel()->getMongoInfo(),
        );
        $this->application->view='Index';
        $this->display('index',$data);
    }
    
}