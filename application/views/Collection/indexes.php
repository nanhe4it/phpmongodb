<?php
$cryptography = new Cryptography();
?>
<?php require_once '_menu.php'; ?>
<div class="well" id="container-indexes">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#IndexesList" data-toggle="tab">List</a></li>
        <li ><a href="#IndexesCreate" data-toggle="tab">Create</a></li>
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
                        <th>Action</th>
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
                <table id="tbl-fiedl-value">
                    <tr>
                        <td style="width:160px;">Name</td>
                        <td colspan="2"><input type="text" class="input-xlarge" name="name"></td>

                    </tr>
                    <tr>
                        <td>Fields</td>

                        <td><input type="text" class="input-xlarge" name="fields[]"></td>
                        <td>
                            <select name="orders[]" style="width:auto;">
                                <option value="1">ASC</option>
                                <option value="-1">DESC</option>
                            </select>
                        </td>
                        <td><i class="icon-plus"></i>&nbsp;<i class="icon-minus"></i></td>
                    </tr>
                    <tr>
                        <td>Unique</td>
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

                    <button class="btn btn-primary">Create</button>
                </div>
                <input type="hidden"  name="load" value="Collection/CreateIndexes"/>

                <input type="hidden" name="db" value="<?php echo $this->db; ?>" />
                <input type="hidden" name="collection" value="<?php echo $this->collection; ?>" />
            </form>
        </div>

    </div>
</div>
<script>
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