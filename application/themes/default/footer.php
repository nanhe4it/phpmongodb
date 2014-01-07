<?php defined('PMDDA') or die('Restricted access'); ?>
<script src="<?php echo Theme::getPath(); ?>bootstrap/js/bootstrap.js"></script>
<script type="text/javascript">
    $("[rel=tooltip]").tooltip();
    $(function() {
        $('.demo-cancel-click').click(function() {
            return false;
        });
    });
</script>

<div class="navbar">
            <div class="navbar-inner">
                <ul class="nav pull-right">
                   
                    <li > <a href="http://www.phpmongodb.org" target="_blank">&copy; 2013 PHPMongoDB.org</a></li>
                    

                </ul>
                
            </div>
        </div>

<div class="navbar">
    
</div>
<script>
    function callAjax(url) {
        url = url + '&theme=false'
        $(document).ready(function() {
            
                $.get(url, function(data, status) {
                   if(status=='success'){
                     $( "#middle-content" ).html(data);  
                   }
                });
           
        })
    }
</script>    
</body>
</html>