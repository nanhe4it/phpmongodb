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



<div class="navbar">
    &copy; 2013 <a href="http://www.phpmongodb.org" target="_blank">PHPMongoDB.org</a>
</div>

</body>
</html>