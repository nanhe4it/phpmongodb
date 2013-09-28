<div class="header">
    <h1 class="page-title"><?php echo $this->db; ?>(<?php echo $this->collection; ?>) </h1>
</div>

<?php require_once '_menu.php';?>
<?php require_once '_insert.php';?>
<?php require_once '_list.php';?>






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



