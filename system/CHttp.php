<?php

class CHttp {

    private $_hostInfo;

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

    

    /**
     * Return if the request is sent via secure channel (https).
     * @return boolean if the request is sent via secure channel (https)
     */
    public function isSecureConnection() {
        return isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https';
    }

    public function getHost($schema = '') {
        if ($this->_hostInfo === null) {
            if ($secure = $this->isSecureConnection())
                $http = 'https';
            else
                $http = 'http';
            if (isset($_SERVER['HTTP_HOST']))
                $this->_hostInfo = $http . '://' . $_SERVER['HTTP_HOST'];
            else {
                $this->_hostInfo = $http . '://' . $_SERVER['SERVER_NAME'];
                $port = $secure ? $this->getSecurePort() : $this->getPort();
                if (($port !== 80 && !$secure) || ($port !== 443 && $secure))
                    $this->_hostInfo.=':' . $port;
            }
        }
        if ($schema !== '') {
            $secure = $this->getIsSecureConnection();
            if ($secure && $schema === 'https' || !$secure && $schema === 'http')
                return $this->_hostInfo;

            $port = $schema === 'https' ? $this->getSecurePort() : $this->getPort();
            if ($port !== 80 && $schema === 'http' || $port !== 443 && $schema === 'https')
                $port = ':' . $port;
            else
                $port = '';

            $pos = strpos($this->_hostInfo, ':');
            return $schema . substr($this->_hostInfo, $pos, strcspn($this->_hostInfo, ':', $pos + 1) + 1) . $port;
        }
        else
            return $this->_hostInfo;
    }
        /**
	 * Redirects the browser to the specified URL.
	 * @param string $url URL to be redirected to. Note that when URL is not
	 * absolute (not starting with "/") it will be relative to current request URL.
	 * @param boolean $terminate whether to terminate the current application
	 * @param integer $statusCode the HTTP status code. Defaults to 302. See {@link http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html}
	 * for details about HTTP status code.
	 */
	public function redirect($url,$terminate=true,$statusCode=302)
	{
		if(strpos($url,'/')===0 && strpos($url,'//')!==0)
			$url=$this->getHost().$url;
		header('Location: '.$url, true, $statusCode);
		if($terminate)
                    exit ();
	}

}

?>
