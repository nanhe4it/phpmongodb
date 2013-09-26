<?php

class Controller {

    protected $_view;
    protected $__layout;
    protected $_data;
    public $message;

    public function __construct() {
        
        $this->message = new Message();
    }

    protected function display($layout = '', $data = array()) {
        $this->_layout = $layout;
        $this->_data = $data;
        $this->__callTheme();
    }

    public function __callTheme() {
        try {
            $theme = getcwd() . '/application/themes/default/index.php';
            if (!file_exists($theme)) {
                throw new Exception('Controller cannot find the Theme file ' . $theme);
            } else {
                require_once $theme;
            }
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

    private function __callView() {
        try {
            $view = getcwd() . '/application/views/' . $this->_view . '/' . $this->_layout . '.php';
            if (!file_exists($view)) {
                throw new Exception('Controller cannot find the view file ' . $view);
            } else {
                require_once ($view);
            }
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

    protected function view() {
        $this->__callView();
    }
    public function isValidName($name) {
        return !preg_match('/[\/\. "*<>:|?\\\\]/', $name);
    }
    public function getModel() {
     return new Model();
    }
    protected function debug($array) {
        echo "<pre>";
        print_r($array);
        echo "<pre>";
    }
    
    protected function getInclude(){
        $included_files = get_included_files();

        $this->debug($included_files);
    }
    

}

?>