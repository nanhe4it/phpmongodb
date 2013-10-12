<div class="well" id="container-insert" style="display:none">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#keyValue" data-toggle="tab">Field Value</a></li>
        <li ><a href="#Array" data-toggle="tab">Array</a></li>
        <li><a href="#JSON" data-toggle="tab">JSON</a></li>
    </ul>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane active in" id="keyValue">
            <form id="tab1" method="post" action="index.php">
                <table id="tbl-fiedl-value">
                    <tr>
                        <th>Field</th>
                        <th>Value</th>
                    </tr>
                    <tr>
                        <td><input type="text" class="input-xlarge" name="fields[]"></td>
                        <td><textarea  rows="2" class="input-xlarge" name="values[]"></textarea></td>
                    </tr>
                </table>
                <div>
                    <button class="btn " id="add-field-value-row"><i class="icon-plus"></i> Add</button>
                    <button class="btn " id="remove-field-value-row" style="display: none"><i class="icon-minus"></i> Remove</button>
                    <button class="btn btn-primary">Save</button>
                </div>
                <input type="hidden"  name="load" value="Collection/SaveRecord"/>
                <input type="hidden" name="type" value="FieldValue" />
                <input type="hidden" name="db" value="<?php echo $this->db; ?>" />
                <input type="hidden" name="collection" value="<?php echo $this->collection; ?>" />
            </form>
        </div>
        <div class="tab-pane fade" id="Array">
            <form id="tab2" method="post" action="index.php">
                <textarea name="data" rows="3" class="input-xlarge" style="width:1000px;">array (
)</textarea>
                <div>
                    <button class="btn btn-primary">Save</button>
                </div>
                <input type="hidden"  name="load" value="Collection/SaveRecord"/>
                <input type="hidden" name="type" value="Array" />
                <input type="hidden" name="db" value="<?php echo $this->db; ?>" />
                <input type="hidden" name="collection" value="<?php echo $this->collection; ?>" />
            </form>
        </div>
        <div class="tab-pane fade" id="JSON">
            <form id="tab3" method="post" action="index.php">
                <textarea name="data" rows="3" class="input-xlarge" style="width:1000px;">{
  
}</textarea>
                <div>
                    <button class="btn btn-primary">Save</button>
                </div>
                <input type="hidden"  name="load" value="Collection/SaveRecord"/>
                <input type="hidden" name="type" value="JSON" />
                <input type="hidden" name="db" value="<?php echo $this->db; ?>" />
                <input type="hidden" name="collection" value="<?php echo $this->collection; ?>" />
            </form>
        </div>
    </div>

</div>