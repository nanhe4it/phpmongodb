<div class="header">
    <h1 class="page-title"><?php echo $this->db; ?></h1>
</div>
<div class="row-fluid">
    <div class="block span6">
        <div class="block-heading">


            <a href="#widget2container" data-toggle="collapse">Collection</a>
        </div>
        <div id="widget2container" class="block-body collapse in">
            <table class="table list">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Total Record</th>
                        <th ></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->data['collectionList'] as $collection) { ?>
                        <tr>
                            <td><p><i class="icon-user"></i> <a href="<?php echo Theme::URL('Collection/Record', array('db' =>$this->db,'collection'=>$collection['name'])); ?>"><?php echo $collection['name']; ?></a></p></td>

                            <td><?php echo $collection['count']; ?></td>
                            <td>
                                <a href="#myModal" data-edit-collection="<?php echo addslashes($collection['name']); ?>" role="button" data-toggle="modal"><i class="icon-pencil"></i></a>
                                <a href="#myModal" data-delete-collection="<?php echo addslashes($collection['name']); ?>"role="button" data-toggle="modal"><i class="icon-remove"></i></a>
                            </td>
                        </tr>
                    <?php } ?>


                </tbody>
            </table>
        </div>
    </div>
    <div class="block span6"><a name="editCollection"></a>
        <p class="block-heading" id="block-heading">Create Collection</p>
        <div class="block-body">
            <form id="form-create-collection" method="post" class="form-inline">
                <label>Name</label>
                <input type="text" value="" id="collection" name="collection" class="input-xlarge">
                <input type="hidden" id="load-create" name="load" value="Collection/Save" />
                <input type="hidden" name="db" value="<?php echo $this->db; ?>" />
                

                <button class="btn " name="save"><i class="icon-save" ></i> Save</button>

            </form>
        </div>
    </div>


</div>

<form method="post" name="form-delete-collection" id="form-delete-collection" >
    
    <div class="modal small hide fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Delete Confirmation</h3>
        </div>
        <div class="modal-body">
            <input type="text" value="" id="pop-up-collection" name="collection" class="input-xlarge">
            
            <p class="error-text" id="pop-up-error-text"><i class="icon-warning-sign modal-icon"></i>Are you sure you want to delete collection ?</p>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
            <button class="btn " id="button-create-collection"><i class="icon-save" ></i> Save</button>
            <button id="button-delete-collection" class="btn btn-danger" data-dismiss="modal">Delete</button>
        </div>
    </div>
    <input type="hidden" id="pop-up-load" name="load" value="" />
    <input type="hidden" name="db" id="pop-up-db" value="<?php echo $this->db; ?>" />
    <input type="hidden" id="pop-up-old_collection" name="old_collection" value="" />
</form> 



<script type="text/javascript">
    $(document).ready(function() {
        
        
        $("a[data-edit-collection]").click(function() {
            
            $("#pop-up-collection").val($(this).attr("data-edit-collection"));
            $("#pop-up-old_collection").val($(this).attr("data-edit-collection"));
            $("#pop-up-load").val("Collection/Update");
            $('#button-delete-collection').hide();
            $('#button-create-collection').show();
            $("#pop-up-collection").show();
            $('#pop-up-error-text').hide();
            $("#myModalLabel").text('Edit Collection');
            
        });
        
        $("a[data-delete-collection]").click(function() {
            $("#pop-up-collection").val($(this).attr("data-delete-collection"));
            $("#pop-up-load").val("Collection/Drop");
            $('#button-delete-collection').show();
            $('#button-create-collection').hide();
            $("#pop-up-collection").hide();
            $('#pop-up-error-text').show();
            $("#myModalLabel").text('Delete Collection');

        });
        $('#button-delete-collection').click(function() {

            $('#form-delete-collection').submit();
            return true;
        });

    });


</script>

