<div class="header">
    <h1 class="page-title"><?php echo $this->db; ?>(<?php echo $this->collection; ?>) </h1>
</div>

<div class="btn-toolbar">

    <button class="btn " id="btn-insert">Insert</button>
    <button class="btn" id="btn-export">Export</button>
    <button class="btn">Import</button>
    <div class="btn-group">
    </div>
</div>
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
                <textarea name="data" rows="3" class="input-xlarge">array (
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
                <textarea name="data" rows="3" class="input-xlarge">{
  
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





<div class="well">
    <?php
    $showTab=true;
    foreach ($this->_data['format'] as $format) {
        if (!isset($this->_data['record'][$format]))
            continue;
        ?>
    <?php 
        if($showTab){
            
    ?>    
        <ul class="nav nav-tabs">
            <li id="li-json"class="active"><a href="#json" data-list-record="json">JSON</a></li>
            <li id="li-array"><a href="#array" data-list-record="array">Array</a></li>
            <li id="li-document"><a href="#document" data-list-record="document">Find</a></li>
        </ul>
        <?php 
        $showTab=false;
        }
        ?>
        <div id="record-<?php echo $format; ?>" style="display: <?php echo $format === 'json' ? 'block' : 'none'; ?>">
            <?php
            foreach ($this->_data['record'][$format] as $cursor) {

                echo "<pre>";
                print_r($cursor);
                echo "</pre>";
            }
            ?>
        </div>
        <?php
    }
    ?>

</div>

<?php    Theme::pagination($this->getModel()->totalRecord($this->db,$this->collection));?>

<script>
    $(document).ready(function() {
        $("#btn-insert").click(function() {
            $('#container-insert').slideDown();
            $('#btn-insert').addClass('btn active');
        });
        $("#add-field-value-row").click(function() {
            $("#tbl-fiedl-value").append('<tr><td><input type="text" class="input-xlarge" name="fields[]"></td><td><textarea  rows="2" class="input-xlarge" name="values[]"></textarea></td></tr>');
            $('#remove-field-value-row').show();
            return false;
        });
        $("#remove-field-value-row").click(function() {
            $('#tbl-fiedl-value tr:last').remove();
            var rowCount = $('#tbl-fiedl-value tr').length;
            if (rowCount === 2) {
                $('#remove-field-value-row').hide();
            }
            return false;
        });

        $("a[data-list-record]").click(function() {
            var tab = $(this).attr("data-list-record");
            $('#record-array').hide();
            $('#record-document').hide();
            $('#record-json').hide();
            $( "#li-json" ).removeClass( "active" );
            $( "#li-array" ).removeClass( "active" );
            $( "#li-document" ).removeClass( "active" );
            if (tab === 'json') {
                $('#record-json').show();
                $( "#li-json" ).addClass( "active" );
            } else if (tab === 'array') {
                $('#record-array').show();
                $( "#li-array" ).addClass( "active" );
            } else if (tab === 'document') {
                $('#record-document').show();
                $( "#li-document" ).addClass( "active" );
            }

        });
        
        $("#btn-export").click(function() {
            var url="index.php?load=Collection/Export&db="+"<?php echo $this->db;?>"+"&collection="+"<?php echo $this->collection;?>";
            window.location=url;
            $('#btn-export').addClass('btn active');
        });

    });
    

</script>



