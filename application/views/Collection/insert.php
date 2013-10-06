<?php require_once '_menu.php'; ?>
<?php require_once '_insert.php';?>
<script>
    $('#container-insert').show();
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
</script>        