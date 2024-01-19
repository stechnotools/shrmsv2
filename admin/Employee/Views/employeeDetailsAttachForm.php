<div class="card card-border">
    <div class="card-header border-info bg-transparent pb-0">
        <h3 class="card-title text-info">Employee Details</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group row required">
                    <label class="col-md-4 control-label" for="input-firstname">Paycode</label>
                    <div class="col-md-8">
                        <div class="input-group">
                            <?php echo form_input(array('class'=>'form-control','name' => 'paycode', 'id' => 'paycode', 'placeholder'=>"Paycode",'readonly'=>'true','value' => set_value('paycode', ''))); ?>
                            
                            <span class="input-group-prepend">
                                <button type="button" class="btn waves-effect waves-light btn-primary" id="employee_list"><i class="fa fa-search"></i></button>
                            </span>
                           
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group row required">
                    <label class="col-md-4 control-label" for="input-firstname">Name</label>
                    <div class="col-md-8">
                        <?php echo form_input(array('class'=>'form-control','name' => 'employee_name', 'id' => 'loan_name', 'placeholder'=>"Employee Name",'readonly'=>'true','value' => set_value('employee_name', ''))); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group row required">
                    <label class="col-md-4 control-label" for="input-firstname">Branch</label>
                    <div class="col-md-8">
                        <?php echo form_input(array('class'=>'form-control','name' => 'branch_name', 'id' => 'branch_name', 'placeholder'=>"Branch Name",'readonly'=>'true','value' => set_value('branch_name', ''))); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group row required">
                    <label class="col-md-4 control-label" for="input-firstname">Card No</label>
                    <div class="col-md-8">
                        <?php echo form_input(array('class'=>'form-control','name' => 'card_no', 'id' => 'card_no', 'placeholder'=>"Card No",'readonly'=>'true','value' => set_value('card_no', ''))); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group row required">
                    <label class="col-md-4 control-label" for="input-firstname">Department</label>
                    <div class="col-md-8">
                        <?php echo form_input(array('class'=>'form-control','name' => 'department_name', 'id' => 'department_name', 'placeholder'=>"Department Name",'readonly'=>'true','value' => set_value('department_name', ''))); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group row required">
                    <label class="col-md-4 control-label" for="input-firstname">Designation</label>
                    <div class="col-md-8">
                        <?php echo form_input(array('class'=>'form-control','name' => 'designation_name', 'id' => 'designation_name', 'placeholder'=>"Designation Name",'readonly'=>'true','value' => set_value('designation_name', ''))); ?>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>