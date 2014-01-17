<?php defined('PMDDA') or die('Restricted access'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>PHPmongoDB </title>
        <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="mongoDB">
        <meta name="author" content="">

        <link rel="stylesheet" type="text/css" href="<?php echo Theme::getPath(); ?>bootstrap/css/bootstrap.css">

        <link rel="stylesheet" type="text/css" href="<?php echo Theme::getPath(); ?>css/style.css">


        <script src="<?php echo Theme::getPath(); ?>js/jquery-1.8.1.min.js" type="text/javascript"></script>

        <!-- Demo page code -->

        <style type="text/css">
            #line-chart {
                height:300px;
                width:800px;
                margin: 0px auto;
                margin-top: 1em;
            }
            .brand { font-family: georgia, serif; }
            .brand .first {
                color: #ccc;
                font-style: italic;
            }
            .brand .second {
                color: #fff;
                font-weight: bold;
            }
        </style>

        <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="<?php echo Theme::getPath(); ?>lib/html5.js"></script>
        <![endif]-->


    </head>

    <!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
    <!--[if IE 7 ]> <body class="ie ie7 "> <![endif]-->
    <!--[if IE 8 ]> <body class="ie ie8 "> <![endif]-->
    <!--[if IE 9 ]> <body class="ie ie9 "> <![endif]-->
    <!--[if (gt IE 9)|!(IE)]><!--> 
    <body class=""> 
        <!--<![endif]-->

        <div class="navbar">
            <div class="navbar-inner">
                <ul class="nav pull-right">

                    <li><a href="<?php echo Theme::URL('Database/Index'); ?>" class="hidden-phone visible-tablet visible-desktop" role="button">Databases</a></li>
                    <li id="fat-menu" class="dropdown">
                        <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-user"></i> Language
                            <i class="icon-caret-down"></i>
                        </a>

                        <!--                        <ul class="dropdown-menu">
                                                    <li><a tabindex="-1" href="#">My Account</a></li>
                                                    <li class="divider"></li>
                                                    <li><a tabindex="-1"  href="#">Settings</a></li>
                                                    <li class="divider visible-phone"></li>
                                                    <li><a tabindex="-1" href="sign-in.html">Logout</a></li>
                                                </ul>-->
                        <ul class="dropdown-menu">
                            <?php
                                $languageList = Widget::get('languageList');
                                foreach($languageList as $key=>$val){
                                    
                                
                            ?>
                            <li><a tabindex="-1" href="<?php echo Theme::URL('Index/SetLanguage',array('language'=>$key)); ?>"><?php echo $val;?></a></li>
                            <?php }?>
                        </ul>
                    </li>

                </ul>
                <a class="brand" href="<?php echo Theme::getHome(); ?>"><span class="first">PHP</span> <span class="second">mongoDB</span></a>
            </div>
        </div>
