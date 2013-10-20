<div class="header">
    <h1 class="page-title"><?php I18n::p('DB');?></h1>
</div>
<div class="row-fluid">
    <div class="block span6">
        <div class="block-heading">
            <a href="#widget2container" data-toggle="collapse"><?php I18n::p('DB');?></a>
        </div>
        <div id="widget2container" class="block-body collapse in">
            <table class="table list">
                <thead>
                    <tr>
                        <th><?php I18n::p('NAME');?></th>
                        <th><?php I18n::p('S_O_D');?></th>
                        <th >&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->data['dbList']['databases'] as $db) { ?>
                        <tr>
                            <td><a href="<?php echo Theme::URL('Collection/Index', array('db' => $db['name'])); ?>"><?php echo $db['name']; ?></i></td>
                            <td><?php echo $db['sizeOnDisk']; ?></td>
                            <td>
                                <a href="#myModal" data-edit-db="<?php echo addslashes($db['name']); ?>" role="button" data-toggle="modal"><i class="icon-pencil"></i></a>
                                <a href="#myModal" data-delete-db="<?php echo addslashes($db['name']); ?>" role="button" data-toggle="modal"><i class="icon-remove"  ></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="block span6"><a name="editCollection"></a>
        <p class="block-heading" id="block-heading"><?php I18n::p('C_DB');?></p>
        <div class="block-body">
            <form id="form-create-database" method="post" class="form-inline" action="index.php">
                <label><?php I18n::p('NAME');?></label>
                <input type="text" value="" id="database" name="db" class="input-xlarge" required="required">
                <input type="hidden" id="load-create" name="load" value="Database/Save" />
                <button class="btn " name="btnCreateDb"><i class="icon-save" ></i><?php I18n::p('SAVE');?> </button>
            </form>
        </div>
    </div>
</div>
<form method="post" name="form-drop-db" id="form-drop-db" action="index.php">
    <div class="modal small hide fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel"><?php I18n::p('DEL_C');?></h3>
        </div>
        <div class="modal-body">
            <input type="text" value="" id="pop-up-database" name="db" class="input-xlarge" required="required">
            <p class="error-text" id="pop-up-error-text"><i class="icon-warning-sign modal-icon"></i><?php I18n::p('A_Y_W_T_D_D');?></p>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true"><?php I18n::p('CANCEL');?></button>
            <button class="btn " id="button-create-database"><i class="icon-save" ></i><?php I18n::p('SAVE');?></button>
            <button id="button-delete-database" class="btn btn-danger" data-dismiss="modal"><?php I18n::p('DELETE');?></button>
        </div>
    </div>
    <input type="hidden" id="pop-up-load" name="load" value="" />
    <input type="hidden" id="pop-up-old-database" name="old_db" value="" />
</form> 

<script type="text/javascript">
    $(document).ready(function() {
        $("a[data-delete-db]").click(function() {
            $("#pop-up-database").val($(this).attr("data-delete-db"));
            $("#pop-up-load").val("Database/Drop");
            $('#button-delete-database').show();
            $('#button-create-database').hide();
            $("#pop-up-database").hide();
            $('#pop-up-error-text').show();
            $("#myModalLabel").text('Delete Database');
        });
        $("a[data-edit-db]").click(function() {
            $("#pop-up-database").val($(this).attr("data-edit-db"));
            $("#pop-up-old-database").val($(this).attr("data-edit-db"));
            $("#pop-up-load").val("Database/Update");
            $('#button-delete-database').hide();
            $('#button-create-database').show();
            $("#pop-up-database").show();
            $('#pop-up-error-text').hide();
            $("#myModalLabel").text('Edit Database');
        });
        $('#button-delete-database').click(function() {
            $('#form-drop-db').submit();
            return true;
        });
    });
</script>

