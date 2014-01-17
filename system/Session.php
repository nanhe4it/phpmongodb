<?php

class Session extends Data {

    const KEY = 'PMD_SESSION';

    public function get($key, $value = null) {
        return isset($_SESSION[KEY]) ? $_SESSION[KEY] : $value;
    }

    public function set($key, $value) {
        $_SESSION[KEY] = $value;
    }

    public function __get($name) {
        $this->data = $this->get(self::KEY);
        return parent::__get($name);
    }

    public function __set($name, $value) {
        $this->data = $this->get(self::KEY);
        parent::__set($name, $value);
        $this->set(self::KEY, $this->data);
    }

    public function start() {
        session_start();
    }

    /**
     * Ends the current session and store session data.
     */
    public function close() {
        if (session_id() !== '')
            @session_write_close();
    }

    /**
     * Frees all session variables and destroys all data registered to a session.
     */
    public function destroy() {
        if (session_id() !== '') {
            @session_unset();
            @session_destroy();
        }
    }

}

?>
