<?php
class Application{
    private $data = array();
    
    /**
     * @param void
     * @return void
     * @author Nanhe Kumar <nanhe.kumar@gmail.com>
     * @access public
     */
    public function init(){
        $chttp=new CHttp();
        $load=$chttp->getParam('load');
        if(!empty($load)){
            list($this->controller,$this->action)=  explode('/',$load);
        }
        if(!isset($this->controller)){
            $this->controller='Index';
        }
        if(!isset($this->action)){
            $this->action='Index';
        }
        $this->theme=$chttp->getParam('theme');
        $this->theme=(empty($this->theme)?TRUE:(strtolower($this->theme)=='false'?FALSE:TRUE));
        
    }
    
    /**
     * 
     * @param mixed $name
     * @return mixed
     * @author Nanhe Kumar <nanhe.kumar@gmail.com>
     * @access public
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        $trace = debug_backtrace();
        trigger_error( 'Undefined property via __get(): ' . $name . ' in ' . $trace[0]['file'] .' on line ' . $trace[0]['line'], E_USER_NOTICE);
        return null;
    }
    /**
     * 
     * @param string $name
     * @param mixed $value
     * @author Nanhe Kumar <nanhe.kumar@gmail.com>
     * @access public
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }
    /**
     * 
     * @param string $name
     * @return mixed
     * @author Nanhe Kumar <nanhe.kumar@gmail.com>
     * @access public
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }
    /**
     * 
     * @param mixed $name
     * @author Nanhe Kumar <nanhe.kumar@gmail.com>
     * @access public
     */
    public function __unset($name)
    {
        unset($this->data[$name]);
    }
}