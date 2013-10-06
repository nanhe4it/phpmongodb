<div class="header">
    <h1 class="page-title"><?php echo $this->db; ?>(<?php echo $this->collection; ?>) </h1>
</div>
<div class="btn-toolbar">

    <button class="btn " id="btn-insert">Insert</button>
    <button class="btn" id="btn-export">Export</button>
    <button class="btn" id="btn-import">Import</button>
    <!--    <button class="btn" id="btn-indexes">Indexes</button>-->
    <a class="btn <?php echo $this->application->layout==='indexes'?'active':'';?>" href="<?php echo Theme::URL('Collection/Indexes',array('db'=>$this->db,'collection'=>$this->collection)); ?>">Indexes</a>

</div>
