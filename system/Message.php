<?php

class Message {

    private $variable = 'pmdMessage';

    public function __get($name) {
        $data = isset($_SESSION[$this->variable]) ? $_SESSION[$this->variable] : array();

        if (array_key_exists($name, $data)) {
            $this->__unset($name);
            return $data[$name];
        }
    }

    public function __set($name, $value) {
        $data = isset($_SESSION[$this->variable]) ? $_SESSION[$this->variable] : array();
        $data[$name] = $value;
        $_SESSION[$this->variable] = $data;
    }

    public function __isset($name) {
        $data = isset($_SESSION[$this->variable]) ? $_SESSION[$this->variable] : array();
        return isset($data[$name]);
    }

    public function __unset($name) {
        $data = isset($_SESSION[$this->variable]) ? $_SESSION[$this->variable] : array();
        unset($data[$name]);
        $_SESSION[$this->variable] = $data;
    }

}

?>
