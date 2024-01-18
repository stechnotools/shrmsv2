<?php
$template=service('template');
$user=service('user');
?>
<?php if ($user->isLogged()): ?>
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <span class="pull-left">Page rendered in <strong>{elapsed_time}</strong> seconds.</span> Copyright &copy; <?php echo date('Y'); ?>&nbsp; v<?php echo AIOADMIN_VERSION ?>
                </div>
            </div>
        </div>
    </footer>
    </div>
</div>
<?endif; ?>
<!-- Start of LiveChat (www.livechat.com) code -->

<!-- Vendor js -->
<script src="<?php echo theme_url('assets/js/vendor.min.js')?>"></script>
<!-- App js -->
<script src="<?php echo theme_url('assets/js/app.min.js')?>"></script>
<script src="<?php echo theme_url('assets/js/jquery.form.min.js'); ?>"></script>
<?php echo $template->footer_javascript() ?>
<script src="<?php echo theme_url('assets/js/loadingoverlay.min.js'); ?>"></script>
<script src="<?php echo theme_url('assets/js/common.js'); ?>"></script>

<div class="modal fade employeemodal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title mt-0" id="myLargeModalLabel">Employee List</h4>
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