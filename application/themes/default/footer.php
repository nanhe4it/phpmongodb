<?php defined('PMDDA') or die('Restricted access'); ?>
<script src="<?php echo Theme::getPath(); ?>lib/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript">
    $("[rel=tooltip]").tooltip();
    $(function() {
        $('.demo-cancel-click').click(function() {
            return false;
        });
    });
</script>


</body>
</html>