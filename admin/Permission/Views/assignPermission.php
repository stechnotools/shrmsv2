<?php
$validation = \Config\Services::validation();
?>
<?php echo form_open_multipart('', 'id="form-assign"'); ?>
    <div class="row">

        <div class="col-12">
            <div class="card">
            <div class="card-header py-2 text-white">
				<h3 class="card-title float-left my-2"><?php echo $text_form; ?></h3>
				<div class="panel-tools float-right">
					<button type="submit" data-toggle="tooltip" title="Save" class="btn btn-danger btn-sm" form="form-assign"><i class="fa fa-save"></i></button>
					<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Cancel" class="btn btn-primary btn-sm"><i class="fa fa-reply"></i></a>
				</div>
			</div>
                <div class="card-body">
                    <form action="<?=base_url()?>" class="form-horizontal" role="form" method="post" id="roletype">
                        <table id="" class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                            <tr>
                                <th class="col-lg-2" rowspan="2">Module Name</th>
                                <th class="col-lg-10" colspan="6">Permissions</th>

                            </tr>
                            <tr>
                                <th class="col-lg-2">Index</th>
                                <th class="col-lg-1">Add</th>
                                <th class="col-lg-1">Edit</th>
                                <th class="col-lg-1">Delete</th>
                                <th class="col-lg-1">View</th>
                                <th class="col-lg-6">Miscellaneous</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                $prePermission=['index','add','edit','delete','view','mis'];
                                foreach($gpermission as $module=>$permissions){?>
                                    <tr>

                                        <td data-title="Module Name">
                                            <?php echo $module; ?>
                                        </td>
                                        <?php foreach($prePermission as $action){?>
                                            <td data-title="">
                                            <?php
                                                if(isset($permissions[$action])) {
                                                    foreach($permissions[$action] as $menu){?>
                                                        
                                                        <div class="checkbox checkbox-success form-check-inline">
                                                            <input type="checkbox" name='<?=$menu->route?>' value="<?=$menu->id?>" <?=$menu->active=="yes"?"checked='checked'":""?>>
                                                            <label>
                                                                <?=$menu->description?>
                                                            </label>
                                                        </div>

                                                    <?}
                                                }
                                                ?>
                                            </td>
                                        <?}?>
                                    </tr>
                                <?}?>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php echo form_close(); ?>
<?php js_start(); ?>
    <script type="text/javascript"><!--
        $.fn.processCheck = function() {
            var id = $(this).attr('id');
            if ($('input#'+id).is(':checked')) {
                $(this).parents('tr').find('td[data-id="'+id+'"]').find('input').prop('disabled', false);;
                $(this).parents('tr').find('td[data-id="'+id+'"]').find('input').prop('checked', true);;

            } else {
                $(this).parents('tr').find('td[data-id="'+id+'"]').find('input').prop('disabled', true);;
                $(this).parents('tr').find('td[data-id="'+id+'"]').find('input').prop('checked', false);;

            }
        };
        //--></script>
<?php js_end(); ?>