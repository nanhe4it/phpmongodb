<?php require_once '_menu.php';?>
<div class="well" id="container-insert" >
    <ul class="nav nav-tabs">
        
        <li <?php if($this->data['format']==='array'){echo 'class="active"';}?>><a href="#Array" data-toggle="tab">Array</a></li>
        <li <?php if($this->data['format']==='json'){echo 'class="active"';}?>><a href="#JSON" data-toggle="tab">JSON</a></li>
    </ul>
    <div id="myTabContent" class="tab-content">
      
        <div class="tab-pane <?php echo $this->data['format']==='array'?'active in':'fade';?>" id="Array">
            <form id="tab2" method="post" action="index.php">
                _id : <input type="text" name="id" id="id_edit" value="<?php echo "\n".$this->data['id'];?>" readonly="" class="input-xlarge" />
                <textarea name="data" rows="4" class="input-xlarge" style="width:1000px;">
                    <?php echo "\n".$this->data['record']['array'];?>
                </textarea>
                <div>
                    <button class="btn btn-primary">Save</button>
                </div>
                <input type="hidden"  name="load" value="Collection/SaveRecord"/>
                <input type="hidden" name="type" value="Array" />
                <input type="hidden" name="db" value="<?php echo $this->db; ?>" />
                <input type="hidden" name="collection" value="<?php echo $this->collection; ?>" />
            </form>
        </div>
        <div class="tab-pane <?php echo $this->data['format']==='json'?'active in':'fade';?>" id="JSON">
            <form id="tab3" method="post" action="index.php">
                _id : <input type="text" name="id" id="id_edit" value="<?php echo "\n".$this->data['id'];?>" readonly="" class="input-xlarge" />
                    
                <textarea name="data" rows="4" class="input-xlarge" style="width:1000px;">
                <?php echo "\n".$this->data['record']['json'];?>
                </textarea>
                <div>
                    <button class="btn btn-primary">Save</button>
                </div>
                <input type="hidden"  name="load" value="Collection/EditRecord"/>
                <input type="hidden" name="type" value="JSON" />
                <input type="hidden" name="db" value="<?php echo $this->db; ?>" />
                <input type="hidden" name="collection" value="<?php echo $this->collection; ?>" />
            </form>
        </div>
    </div>

</div>