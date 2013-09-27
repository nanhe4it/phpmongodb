<div class="header">
    <h1 class="page-title">Databases</h1>
</div>
<div class="row-fluid">
    <div class="block span6">
        <div class="block-heading">


            <a href="#widget2container" data-toggle="collapse">Database</a>
        </div>
        <div id="widget2container" class="block-body collapse in">
            <table class="table list">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>size On Disk</th>
                        <th ></th>
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
        <p class="block-heading" id="block-heading">Create Database</p>
        <div class="block-body">
            <form id="form-create-database" method="post" class="form-inline">
                <label>Name</label>
                <input type="text" value="" id="database" name="db" class="input-xlarge">
                <input type="hidden" id="load-create" name="load" value="Database/Save" />
                <button class="btn " name="save"><i class="icon-save" ></i> Save</button>

            </form>
        </div>
    </div>


</div>

<form method="post" name="form-drop-db" id="form-drop-db" >

    <div class="modal small hide fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Delete Confirmation</h3>
        </div>
        <div class="modal-body">
            <input type="text" value="" id="pop-up-database" name="db" class="input-xlarge">

            <p class="error-text" id="pop-up-error-text"><i class="icon-warning-sign modal-icon"></i>Are you sure you want to delete database ?</p>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
            <button class="btn " id="button-create-database"><i class="icon-save" ></i> Save</button>
            <button id="button-delete-database" class="btn btn-danger" data-dismiss="modal">Delete</button>
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

