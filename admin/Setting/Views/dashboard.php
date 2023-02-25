<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title"><?php echo $heading_title; ?></h3>
    </div>
    <div class="block-content block-content-full">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th rowspan="2">Dashboard Name</th>
                        <th rowspan="2">Width</th>
                        <th rowspan="2">Admin Status</th>
                        <th rowspan="2">Front Status</th>
                        <th colspan="<?=count($roles)?>">Role</th>
                    </tr>
                    <tr>
                        <?php foreach($roles as $role){?>
                        <th><?=$role->name?></th>
                        <?}?>
                    </tr>
                </thead>
                <tbody id="report_menu">
                <?php
                $permissions=[];
                foreach ($dreports as $report) {
                    $permissions=json_decode($report->permission,true);
                    //printr($permissions);
                    ?>
                    <tr class="report_menu draggable-item" id="<?= $report->id ?>">
                        <td class=" pl-lg">
                            <a class="btn-block-option draggable-handler" href="javascript:void(0)">
                                <i class="si si-cursor-move"></i>
                            </a>
                            <?= lang($report->name) ?>
                        </td>
                        <td class="pl-lg">
                            <input type="text" data-id="<?= $report->id ?>" name="col" value="<?= $report->col ?>" class="form-control">
                        </td>
                        <td>
                            <div class="col-lg-5 checkbox change_status">
                                <label class="css-control css-control-sm css-control-primary css-switch">
                                    <?php echo form_checkbox(array('class'=>'css-control-input','data-id'=>$report->id , 'data-status'=>'b', 'name' => 'status', 'value' => 1,'checked' => ($report->status == 1 ? true : false))); ?>
                                    <span class="css-control-indicator"></span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="col-lg-5 checkbox change_status">
                                <label class="css-control css-control-sm css-control-primary css-switch">
                                    <?php echo form_checkbox(array('class'=>'css-control-input','data-id'=>$report->id ,'data-status'=>'f', 'name' => 'dstatus', 'value' => 1,'checked' => ($report->dstatus == 1 ? true : false))); ?>
                                    <span class="css-control-indicator"></span>
                                </label>
                            </div>
                        </td>
                        <?php foreach($roles as $role){

                            ?>
                            <td class="change_permission">
                                <label class="css-control css-control-sm css-control-primary css-switch">
                                    <?php echo form_checkbox(array('class'=>'css-control-input','name' => 'permission['.$role->id.']', 'value' => $role->id,'checked' => (isset($permissions[$role->id])?($permissions[$role->id]==1?true : false): false))); ?>
                                    <span class="css-control-indicator"></span>
                                </label>
                            </td>
                        <?}?>

                    </tr>
                <?php }
                ?>
                </tbody>

            </table>
        </div>
    </div>
</div>
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Malkangiri Dashboard Setting</h3>
    </div>
    <div class="block-content block-content-full">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-primary">
                <tr>
                    <th rowspan="2">Dashboard Name</th>
                    <th rowspan="2">Width</th>
                    <th rowspan="2">Admin Status</th>
                    <th rowspan="2">Front Status</th>
                    <th colspan="<?=count($roles)?>">Role</th>
                </tr>
                <tr>
                    <?php foreach($roles as $role){?>
                        <th><?=$role->name?></th>
                    <?}?>
                </tr>
                </thead>
                <tbody id="report_menu">
                <?php
                $permissions=[];
                foreach ($dmreports as $report) {
                    $permissions=json_decode($report->permission,true);
                    //printr($permissions);
                    ?>
                    <tr class="report_menu draggable-item" id="<?= $report->id ?>">
                        <td class=" pl-lg">
                            <a class="btn-block-option draggable-handler" href="javascript:void(0)">
                                <i class="si si-cursor-move"></i>
                            </a>
                            <?= lang($report->name) ?>
                        </td>
                        <td class="pl-lg">
                            <input type="text" data-id="<?= $report->id ?>" name="col" value="<?= $report->col ?>" class="form-control">
                        </td>
                        <td>
                            <div class="col-lg-5 checkbox change_status">
                                <label class="css-control css-control-sm css-control-primary css-switch">
                                    <?php echo form_checkbox(array('class'=>'css-control-input','data-id'=>$report->id , 'data-status'=>'b', 'name' => 'status', 'value' => 1,'checked' => ($report->status == 1 ? true : false))); ?>
                                    <span class="css-control-indicator"></span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="col-lg-5 checkbox change_status">
                                <label class="css-control css-control-sm css-control-primary css-switch">
                                    <?php echo form_checkbox(array('class'=>'css-control-input','data-id'=>$report->id ,'data-status'=>'f', 'name' => 'dstatus', 'value' => 1,'checked' => ($report->dstatus == 1 ? true : false))); ?>
                                    <span class="css-control-indicator"></span>
                                </label>
                            </div>
                        </td>
                        <?php foreach($roles as $role){

                            ?>
                            <td class="change_permission">
                                <label class="css-control css-control-sm css-control-primary css-switch">
                                    <?php echo form_checkbox(array('class'=>'css-control-input','name' => 'permission['.$role->id.']', 'value' => $role->id,'checked' => (isset($permissions[$role->id])?($permissions[$role->id]==1?true : false): false))); ?>
                                    <span class="css-control-indicator"></span>
                                </label>
                            </td>
                        <?}?>

                    </tr>
                <?php }
                ?>
                </tbody>

            </table>
        </div>
    </div>
