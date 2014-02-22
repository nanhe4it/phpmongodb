<?php

/**
 * @author Nanhe Kumar <nanhe.kumar@fabfurnish.com>
 * @version 1.0.0
 * @package PHPMongoDB
 */

class Config {

    public static $theme = 'default';
    public static $language = array(
        'english' => 'English',
        'german' => 'German',
    );
    public static $authentication = array(
        'user' => 'admin',
        'password' => 'admin'
    );
    public static $authorization = array(
        'readonly'=>false,
    );

    /**
     *
     * @var array
     * @link http://in2.php.net/manual/en/mongoclient.construct.php (for more detail)
     */
    public static $connection = array(
        'server' => "", //mongodb://localhost:27017
        'options' => array(
            'replicaSet' => false,
        ), //
    );

}

?>
