<?php

class Controller {

    protected $data = array();
    protected $message;
    protected $application;

    public function setProperties($application, $message) {
        $this->application = $application;
        $this->message = $message;
    }

    protected function display($layout = '', $data = array()) {
        $this->application->layout = $layout;
        $this->data = $data;
        $this->callTheme();
    }

    public function callTheme() {
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

    private function callView() {
        try {
            $view = getcwd() . '/application/views/' . $this->_view . '/' . $this->application->layout . '.php';
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
        $this->callView();
    }

    public function isValidName($name) {
        return TRUE;
        //return !preg_match('/[\/\. "*<>:|?\\\\]/', $name);
    }

    public function getModel() {
        return new Model();
    }

    protected function debug($array) {
        echo "<pre>";
        print_r($array);
        echo "<pre>";
    }

    protected function getInclude() {
        $included_files = get_included_files();

        $this->debug($included_files);
    }

}

?>