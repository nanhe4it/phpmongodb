<?php

class System {

    protected $application;
    protected $applicationPath;
    protected $controllerPath;
    protected $modelPath;

    public function run() {
        $this->__setPath();

        $this->application = new Application();
        $this->__loadModel();
        if ($this->__loadController()) {
            $this->__loadAction();
        }
    }

    private function __setPath() {
        $this->applicationPath = getcwd() . '/application/';
        $this->controllerPath = $this->applicationPath . 'controllers';
        $this->modelPath = $this->applicationPath . 'models';
    }

    private function __loadAction() {
        try {
            $controller = $this->application->controller . 'Controller';
            if (method_exists($controller, $this->application->action)) {

                call_user_func(array(new $controller, $this->application->action));
            } else {
                throw new Exception('Method  ' . $this->application->action . ' doe not exitst');
            }
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
            return false;
        }
    }

    private function __loadController() {
        try {
            $controller = $this->controllerPath . '/' . $this->application->controller . '.php';
            if (!file_exists($controller)) {
                throw new Exception('System cannot find the Controller file ' . $controller);
            } else {
                require_once ($controller);
            }
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
            return false;
        }
        return true;
    }

    private function __loadModel() {

        $model = $this->modelPath . '/' . $this->application->controller . '.php';
        if (file_exists($model)) {
            require_once ($model);
            return TRUE;
        }
        return FALSE;
    }

    

}

?>