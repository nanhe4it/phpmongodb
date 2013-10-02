<?php

class CHttp {

    /**

     * @return string request type, such as GET, POST, HEAD.
     * @author Nanhe Kumar <nanhe.kumar@gmail.com>
     */
    public function getType() {
        return strtoupper(isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET');
    }

    /**
     * Returns whether this is a POST request.
     * @return boolean whether this is a POST request.
     * @author Nanhe Kumar <nanhe.kumar@gmail.com>
     */
    public function isPost() {
        return isset($_SERVER['REQUEST_METHOD']) && !strcasecmp($_SERVER['REQUEST_METHOD'], 'POST');
    }

    /**
     * Returns whether this is an AJAX (XMLHttpRequest) request.
     * @return boolean whether this is an AJAX (XMLHttpRequest) request.
     * @author Nanhe Kumar <nanhe.kumar@gmail.com>
     */
    public function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    /**
     * Returns the named GET or POST parameter value.
     * If the GET or POST parameter does not exist, the second parameter to this method will be returned.
     * If both GET and POST contains such a named parameter, the GET parameter takes precedence.
     * @param string $name the GET parameter name
     * @param mixed $value the default parameter value if the GET parameter does not exist.
     * @return mixed the GET parameter value

     */
    public function getParam($name, $value = null) {
        return isset($_GET[$name]) ? $_GET[$name] : (isset($_POST[$name]) ? $_POST[$name] : $value);
    }

    /**
     * Returns the named GET parameter value.
     * If the GET parameter does not exist, the second parameter to this method will be returned.
     * @param string $name the GET parameter name
     * @param mixed $value the default parameter value if the GET parameter does not exist.
     * @return mixed the GET parameter value
    
     */
    public function getQuery($name, $value = null) {
        return isset($_GET[$name]) ? $_GET[$name] : $value;
    }

    /**
     * Returns the named POST parameter value.
     * If the POST parameter does not exist, the second parameter to this method will be returned.
     * @param string $name the POST parameter name
     * @param mixed $value the default parameter value if the POST parameter does not exist.
     * @return mixed the POST parameter value
     
     */
    public function getPost($name, $value = null) {
        return isset($_POST[$name]) ? $_POST[$name] : $value;
    }

}

?>
