<?php
$cryptography = new Cryptography();
?>
<?php require_once '_menu.php'; ?>
<div class="well" id="container-indexes">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#IndexesList" data-toggle="tab"><?php I18n::p('LIST');?></a></li>
        <li ><a href="#IndexesCreate" data-toggle="tab"><?php I18n::p('CREATE');?></a></li>
    </ul>

    <div id="myTabContent" class="tab-content">
        <div class="tab-pane active in" id="IndexesList">
            <table class="table">
                <thead>
                    <tr>
                        <th>v</th>
                        <th>key</th>
                        <th>name</th>
                        <th>unique</th>
                        <th>ns</th>
                        <th>background</th>
                        <th><?php I18n::p('ACTION');?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->data as $index) { ?>
                        <tr>
                            <td><?php echo $index['v']; ?></td>
                            <td><?php echo $cryptography->highlight($cryptography->arrayToJSON($index['key'])); ?></td>
                            <td><?php echo $index['name']; ?></td>
                            <td><?php echo (isset($index['unique']) ? ($index['unique'] ? 'true' : 'false') : ''); ?></td>
                            <td><?php echo $index['ns']; ?></td>
                            <td><?php echo isset($index['background']) ? (is_double($index['background']) ? 'NumberLong(' . $index['background'] . ')' : $index['background']) : ''; ?>
                            <td><?php echo $index['name'] !== '_id_' ? '<a href="' . Theme::URL('Collection/DeleteIndexes', array('db' => $this->db, 'collection' => $this->collection, 'name' => $index['name'])) . '">Delete</a>' : ''; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>            
        <div class="tab-pane fade" id="IndexesCreate">
            <form id="tab1" method="post" action="index.php">
                <table id="tbl-create-indexes">
                    <tr>
                        <td style="width:160px;"><?php I18n::p('NAME');?></td>
                        <td colspan="2"><input type="text" class="input-xlarge" name="name" required="required"></td>
                    </tr>
                    <tr>
                        <td><?php I18n::p('FIELDS');?></td>
                        <td><input type="text" class="input-xlarge" name="fields[]" required="required"></td>
                        <td>
                            <select name="orders[]" style="width:auto;">
                                <option value="1">ASC</option>
                                <option value="-1">DESC</option>
                            </select>
                        </td>
                        <td>&nbsp;<a href="javascript:void(0)" onclick="appendTR();" class="btn-primary"><i class="icon-plus"></i></a>&nbsp;</td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td style="width:160px;"><?php I18n::p('UNIQUE');?></td>
                        <td colspan="2"><input type="checkbox"  value="1" name="unique" id="index_unique"></td>
                    </tr>
                    <tr id="drop_duplicates" style="display: none">
                        <td>Drop duplicates?</td>
                        <td colspan="2"><input type="checkbox"  value="1" name="drop"></td>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                </table>
                <div>
                    <button class="btn btn-primary"><?php I18n::p('CREATE');?></button>
                </div>
                <input type="hidden"  name="load" value="Collection/CreateIndexes"/>
                <input type="hidden" name="db" value="<?php echo $this->db; ?>" />
                <input type="hidden" name="collection" value="<?php echo $this->collection; ?>" />
            </form>
        </div>

    </div>
</div>
<script>
    function appendTR() {
        var trID='tr-indexes'+$('#tbl-create-indexes tr').length;
        var tr = '<tr id="'+trID+'">';
        tr = tr + '<td>&nbsp;</td>';
        tr = tr + '<td><input type="text" class="input-xlarge" name="fields[]"></td>';
        tr = tr + '<td>';
        tr = tr + '<select name="orders[]" style="width:auto;">';
        tr = tr + '<option value="1">ASC</option>';
        tr = tr + '<option value="-1">DESC</option>';
        tr = tr + '</select>';
        tr = tr + '</td>';
        tr = tr + '<td>';
        tr = tr + '&nbsp;<a href="javascript:void(0)" onclick="appendTR();" class="btn-primary"><i class="icon-plus"></i></a>&nbsp;';
        tr = tr + "<a href=\"javascript:void(0)\" onclick=\"removeTR('"+trID+"');\" class=\"btn-primary\"><i class=\"icon-minus\"></i></a>";
        tr = tr + '</td>';
        tr = tr + '</tr>';
        $("#tbl-create-indexes").append(tr);
        return false;
    }
    function removeTR(trID){

        $("table#tbl-create-indexes tr#"+trID).remove();
         return false;
    }
    $(document).ready(function() {
        $("#index_unique").click(function() {
            var checked = $(this).is(':checked');
            if (checked) {
                $('#drop_duplicates').show();
            } else {
                $('#drop_duplicates').hide();
            }
        });
    });

</script>   

