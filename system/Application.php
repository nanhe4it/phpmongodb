<?php
class Application extends Data{
    
    
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
    
   
}