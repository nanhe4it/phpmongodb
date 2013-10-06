<?php defined('PMDDA') or die('Restricted access'); ?>
<div class="sidebar-nav">
    <a href="#dashboard-menu" class="nav-header" data-toggle="collapse"><i class="icon-dashboard"></i>Databases</a>
    <ul  class="nav nav-list collapse in">
        <?php
               
        $dbList = $this->getModel()->listDatabases();
        foreach ($dbList['databases'] as $db) {
            ?>
        <li ><a href="<?php echo Theme::URL('Collection/Index', array('db' => $db['name'])); ?>"><?php echo $db['name']; ?></a></li>
        <?php } ?>


    </ul>
</div>