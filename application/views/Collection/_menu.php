<div class="header">
    <h1 class="page-title"><?php echo $this->db; ?>(<?php echo $this->collection; ?>) </h1>
</div>
<div class="btn-toolbar">
    <a class="btn <?php echo $this->application->layout==='record'?'active':'';?>" href="<?php echo Theme::URL('Collection/Record',array('db'=>$this->db,'collection'=>$this->collection)); ?>">Browse</a>
    <?php if($this->application->layout==='record'){ ?>
    <button class="btn " id="btn-insert">Insert</button>
    <?php }else{ ?>
    <a class="btn <?php echo $this->application->layout==='insert'?'active':'';?>" href="<?php echo Theme::URL('Collection/Insert',array('db'=>$this->db,'collection'=>$this->collection)); ?>">Insert</a>
    <?php }?>
    <button class="btn" id="btn-export">Export</button>
    <button class="btn" id="btn-import">Import</button>
    <a class="btn <?php echo $this->application->layout==='indexes'?'active':'';?>" href="<?php echo Theme::URL('Collection/Indexes',array('db'=>$this->db,'collection'=>$this->collection)); ?>">Indexes</a>

</div>