</div>

<?php js_start(); ?>
<script type="text/javascript"><!--
    jQuery(function(){ Codebase.helpers('draggable-items'); });
    $(document).ready(function () {
        $('.change_status input[type="checkbox"]').change(function () {
            var id = $(this).data().id;
            var status = $(this).is(":checked");
            if (status == true) {
                status = 1;
            } else {
                status = 0;
            }
            var action = $(this).data().status;
            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: '<?= admin_url()?>/setting/save_dashboard/' + id + '/' + action, // the url where we want to POST
                dataType: 'json', // what type of data do we expect back from the server
                data:{status:status},
                encode: true,
                success: function (res) {
                    if (res) {
//                        toastr[res.status](res.message);
                    } else {
                        alert('There was a problem with AJAX');
                    }
                }
            })

        });
        $('.change_permission input[type="checkbox"]').change(function () {
            var parenttr=$(this).parents('tr');
            var id = $(parenttr).attr('id');

            var role_ids = $(parenttr).find('input[name^="permission"]:checked').map(function()
            {
                role_id=$(this).val();
                return role_id;
            }).get();
            var status='p';
            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: '<?= admin_url()?>/setting/save_dashboard/' + id + '/' + status, // the url where we want to POST
                data:{role_ids:role_ids},
                dataType: 'json', // what type of data do we expect back from the server
                encode: true,
                success: function (res) {
                    if (res) {
//                        toastr[res.status](res.message);
                    } else {
                        alert('There was a problem with AJAX');
                    }
                }
            })

        });
        $('input[name="col"]').change(function () {
            var id = $(this).data().id;
            var col = $(this).val();
            var status="w"
            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: '<?= admin_url()?>/setting/save_dashboard/' + id + '/' + status, // the url where we want to POST
                dataType: 'json', // what type of data do we expect back from the server
                data:{col:col},
                encode: true,
                success: function (res) {
                    if (res) {
//                        toastr[res.status](res.message);
                    } else {
                        alert('There was a problem with AJAX');
                    }
                }
            })

        });
    })
    $(function () {
        $('tbody[id^="report_menu"]').sortable({
            connectWith: ".report_menu",
            placeholder: 'ui-state-highlight',
            forcePlaceholderSize: true,
            stop: function (event, ui) {
                var id = JSON.stringify(
                    $('tbody[id^="report_menu"]').sortable(
                        'toArray',
                        {
                            attribute: 'id'
                        }
                    )
                );
                var formData = {
                    'report_menu': id
                };
                $.ajax({
                    type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url: '<?= admin_url()?>setting/save_dashboard/', // the url where we want to POST
                    data: formData, // our data object
                    dataType: 'json', // what type of data do we expect back from the server
                    encode: true,
                    success: function (res) {
                        if (res) {
//                            toastr[res.status](res.message);
                        } else {
                            alert('There was a problem with AJAX');
                        }
                    }
                })

            }
        });
        $(".report_menu").disableSelection();
    });
    //--></script>
<?php js_end(); ?>