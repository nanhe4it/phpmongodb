


<div class="row-fluid">
    <div class="block span6">
        <a href="#tablewidget" class="block-heading" data-toggle="collapse">Web server</a>
        <div id="tablewidget" class="block-body collapse in">
            <table class="table">

                <tbody>
                    <tr>
                        <td>PHP version</td>
                        <td><?php echo $this->_data['phpversion']; ?></td>
                    </tr>
                    <tr>
                        <td>Web server</td>
                        <td><?php echo $this->_data['webserver']; ?></td>
                    </tr>
                    <tr>
                        <td>MongoDB Version</td>
                        <td><?php echo $this->_data['mongoinfo']['version']; ?></td>
                    </tr>
                    <tr>
                        <td>MongoDB Version</td>
                        <td><?php echo $this->_data['mongoinfo']['version']; ?></td>
                    </tr>

                </tbody>
            </table>

            
        </div>
    </div>
    <div class="block span6">
        <a href="#widget1container" class="block-heading" data-toggle="collapse">Build Information </a>
        <div id="widget1container" class="block-body collapse in">
            <table class="table">

                <tbody>
                
                    <tr>
                        <td>Version</td>
                        <td><?php echo $this->_data['mongoinfo']['version']; ?></td>
                    </tr>
                    <tr>
                        <td>gitVersion</td>
                        <td><?php echo $this->_data['mongoinfo']['gitVersion']; ?></td>
                    </tr>
                    <tr>
                        <td>sysInfo</td>
                        <td><?php echo $this->_data['mongoinfo']['sysInfo']; ?></td>
                    </tr>
                    <tr>
                        <td>loaderFlags</td>
                        <td><?php echo $this->_data['mongoinfo']['loaderFlags']; ?></td>
                    </tr>
                    <tr>
                        <td>compilerFlags</td>
                        <td><?php echo $this->_data['mongoinfo']['compilerFlags']; ?></td>
                    </tr>
                    <tr>
                        <td>allocator</td>
                        <td><?php echo $this->_data['mongoinfo']['allocator']; ?></td>
                    </tr>
                    <tr>
                        <td>javascriptEngine</td>
                        <td><?php echo $this->_data['mongoinfo']['javascriptEngine']; ?></td>
                    </tr>
                    <tr>
                        <td>bits</td>
                        <td><?php echo $this->_data['mongoinfo']['bits']; ?></td>
                    </tr>
                    <tr>
                        <td>debug</td>
                        <td><?php echo $this->_data['mongoinfo']['debug']; ?></td>
                    </tr>
                    <tr>
                        <td>maxBsonObjectSize</td>
                        <td><?php echo $this->_data['mongoinfo']['maxBsonObjectSize']; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


