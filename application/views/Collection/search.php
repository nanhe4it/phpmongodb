<?php require_once '_menu.php'; ?>
<div class="well" id="container-indexes">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#searchColVal" data-toggle="tab"><?php I18n::p('ATTRIBUTES'); ?></a></li>

    </ul>

    <div id="myTabContent" class="tab-content">

        <div class="tab-pane active in" id="searchColVal">
            <form id="tab1" method="post" action="index.php">
                <table id="tbl-search-col-val">
                    <tr>
                        <th>Field</th>
                        <th>Operator</th>
                        <th>Value</th>

                        <th>&nbsp;</th>
                    </tr>
                    <tr>
                        <td><input type="text" class="input-xlarge" name="fields[]"  placeholder="Enter Attribute"></td>
                        <td><select name="operators[]" style="width: auto;">
                                <option value="==">=</option>
                                <option value="&gt;">&gt;</option>
                                <option value="&gt;=">&gt;=</option>
                                <option value="&lt;">&lt;</option>
                                <option value="&lt;=">&lt;=</option>
                                <option value="!=">!=</option>
                            </select></td>
                        <td ><input type="text" class="input-xlarge" name="values[]" placeholder="Enter Value"></td>


                        <td>&nbsp;<a href="javascript:void(0)" onclick="appendTR();" class="btn-primary"><i class="icon-plus"></i></a>&nbsp;</td>
                    </tr>


                </table>


                <p >Logical Operator
                    <input type="radio" checked="checked" name="logical_operator" value="&&" > && 
                    <input type="radio" name="logical_operator" value="||" >||
                </p>

                <div>
                    <button class="btn btn-primary"><?php I18n::p('GO'); ?></button>
                </div>
                <input type="hidden"  name="load" value="Collection/Record"/>
                <input type="hidden" name="db" value="<?php echo $this->db; ?>" />
                <input type="hidden" name="collection" value="<?php echo $this->collection; ?>" />
                <input type="hidden" name="search" value="1" />
            </form>
        </div>

    </div>
</div>
<script>
                            function appendTR() {
                                var trID = 'tr-indexes' + $('#tbl-search-col-val tr').length;
                                var tr = '<tr id="' + trID + '">';
                                tr = tr + '<td><input type="text" class="input-xlarge" name="fields[]"  placeholder="Enter Attribute"></td>';
                                tr = tr + '<td>';
                                tr = tr + '<select name="operators[]" style="width: auto;">';
                                tr = tr + '<option value="==">=</option>';
                                tr = tr + '<option value="&gt;">&gt;</option>';
                                tr = tr + '<option value="&gt;=">&gt;=</option>';
                                tr = tr + '<option value="&lt;">&lt;</option>';
                                tr = tr + '<option value="&lt;=">&lt;=</option>';
                                tr = tr + '<option value="!=">!=</option>';
                                tr = tr + '</select>';
                                tr = tr + '</td>';
                                tr = tr + '<td><input type="text" class="input-xlarge" name="values[]" placeholder="Enter Value"></td>';
                                tr = tr + '<td>';
                                tr = tr + '&nbsp;<a href="javascript:void(0)" onclick="appendTR();" class="btn-primary"><i class="icon-plus"></i></a>&nbsp;';
                                tr = tr + "<a href=\"javascript:void(0)\" onclick=\"removeTR('" + trID + "');\" class=\"btn-primary\"><i class=\"icon-minus\"></i></a>";
                                tr = tr + '</td>';
                                tr = tr + '</tr>';
                                $("#tbl-search-col-val").append(tr);
                                return false;
                            }
                            function removeTR(trID) {

                                $("table#tbl-search-col-val tr#" + trID).remove();
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
