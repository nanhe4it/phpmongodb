<?php
/*
 * @author : Nanhe Kumar
 * and open the template in the editor.
 */
error_reporting(E_ALL);
ini_set('display_errors',1);
require(dirname(__FILE__).'/system/Engine.php');
$engine=new Engine();
$engine->start();
$engine->callTheme();
?>
