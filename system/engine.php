<?php
session_start();
//echo $_SERVER['QUERY_STRING'];
class Engine {

    public $system;
    public $files = array(
        'Application',
        'System',
        'Cryptography',
        'Message',
        'PHPMongoDB',
        'Model',
        'Controller',
        'Theme',
    );
    
    public function start() {
        $this->includes($this->files);
        $this->system = new System();
        $this->system->run();
    }
    public function includes($files) {
        foreach ($files as $file) {
            $fileWithPath = dirname(__FILE__) . '/' . $file.'.php';
            try {

                if (!file_exists($fileWithPath)) {
                    throw new Exception('System cannot find  file ' . $fileWithPath);
                } else {
                    require_once ($fileWithPath);
                }
            } catch (Exception $e) {
                echo 'Caught exception: ', $e->getMessage(), "\n";
                return false;
            }
        }
        return TRUE;
    }

}

?>