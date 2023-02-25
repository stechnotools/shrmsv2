<?php 
$template=service('template');
$user=service('user');
?>
<?php if ($user->isLogged()): ?>
        <footer class="footer text-right"><span class="pull-left">Page rendered in <strong>{elapsed_time}</strong> seconds.</span> Copyright &copy; <?php echo date('Y'); ?>&nbsp; v<?php echo AIOADMIN_VERSION ?></footer>
    </div>
</div>
<?endif; ?>

<div id="loading" style="display:none;">
    <img src="<?php echo theme_url('assets/images/ajax-loader.gif');?>" alt="Loading">Processing...
</div>
<script>
    var resizefunc = [];
</script>
<!-- Main  -->
<script src="<?php echo theme_url('assets/js/jquery.min.js'); ?>"></script>
<!--<script src="<?php echo theme_url('assets/js/jquery-ui.min.js'); ?>"></script>-->
<!--<script src="<?php echo theme_url('assets/js/plugin.js'); ?>"></script>-->
<script src="<?php echo theme_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/detect.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/fastclick.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/jquery.slimscroll.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/jquery.blockUI.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/waves.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/wow.min.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/jquery.nicescroll.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/jquery.scrollTo.min.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/jquery.form.min.js'); ?>"></script>
<script src="//cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
<script src="<?php echo theme_url('assets/js/jquery.app.js'); ?>"></script>
<?php echo $template->footer_javascript() ?>
<script src="<?php echo theme_url('assets/js/common.js'); ?>"></script>
<!-- Modal -->
<div class="modal fade dmodal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mt-0" id="myLargeModalLabel">Large modal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
</body>
</html>