<?php
class Application{
    private $data = array();
    
    public function __construct() {
        
        
    }
    public function init(){
        $this->request=new CHttp();
        $load=$this->request->getParam('load');
        if(!empty($_REQUEST['load'])){
            list($this->controller,$this->action)=  explode('/',$load);
        }
        if(!isset($this->controller)){
            $this->controller='Index';
        }
        if(!isset($this->action)){
            $this->action='Index';
        }
        $this->theme=$this->request->getParam('theme');
        $this->theme=(empty($this->theme)?TRUE:(strtolower($this->theme)=='false'?FALSE:TRUE));
        
    }
    

    public function __get($name)
    {
     
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        $trace = debug_backtrace();
        trigger_error( 'Undefined property via __get(): ' . $name . ' in ' . $trace[0]['file'] .' on line ' . $trace[0]['line'], E_USER_NOTICE);
        return null;
    }
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    
    public function __isset($name)
    {
       
        return isset($this->data[$name]);
    }

    
    public function __unset($name)
    {
        
        unset($this->data[$name]);
    }
}