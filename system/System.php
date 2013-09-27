<?php

class System {

    protected $application;

    public function start() {
        $this->application = new Application();
        $this->application();
    }

    protected function getObject($class) {
        return new $class;
    }
    protected function getProperties(){
        return array(
            'message'=>new Message,
            'application'=>$this->application,
            
        );
    }

    protected function application() {

        try {
            $controller = $this->getObject($this->application->controller . 'Controller');
            
            if (method_exists($controller, 'setProperties')) {

                call_user_func_array(array($controller,'setProperties'),$this->getProperties());
                
            }
            if (method_exists($controller, $this->application->action)) {

                call_user_func(array($controller, $this->application->action));
            } else {
                throw new Exception('Method  ' . $this->application->action . ' doe not exitst');
            }
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
            return false;
        }
    }

}

?>