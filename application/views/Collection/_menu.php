<div class="header">
    <h1 class="page-title"><?php echo $this->db; ?>(<?php echo $this->collection; ?>) </h1>
</div>
<div class="btn-toolbar">
    <a class="btn <?php echo $this->application->layout==='record'?'active':'';?>" href="<?php echo Theme::URL('Collection/Record',array('db'=>$this->db,'collection'=>$this->collection)); ?>"><?php I18n::p('BROWSE');?></a>
    <?php if($this->application->layout==='record'){ ?>
    <button class="btn " id="btn-insert"><?php I18n::p('INSERT');?></button>
    <?php }else{ ?>
    <a class="btn <?php echo $this->application->layout==='insert'?'active':'';?>" href="<?php echo Theme::URL('Collection/Insert',array('db'=>$this->db,'collection'=>$this->collection)); ?>"><?php I18n::p('INSERT');?></a>
    <?php }?>
    <a class="btn <?php echo $this->application->layout==='export'?'active':'';?>" href="<?php echo Theme::URL('Collection/Export',array('db'=>$this->db,'collection'=>$this->collection)); ?>"><?php I18n::p('EXPORT');?></a>
    <a class="btn <?php echo $this->application->layout==='import'?'active':'';?>" href="<?php echo Theme::URL('Collection/Import',array('db'=>$this->db,'collection'=>$this->collection)); ?>"><?php I18n::p('IMPORT');?></a>
    <a class="btn <?php echo $this->application->layout==='indexes'?'active':'';?>" href="<?php echo Theme::URL('Collection/Indexes',array('db'=>$this->db,'collection'=>$this->collection)); ?>"><?php I18n::p('INDEXES');?></a>
</div>
