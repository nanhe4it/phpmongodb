<?php
/*
 * @author : Nanhe Kumar
 * and open the template in the editor.
 */
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors',1);
define('PMDDA',TRUE);
require(dirname(__FILE__).'/system/Engine.php');
$engine=new Engine();
$engine->start();
$engine->stop();
?>
