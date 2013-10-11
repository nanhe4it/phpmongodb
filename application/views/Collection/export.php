<?php require_once '_menu.php'; ?>
<form method="post" action="index.php">
    <div class="row-fluid">
        <div class="block " id="block_export_method">
            <a href="javascript:void(0)" class="block-heading" data-toggle="collapse">Export Method:</a>
            <div class="block-body">
                <input type="radio" id="quick_export" value="quick" name="quick_or_custom" checked="checked">&nbsp;Quick - display only the minimal options<br>
                <input type="radio" id="custom_export" value="custom" name="quick_or_custom">&nbsp;Custom - display all possible options
            </div>
        </div>
        <div class="block " style="display:none;" id="block_export_rows">
            <a href="javascript:void(0)" class="block-heading" data-toggle="collapse">Rows:</a>
            <div class="block-body">
                <input type="radio" checked="checked" id="dump_all_export" value="all" name="all_or_some">&nbsp;Dump all rows<br>
                <input type="radio" id="dump_some_export" value="custom" name="all_or_some">&nbsp;Dump some row(s)
                <table id="dump_some_row_export" style="display:none;">
                    <tr>
                        <td>Number of rows:</td>
                        <td><input type="text"  value="5" size="5" name="limit_to" id="limit_to_export"></td>
                    </tr>
                    <tr>
                        <td>Row to begin at:</td>
                        <td><input type="text"  value="0" size="5" name="limit_to" id="limit_from_export"></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="block" id="block_export_output" style="display:none;">
            <a href="javascript:void(0)" class="block-heading" data-toggle="collapse">Output:</a>
            <div class="block-body">

                <input type="radio" checked="checked" id="text_export" value="custom" name="text_or_save">&nbsp;View output as text(s)<br>
                <input type="radio"  id="save_export" value="quick" name="text_or_save">&nbsp;Save output to a file<br>
                <table id="save_output_to_a_file" style="display: none;">
                    <tr>
                        <td>File name : </td>
                        <td><input type="text"  value=""  name="file_name" id="file_name_export"></td>
                    </tr>
                    <tr><td>Compressed : </td>
                        <td><select name="compression" id="compression_export">
                                <option value="none">None</option>
                                <option value="zip">zipped</option>
                                <option value="gzip">gzipped</option>
                                <option value="bzip2">bzipped</option>
                            </select></td></tr>

                </table>
            </div>
        </div>
        <div class="block " id="block_export_data_dump_options" style="display:none;">
            <a href="#tablewidget" class="block-heading" data-toggle="collapse">Data dump options:</a>
            <div class="block-body">
                <input type="radio" checked="checked" id="json_export" value="quick" name="json_or_mongo">&nbsp;Only JSON<br>
                <input type="radio" id="mongo_export" value="custom" name="json_or_mongo">&nbsp;Mongo Statement
            </div>
        </div>
        <input type="hidden" name="db" id="db-export" value="<?php echo $this->db;?>" />
        <input type="hidden" name="collection" id="collection_export" value="<?php echo $this->collection;?>" />
        <input type="hidden" name="load" value="Collection/Export" />
        <input class="btn btn-primary btn-large" type="submit" name="btnExport" Value="Export" />
    </div>
</form>    
<script>
    $(document).ready(function() {
        $("#custom_export").click(function() {
            $('#block_export_rows').slideDown();
            $('#block_export_output').slideDown();
            $('#block_export_data_dump_options').slideDown();
        });
    });
    $(document).ready(function() {
        $("#quick_export").click(function() {
            $('#block_export_rows').slideUp();
            $('#block_export_output').slideUp();
            $('#block_export_data_dump_options').slideUp();
        });
    });
    $(document).ready(function() {
        $("#dump_some_export").click(function() {
            $('#dump_some_row_export').show();
        });
    });
    $(document).ready(function() {
        $("#dump_all_export").click(function() {
            $('#dump_some_row_export').hide();
        });
    });
    $(document).ready(function() {
        $("#save_export").click(function() {
            $('#save_output_to_a_file').show();
        });
    });
    $(document).ready(function() {
        $("#text_export").click(function() {
            $('#save_output_to_a_file').hide();
        });
    });
    
    
</script>    
