<div class="well">
    <?php
    $showTab = true;
    foreach ($this->data['format'] as $format) {
        if (!isset($this->data['record'][$format]))
            continue;
        ?>
        <?php
        if ($showTab) {
            ?>    
            <ul class="nav nav-tabs">
                <li id="li-json"class="active"><a href="javascript:void(0)" data-list-record="json">JSON</a></li>
                <li id="li-array"><a href="javascript:void(0)" data-list-record="array">Array</a></li>
                <li id="li-document"><a href="javascript:void(0)" data-list-record="document">Find</a></li>
            </ul>
            <?php
            $showTab = false;
        }
        ?>
        <div id="record-<?php echo $format; ?>" style="display: <?php echo $format === 'json' ? 'block' : 'none'; ?>">
            <?php
            foreach ($this->data['record'][$format] as $cursor) {

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

<?php Theme::pagination($this->getModel()->totalRecord($this->db, $this->collection)); ?>