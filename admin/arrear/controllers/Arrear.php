<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use NXP\MathExecutor;
class Arrear extends Admin_Controller {
	private $error = array();
	public function __construct(){
		parent::__construct();
		$this->load->model('arrear/arrear_model');
		$this->load->model('employee/employee_model');
		$this->load->model('attendance/attendance_model');
		$this->load->model('payment/payment_model');
		//$this->load->model('arrear_time_model');		
	}
	public function index(){
      	$this->lang->load('arrear');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		$this->getList();  
	}
	protected function search() {
		$requestData= $_REQUEST;
		$totalData = $this->arrear_model->getTotalArrears();
		$totalFiltered = $totalData;
		$filter_data = array(
			'filter_search'  => $requestData['search']['value'],
			'branch_id' => $requestData['branch_id'],
			'department_id' => $requestData['department_id'],
			'designation_id' => $requestData['designation_id'],
			'order'  		 => $requestData['order'][0]['dir'],
			'sort' 			 => $requestData['order'][0]['column'],
			'start' 		 => $requestData['start'],
			'limit' 		 => $requestData['length']
		);
		$totalFiltered = $this->arrear_model->getTotalArrears($filter_data);
		$filteredData = $this->arrear_model->getArrears($filter_data);
		//printr($filteredData);
		$datatable=array();
		foreach($filteredData as $result) {
			$earning=json_decode($result->earning,true);
			if($earning){
				$earning=array_sum($earning);
			}
			$deduction=json_decode($result->deduction,true);
			if($deduction){
				$deduction=array_sum($deduction);
			}
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('arrear/edit/'.$result->user_id).'"><i class="fa fa-pencil"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('arrear/delete/'.$result->user_id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';
			$action .= '</div>';
			$datatable[]=array(
				'<input type="checkbox" name="selected[]" value="'.$result->user_id.'" />',
				$result->employee_name,
				$result->paycode,
				$result->basic,
				$earning,
				$deduction,
				$result->basic,
				$action
			);
		}
		//printr($datatable);
		$json_data = array(
			"draw"            => isset($requestData['draw']) ? intval( $requestData['draw'] ):1,
			"recordsTotal"    => intval( $totalData ),
			"recordsFiltered" => intval( $totalFiltered ),
			"data"            => $datatable
		);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($json_data));  // send data as json format
	}
	public function add(){
		$this->lang->load('arrear');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){
			$this->load->model('setting/setting_model');
			$this->setting_model->editSetting('formula',$this->input->post('formula'));
			if($this->input->post('suser_id')){
				$user_id=$this->input->post('suser_id');
				$this->arrear_model->editArrear($user_id,$this->input->post());
			}else{
				$userid=$this->arrear_model->addArrear($this->input->post());
			}
			$this->session->set_flashdata('message', 'arrear Saved Successfully.');
			redirect(ADMIN_PATH.'/arrear');
		}
		$this->getForm();
	}
	public function edit(){
		$this->lang->load('arrear');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
		//printr($this->input->post());
		//exit;
			$user_id=$this->uri->segment(4);
			$this->load->model('setting/setting_model');
			$this->setting_model->editSetting('formula',$this->input->post('formula'));
			
			$this->arrear_model->editArrear($user_id,$this->input->post());
			$this->session->set_flashdata('message', 'arrear Updated Successfully.');
			redirect(ADMIN_PATH.'/arrear');
		}
		$this->getForm();
	}
	public function delete(){
		if ($this->input->post('selected')){
         $selected = $this->input->post('selected');
      }else{
         $selected = (array) $this->uri->segment(4);
       }
		$this->arrear_model->deleteArrear($selected);
		$this->session->set_flashdata('message', 'arrear deleted Successfully.');
		redirect(ADMIN_PATH.'/arrear');
	}
	protected function getList() {
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('heading_title'),
			'href' => admin_url('arrear')
		);
		$this->template->add_package(array('datatable'),true);
		$data['add'] = admin_url('arrear/add');
		$data['delete'] = admin_url('arrear/delete');
		$data['datatable_url'] = admin_url('arrear/search');
		$data['emp_sample']=base_url('storage/uploads/files/arrear-sample.xlsx');
		$data['heading_title'] = $this->lang->line('heading_title');
		$data['text_list'] = $this->lang->line('text_list');
		$data['text_no_results'] = $this->lang->line('text_no_results');
		$data['text_confirm'] = $this->lang->line('text_confirm');
		$data['button_add'] = $this->lang->line('button_add');
		$data['button_edit'] = $this->lang->line('button_edit');
		$data['button_delete'] = $this->lang->line('button_delete');
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}
		if ($this->input->post('selected')) {
			$data['selected'] = (array)$this->input->post('selected');
		} else {
			$data['selected'] = array();
		}
		
		$this->load->model('branch/branch_model');
		$data['branches']=$this->branch_model->getBranches();
		
		$this->load->model('department/department_model');
		$data['departments']=$this->department_model->getDepartments();
		
		$this->load->model('designation/designation_model');
		$data['designations']=$this->designation_model->getDesignations();
		
		
		$this->template->view('arrears', $data);
	}
	protected function getForm(){
		$data = array();
		$data = $this->lang->load('arrear');
		$this->template->add_package(array('ckeditor','ckfinder','colorbox','select2','datepicker','datatable'),true);
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('heading_title'),
			'href' => admin_url('arrear')
		);
		//printr($_SESSION);
		$_SESSION['isLoggedIn'] = true;
		$data['heading_title'] 	= $this->lang->line('heading_title');
		$data['text_form'] = $this->uri->segment(4) ? "arrear Edit" : "arrear Add";
		$data['text_image'] =$this->lang->line('text_image');
		$data['text_none'] = $this->lang->line('text_none');
		$data['text_clear'] = $this->lang->line('text_clear');
		$data['cancel'] = admin_url('pages');
		$data['button_save'] = $this->lang->line('button_save');
		$data['button_cancel'] = $this->lang->line('button_cancel');
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}
		if($this->uri->segment(4)){
			$id=$this->uri->segment(4);
			$data['edit']=true;
		}else if($this->input->get('id')){
			$id=$this->input->get('id');
			$data['edit']=false;
		}else{
			$id=0;
			$data['edit']=false;
		}			
		
		if ($id && ($this->input->server('REQUEST_METHOD') != 'POST')) {
			$emp_payroll = $this->arrear_model->getEmployeePayroll($id);
			$empoffice_info = $this->employee_model->getEmployeeOffice($id);
			$data['arrear_id']=$id;
			
		}else{
			$data['arrear_id']=0;
		}
		
		//printr($empoffice_info);
		
		//branch
		
		//printr($data['shifts']);
		foreach($this->arrear_model->getTableColumnsByName("employee_payroll") as $field) {
			
			if($this->input->post($field)) {
				$data[$field] = $this->input->post($field);
			} else if(isset($emp_payroll->{$field}) && $emp_payroll->{$field}) {
				$data[$field] = $emp_payroll->{$field};
			} else {
				$data[$field] = '';
			}
			
			if ($this->input->post("formula[formula_$field]")){
				$data['formula']["formula_$field"] = $this->input->post("formula[formula_$field]");
			} else {
				$formula='formula_'.$field;
				$data['formula']["formula_$field"] = $this->settings->{$formula};
			}
			
		}
		
		
		
		//printr($data['formula']);
		//exit;
		
		
		if (!empty($empoffice_info)) {
			$data['user_id'] = $empoffice_info->user_id;
		} else {
			$data['user_id'] = '';
		}
		
		if (!empty($emp_payroll)) {
			$data['suser_id'] = $emp_payroll->user_id;
		} else {
			$data['suser_id'] = '';
		}
		
		if ($this->input->post('paycode')) {
			$data['paycode'] = $this->input->post('paycode');
		} elseif (!empty($empoffice_info)) {
			$data['paycode'] = $empoffice_info->paycode;
		} else {
			$data['paycode'] = '';
		}
		
		if ($this->input->post('employee_name')) {
			$data['employee_name'] = $this->input->post('employee_name');
		} elseif (!empty($empoffice_info)) {
			$data['employee_name'] = $empoffice_info->employee_name;
		} else {
			$data['employee_name'] = '';
		}
		
		if ($this->input->post('card_no')) {
			$data['card_no'] = $this->input->post('card_no');
		} elseif (!empty($empoffice_info)) {
			$data['card_no'] = $empoffice_info->card_no;
		} else {
			$data['card_no'] = '';
		}
		
		if ($this->input->post('branch_name')) {
			$data['branch_name'] = $this->input->post('branch_name');
		} elseif (!empty($empoffice_info)) {
			$data['branch_name'] = $empoffice_info->branch_name;
		} else {
			$data['branch_name'] = '';
		}
		
		if (!empty($empoffice_info)) {
			$data['branch_id'] = $empoffice_info->branch_id;
		} else {
			$data['branch_id'] = '';
		}
		
		if ($this->input->post('department_name')) {
			$data['department_name'] = $this->input->post('department_name');
		} elseif (!empty($empoffice_info)) {
			$data['department_name'] = $empoffice_info->department_name;
		} else {
			$data['department_name'] = '';
		}
		
		if ($this->input->post('designation_name')) {
			$data['designation_name'] = $this->input->post('designation_name');
		} elseif (!empty($empoffice_info)) {
			$data['designation_name'] = $empoffice_info->designation_name;
		} else {
			$data['designation_name'] = '';
		}
		
		if ($this->input->post('arrear_mode')) {
			$data['arrear_mode'] = $this->input->post('arrear_mode');
		} elseif (!empty($emp_payroll)) {
			$data['arrear_mode'] = $emp_payroll->arrear_mode;
		} else {
			$data['arrear_mode'] = 'cash';
		}
		
		
		
		
		$this->load->model('formula/formula_model');
		$data['formulas']=$this->formula_model->getFormulas();
		$this->load->model('salary/field_model');
		$data['fields']=$this->field_model->getFields();
		$this->load->model('tax/tax_model');
		$data['taxs']=$this->tax_model->getTaxs();
		
		
		
		foreach($data['fields'] as $field){
			$name=$field->field;
			if($field->type=="earning"){
				if ($this->input->post("earning[$name]")) {
					$data['_earning'][$name] = $this->input->post("earning[$name]");
				} elseif (!empty($emp_payroll->earning)) {
					$earning=json_decode($emp_payroll->earning,true);
					//printr($earning);
					$data['_earning'][$name] = isset($earning[$name])?$earning[$name]:'';
				} else {
					$data['_earning'][$name] = '';
				}
			}else{
				if ($this->input->post("deduction[$name]")) {
					$data['_deduction'][$name] = $this->input->post("deduction[$name]");
				} elseif (!empty($emp_payroll->deduction)) {
					$deduction=json_decode($emp_payroll->deduction,true);
					$data['_deduction'][$name] = isset($deduction[$name])?$deduction[$name]:'';
				} else {
					$data['_deduction'][$name] = '';
				}
			}

			
			
			if ($this->input->post("formula[formula_$name]")){
				$data['formula']["formula_$name"] = $this->input->post("formula[formula_$name]");
			} else {
				$formula='formula_'.$name;
				$data['formula']["formula_$name"] = $this->settings->{$formula};
			}
			
		}
		
		foreach($data['taxs'] as $tax){
			$name=$tax->field;
			
			if ($this->input->post("deduction[$name]")) {
				$data['_deduction'][$name] = $this->input->post("deduction[$name]");
			} elseif (!empty($emp_payroll->deduction)) {
				$deduction=json_decode($emp_payroll->deduction,true);
				$data['_deduction'][$name] = isset($deduction[$name])?$deduction[$name]:'';
			} else {
				$data['_deduction'][$name] = '';
			}
			
			
			if ($this->input->post("formula[formula_$name]")){
				$data['formula']["formula_$name"] = $this->input->post("formula[formula_$name]");
			} else {
				$formula='formula_'.$name;
				$data['formula']["formula_$name"] = $this->settings->{$formula};
			}
			
		}
		
		
		$this->template->view('arrearForm',$data);
	}
	public function sheet(){
		$this->load->model('tax/tax_model');
		$this->load->model('attendance/attendance_model');
		$this->load->model('field_model');
		$this->lang->load('arrear');
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => 'Arrear Sheet',
			'href' => admin_url('arrear/sheet')
		);
		$data['heading_title'] 	= 'Arrear Sheet';
		
		$this->template->add_package(array('datepicker','table_export','select2','datatable'),true);
		
		if($this->input->get('branch_id')){
			$data['branch_id']=$this->input->get('branch_id');
		}else{
			$data['branch_id']='';
		}
		if($this->input->get('month')){
			$data['month']=$this->input->get('month');
		}else{
			$data['month']=date('m/Y');
		}
		
		$data['earning_fields']=$this->field_model->getFields(array('type'=>'earning'));
		$data['deduction_fields']=$this->field_model->getFields(array('type'=>'deduction'));
		//printr($data['deduction_fields']);
		$filter_data = array(
			'branch_id' => $data['branch_id'],
			'month'  	=> $data['month'],
		);
		
		//echo $data['month'];
		$results = $this->arrear_model->getArrearSheets($filter_data);
		if($data['branch_id']){
			$this->load->model('branch/branch_model');
			$branch_info = $this->branch_model->getBranch($data['branch_id']);
		}else{
			$branch_info=[];
		}
		//printr($results);
		//exit;
		$data['sheets']=[];
		foreach($results as $key=>$result){
			
			//all branch calculation
			$filter=$result;
			$envirnment=[];
			if(isset($branch_info->envirnment)){
				$envirnment = json_decode($branch_info->envirnment,true);
				if($envirnment['arrear_without_wo']=="no"){
					$total_days=(float)$result->month_days-(float)$result->weekly_off;
				}else{
					$total_days=(float)$result->month_days;
				}
			}else{
				$total_days=(float)$result->month_days;
			}
			//$total_cl=$this->
			if(isset($envirnment['arrear_cl'])){
				
			}
			$arrear_days=(float)$total_days -(float)$result->absent_days+(float)$result->arrear_days-(float)$result->deduction_days;
			
			$basic=$this->parseFormula("basic",$filter);
			$basic=($basic*(float)$arrear_days)/(float)$total_days;
			$filter->basic=$basic;
			//$arrear_days=(float)$result->present_days+((float)$result->el+(float)$result->cl+(float)$result->sl+(float)$result->cof);
			
			//echo $arrear_days;
			
			$perday_arrear=$basic/(float)$total_days;
			$perday_narrear=(float)$result->ctc/(float)$total_days;
			
			//sitewise calculation
			$sitedata=[];
			if($result->site_attendance_check){
				$sitesattendance=$this->arrear_model->getSiteAttendanceArrears($result->atttenace_id,$result->designation_id);
				//printr($sitesattendance);
				$spresent_days=$spwo=$sarrear_day=$sdeduction_day=$sot=0;
				
				foreach($sitesattendance as $site){
					$sarrear_days=$site['present_days']+$site['pwo']+$site['arrear_days']+$site['ot']-$site['deduction_days'];
					//echo $sarrear_days."-".$site['arrear']."<br>";
					$sperday_arrear=$site['arrear']/(float)$total_days;
					//echo $sperday_arrear."<br>";
					$sarrear=$sperday_arrear*$sarrear_days;
					$sitedata[]=array(
						'site_id'=>$site['site_id'],
						'site_name'=>$site['name'],
						'site_arrear'=>$sarrear
					);
				}
				//printr($sitedata);
			}
			//echo $perday_narrear."<br>";
			$ot_amount=$perday_narrear*((float)$result->holidays+(float)$result->ot+(float)$result->pwo);
			
			$hra=$this->parseFormula("hra",$filter);
			$conveyance=$this->parseFormula("conveyance",$filter);
			
			/*if($result->basic){
				$result->ctc=($result->basic/(float)$result->month_days)*(float)$result->present_days;
			}*/
			
			$earning=(array)json_decode($result->earning);
			$deduction=(array)json_decode($result->deduction);
			
			
			$total_earning=0;
			$earning_array=[];
			foreach($earning as $field=>$value){
				$$field=$earn=(float)$this->parseFormula($field,$filter,$value);
				$earning_array[$field]=round($earn);
				//echo $earn."<br>";
				$total_earning+=$earn;
				
			}
			
			//advance
			$this->load->model('advance/advance_model');
			
			$advances=$this->advance_model->getEmployeeAdvanceAdjustmentByMonth($result->user_id,$data['month']);
			$total_advance=0;
			foreach($advances as $adavance){
				$total_advance+=(float)$adavance->installment_amount;
			}
			
			//loan
			$this->load->model('loan/loan_model');
			
			$loans=$this->loan_model->getEmployeeLoanAdjustmentByMonth($result->user_id,$data['month']);
			$total_loan=0;
			foreach($loans as $loan){
				$total_loan+=(float)$loan->installment_amount+(float)$loan->interest_amount;
			}
			
			
			//$gross=$basic+$hra+$conveyance+$total_earning-$total_advance-$total_loan;
			$gross=$basic+$hra+$conveyance+$total_earning;
			
			$filter->gross=$gross;
			$earn_arrear=$perday_arrear*$arrear_days;
			
			$total_deduction=0;
			$deduction_array=[];
			foreach($deduction as $field=>$value){
				$deduc=(float)$this->parseFormula($field,$filter,$value);
				$deduction_array[$field]=round($deduc);
				$dkey=array_search($field, array_column($data['deduction_fields'], 'field'));
				if($data['deduction_fields'][$dkey]->status){
					$total_deduction+=$deduc;
				}
				
			}
			//printr($deduction);
			$ptc=isset($deduction['professional_tax'])?1:0;
			$pt=0;
			if($ptc){
				//echo $result->user_id;
				//echo $result->ctc;
				$pt=(float)$this->taxRate('professional_tax',$result->ctc);
				
			}
			
			//exit;
			$net_arrear=$gross-$total_deduction-$gratuity-$pt-$total_advance-$total_loan;
			
			$data['sheets'][$key]=array(
				'paycode'=>$result->paycode,
				'emp_name'=>$result->employee_name,
				'designation_name'=>$result->designation_name,
				'extra_duty'=>$result->arrear_days,
				'pwo'=>$result->pwo,
				'total_days'=>$result->month_days,
				'arrear_days'=>$arrear_days,
				'ctc'=>round($result->ctc),
				'basic'=>round($basic),
				'hra'=>round($hra),
				'conveyance'=>round($conveyance),
				'earning'=>$earning_array,
				'deduction'=>$deduction_array,
				'ot_amount'=>round($ot_amount),
				'gross'=>round($gross),
				'earn_arrear'=>round($earn_arrear),
				'pt'=>round($pt),
				'tds'=>round($result->tds),
				'advance'=>round($total_advance),
				'loan'=>round($total_loan),
				'net_arrear'=>round($net_arrear),
				'sitedata'=>$sitedata
			);
		}
		//printr($data['sheets']);
		//exit;
		$this->load->model('branch/branch_model');
		$data['branches']=$this->branch_model->getBranches();
		
		$this->template->view('arrearSheet',$data);
	}
	public function sheetwo(){
		$this->load->model('tax/tax_model');
		$this->load->model('attendance/attendance_model');
		$this->load->model('field_model');
		$this->lang->load('arrear');
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => 'Arrear Sheet',
			'href' => admin_url('arrear/sheet')
		);
		$data['heading_title'] 	= 'Arrear Sheet';
		
		$this->template->add_package(array('datepicker','table_export','select2','datatable'),true);
		
		if($this->input->get('branch_id')){
			$data['branch_id']=$this->input->get('branch_id');
		}else{
			$data['branch_id']='';
		}
		if($this->input->get('month')){
			$data['month']=$this->input->get('month');
		}else{
			$data['month']=date('m-Y');
		}
		
		$data['earning_fields']=$this->field_model->getFields(array('type'=>'earning'));
		$data['deduction_fields']=$this->field_model->getFields(array('type'=>'deduction'));
		//printr($data['deduction_fields']);
		$filter_data = array(
			'branch_id' => $data['branch_id'],
			'month'  	=> $data['month'],
		);
		
		//echo $data['month'];
		$results = $this->arrear_model->getArrearSheets($filter_data);
		
		
		$data['sheets']=[];
		foreach($results as $key=>$result){
			
			//all branch calculation
			$filter=$result;
			//if()
			$total_days=(float)$result->month_days;
			$arrear_days=(float)$total_days -(float)$result->absent_days+(float)$result->arrear_days-(float)$result->deduction_days;
			
			$basic=$this->parseFormula("basic",$filter);
			$basic=($basic*(float)$arrear_days)/(float)$total_days;
			$filter->basic=$basic;
			//$arrear_days=(float)$result->present_days+((float)$result->el+(float)$result->cl+(float)$result->sl+(float)$result->cof);
			
			//echo $arrear_days;
			
			$perday_arrear=$basic/(float)$total_days;
			$perday_narrear=(float)$result->ctc/(float)$total_days;
			
			//sitewise calculation
			$sitedata=[];
			if($result->site_attendance_check){
				$sitesattendance=$this->arrear_model->getSiteAttendanceArrears($result->atttenace_id,$result->designation_id);
				//printr($sitesattendance);
				$spresent_days=$spwo=$sarrear_day=$sdeduction_day=$sot=0;
				
				foreach($sitesattendance as $site){
					$sarrear_days=$site['present_days']+$site['pwo']+$site['arrear_days']+$site['ot']-$site['deduction_days'];
					//echo $sarrear_days."-".$site['arrear']."<br>";
					$sperday_arrear=$site['arrear']/(float)$total_days;
					//echo $sperday_arrear."<br>";
					$sarrear=$sperday_arrear*$sarrear_days;
					$sitedata[]=array(
						'site_id'=>$site['site_id'],
						'site_name'=>$site['name'],
						'site_arrear'=>$sarrear
					);
				}
				//printr($sitedata);
			}
			//echo $perday_narrear."<br>";
			$ot_amount=$perday_narrear*((float)$result->holidays+(float)$result->ot+(float)$result->pwo);
			
			$hra=$this->parseFormula("hra",$filter);
			$conveyance=$this->parseFormula("conveyance",$filter);
			
			/*if($result->basic){
				$result->ctc=($result->basic/(float)$result->month_days)*(float)$result->present_days;
			}*/
			
			$earning=(array)json_decode($result->earning);
			$deduction=(array)json_decode($result->deduction);
			
			
			$total_earning=0;
			$earning_array=[];
			foreach($earning as $field=>$value){
				$$field=$earn=(float)$this->parseFormula($field,$filter,$value);
				$earning_array[$field]=round($earn);
				//echo $earn."<br>";
				$total_earning+=$earn;
				
			}
			
			//advance
			$this->load->model('advance/advance_model');
			
			$adavances=$this->advance_model->getEmployeeAdvanceAdjustmentByMonth($result->user_id,$data['month']);
			$total_advance=0;
			foreach($adavances as $adavance){
				$total_advance+=(float)$adavance->installment_amount;
			}
			
			//loan
			$this->load->model('loan/loan_model');
			
			$loans=$this->loan_model->getEmployeeLoanAdjustmentByMonth($result->user_id,$data['month']);
			$total_loan=0;
			foreach($loans as $loan){
				$total_loan+=(float)$loan->installment_amount+(float)$loan->interest_amount;
			}
			
			
			$gross=$basic+$hra+$conveyance+$total_earning-$total_advance-$total_loan;
			
			$filter->gross=$gross;
			$earn_arrear=$perday_arrear*$arrear_days;
			
			$total_deduction=0;
			$deduction_array=[];
			foreach($deduction as $field=>$value){
				$deduc=(float)$this->parseFormula($field,$filter,$value);
				$deduction_array[$field]=round($deduc);
				$dkey=array_search($field, array_column($data['deduction_fields'], 'field'));
				if($data['deduction_fields'][$dkey]->status){
					$total_deduction+=$deduc;
				}
				
			}
			//printr($deduction);
			$ptc=isset($deduction['professional_tax'])?1:0;
			$pt=0;
			if($ptc){
				//echo $result->user_id;
				//echo $result->ctc;
				$pt=(float)$this->taxRate('professional_tax',$result->ctc);
				
			}
			
			//exit;
			$net_arrear=$gross-$total_deduction-$gratuity-$pt;
			
			$data['sheets'][$key]=array(
				'paycode'=>$result->paycode,
				'emp_name'=>$result->employee_name,
				'designation_name'=>$result->designation_name,
				'extra_duty'=>$result->arrear_days,
				'pwo'=>$result->pwo,
				'total_days'=>$result->month_days,
				'arrear_days'=>$arrear_days,
				'ctc'=>round($result->ctc),
				'basic'=>round($basic),
				'hra'=>round($hra),
				'conveyance'=>round($conveyance),
				'earning'=>$earning_array,
				'deduction'=>$deduction_array,
				'ot_amount'=>round($ot_amount),
				'gross'=>round($gross),
				'earn_arrear'=>round($earn_arrear),
				'pt'=>round($pt),
				'tds'=>round($result->tds),
				'advance'=>round($total_advance),
				'loan'=>round($total_loan),
				'net_arrear'=>round($net_arrear),
				'sitedata'=>$sitedata
			);
		}
		//printr($data['sheets']);
		//exit;
		$this->load->model('branch/branch_model');
		$data['branches']=$this->branch_model->getBranches();
		
		$this->template->view('arrearSheet',$data);
	}
	public function process(){
		$this->load->model('tax/tax_model');
		$this->load->model('attendance/attendance_model');
		$this->load->model('field_model');
		$this->lang->load('arrear');
		$this->load->model('payment/payment_model');
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => 'Arrear Sheet',
			'href' => admin_url('arrear/sheet')
		);
		
		
		$this->template->add_package(array('datepicker','table_export','select2','datatable'),true);
		
		if($this->input->get('branch_id')){
			$data['branch_id']=$this->input->get('branch_id');
		}else{
			$data['branch_id']='';
		}
		if($this->input->get('month')){
			$data['month']=$this->input->get('month');
		}else{
			$data['month']= date('m/Y');
		}
		$var = '01/'.$data['month'];
		$date = str_replace('/', '-', $var);
			
		$data['heading_title'] 	= 'Arrear Process for '. date('F, Y', strtotime($date));
		$filter_data = array(
			'branch' => $data['branch_id'],
            'month'  	=> $data['month']
		);
		$employees_arrears=$this->arrear_model->getArrearSheets($filter_data);
        $data['sheets']=[];
		foreach($employees_arrears as $key=>$employee){
			$payment_info=$this->payment_model->getPaymentByMonth($employee->user_id,$data['month']);
			$payroll=$this->getPayroll($employee,$data['month']);
			$data['sheets'][$key]=$payroll;
			$data['sheets'][$key]['payment_info']=$payment_info;
			
		}

		$this->load->model('branch/branch_model');
		$data['branches']=$this->branch_model->getBranches();

		$this->template->view('arrearProcess',$data);
	}
    public function payment(){
        $this->load->model('payment/payment_model');
        $this->template->set_meta_title($this->lang->line('heading_title'));
        $this->template->add_package(array('ckeditor','ckfinder','colorbox','select2','datepicker','datatable'),true);

        if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validatePaymentForm()){
            $user_id=$this->input->post('user_id');
            $month=$this->input->post('payment_month');

            $arrear=$this->getPayroll($user_id,$month);
            $arrear=array_merge($arrear,$_POST);
            
            $userid=$this->payment_model->addPayment($arrear);
            $this->session->set_flashdata('message', 'payment Saved Successfully.');
            redirect(ADMIN_PATH.'/payment');
        }
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->lang->line('heading_title'),
            'href' => admin_url('payment')
        );
        $data['heading_title'] 	= $this->lang->line('heading_title');
        $data['cancel'] = admin_url('pages');
        $data['button_save'] = $this->lang->line('button_save');
        $data['button_cancel'] = $this->lang->line('button_cancel');

        if($this->input->get('user_id')){
            $data['user_id']=$user_id=$this->input->get('user_id');
        }else{
            $data['user_id']=$user_id='';
        }

        if($this->input->get('month')){
            $data['month']=$month=$this->input->get('month');
        }else{
            $data['month']= $month=date('m/Y');
        }
        $var = '01/'.$data['month'];
        $date = str_replace('/', '-', $var);

        $data['mm']= date('F, Y', strtotime($date));

        if($this->input->get('payment_id')){
            $data['payment_id']=$payment_id=$this->input->get('payment_id');
        }else{
            $data['payment_id']= $payment_id='';
        }

        if(isset($this->error['warning'])){
            $data['error'] 	= $this->error['warning'];
        }


        if ($payment_id && ($this->input->server('REQUEST_METHOD') != 'POST')) {
            $payment_info = $this->payment_model->getPayment($payment_id);
        }


        if($user_id && $month){
            $emp_info = $this->employee_model->getEmployee($user_id);
            $date_added=date("Y-m-t", strtotime($date));
            $attendance_info=$this->attendance_model->getAttendanceByUserID($user_id,$date_added);
        }

        //printr($attendance_info);
        //exit;
        foreach($this->arrear_model->getTableColumnsByName("arrear_payment") as $field) {
            if($this->input->post($field)) {
                $data[$field] = $this->input->post($field);
            } else if(isset($payment_info->{$field}) && $payment_info->{$field}) {
                $data[$field] = $payment_info->{$field};
            } else {
                $data[$field] = '';
            }
        }

        foreach($this->attendance_model->getTableColumnsByName("attendance") as $field) {
            if(isset($attendance_info->{$field}) && $attendance_info->{$field}) {
                $data[$field] = $attendance_info->{$field};
            } else {
                $data[$field] = '';
            }
        }


        if (!empty($emp_info)) {
            $data['user_id'] = $user_id=$emp_info->user_id;
        } else {
            $data['user_id'] = $user_id='';
        }

        if (!empty($emp_info)) {
            $data['paycode'] = $emp_info->paycode;
        } else {
            $data['paycode'] = '';
        }

        if (!empty($emp_info)) {
            $data['employee_name'] = $emp_info->employee_name;
        } else {
            $data['employee_name'] = '';
        }

        if (!empty($emp_info)) {
            $data['branch_name'] = $emp_info->branch_name;
        } else {
            $data['branch_name'] = '';
        }

        if (!empty($emp_info)) {
            $data['department_name'] = $emp_info->department_name;
        } else {
            $data['department_name'] = '';
        }

        if (!empty($emp_info)) {
            $data['designation_name'] = $emp_info->designation_name;
        } else {
            $data['designation_name'] = '';
        }

        if (!empty($emp_info)) {
            $data['doj'] = $emp_info->doj;
        } else {
            $data['doj'] = '';
        }

        $data['arrear_days']=(float)$data['month_days'] -(float)$data['absent_days']+(float)$data['arrear_days']-(float)$data['deduction_days'];

        $filter_data = array(
            'month'  	=> $month,
            'user_id'   => $user_id
        );
        $result=$this->arrear_model->getArrearSheets($filter_data);

        $data['arrear']=$this->getPayroll($result,$month);
        //printr($debit);
        //printr($credit);
        //exit;
        $this->template->view('arrearPayment',$data);
    }
    public function slip(){
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->lang->line('heading_title'),
            'href' => admin_url('payment')
        );
        $data['heading_title'] 	= "PaySlip";

        $payment_id=$this->uri->segment(4);

        $data['payment_info']=$payment_info=$this->payment_model->getPayment($payment_id);
        //printr($data['payment_info']);
        //$payment_month="02/2022";
        $var = '01/'.$payment_info->payment_month;
        $date = str_replace('/', '-', $var);

        $data['heading_title'] 	= 'Arrear Process for '. date('F, Y', strtotime($date));

        $data['payment_month']=date('F, Y', strtotime($date));
        $data['employee']=$this->employee_model->getEmployee($payment_info->user_id);
        $data['aemployee']=$this->attendance_model->getSiteAttendancesByUserID($payment_info->user_id,$payment_info->payment_month);
        $data['allowances']=$this->payment_model->getAllowances($payment_id);
        $data['deductions']=$this->payment_model->getDeductions($payment_id);
       

        $this->template->view('arrearSlip',$data);
    }

    private function getArrear($user_id,$month){

        $result =$filter= $this->arrear_model->getArrearSheet($user_id,$month);

        $this->load->model('arrear/field_model');
        $earning_fields=$this->field_model->getFields(array('type'=>'earning'));
        $deduction_fields=$this->field_model->getFields(array('type'=>'deduction'));

        //branch info
        $this->load->model('branch/branch_model');
        $branch_info = $this->branch_model->getBranch($result->branch_id);

        //arrear info
        $envirnment=[];
        $credit=$debit=[];
        if(isset($branch_info->envirnment)){
            $envirnment = json_decode($branch_info->envirnment,true);
            if($envirnment['arrear_without_wo']=="no"){
                $total_days=(float)$result->month_days-(float)$result->weekly_off;
            }else{
                $total_days=(float)$result->month_days;
            }
        }else{
            $total_days=(float)$result->month_days;
        }
        //$total_cl=$this->
        if(isset($envirnment['arrear_cl'])){

        }
        $arrear_days=(float)$total_days -(float)$result->absent_days+(float)$result->arrear_days-(float)$result->deduction_days;
        $basic=$this->parseFormula("basic",$filter);
        $basic=($basic*(float)$arrear_days)/(float)$total_days;
        $filter->basic=$basic;
        $perday_arrear=$basic/(float)$total_days;
        $perday_narrear=(float)$result->ctc/(float)$total_days;

        //sitewise calculation
        $sitedata=[];
        if($result->site_attendance_check){
            $sitesattendance=$this->arrear_model->getSiteAttendanceArrears($result->atttenace_id,$result->designation_id);
            $spresent_days=$spwo=$sarrear_day=$sdeduction_day=$sot=0;

            foreach($sitesattendance as $site){
                $sarrear_days=$site['present_days']+$site['pwo']+$site['arrear_days']+$site['ot']-$site['deduction_days'];
                $sperday_arrear=$site['arrear']/(float)$total_days;
                $sarrear=$sperday_arrear*$sarrear_days;
                $sitedata[]=array(
                    'site_id'=>$site['site_id'],
                    'site_name'=>$site['name'],
                    'site_arrear'=>$sarrear
                );
                $credit[$site['name']]=$sarrear;
            }

        }
        $ot_amount=$credit['OT']=$perday_narrear*((float)$result->holidays+(float)$result->ot+(float)$result->pwo);
        $hra=$credit['HRA']=$this->parseFormula("hra",$filter);
        $conveyance=$credit['conveyance']=$this->parseFormula("conveyance",$filter);

        $earning=(array)json_decode($result->earning);
        $deduction=(array)json_decode($result->deduction);

        $total_earning=0;
        $earning_array=[];
        foreach($earning as $field=>$value){
            $ekey=array_search($field, array_column($earning_fields, 'field'));
            $$field=$earn=$credit[$earning_fields[$ekey]->name]=(float)$this->parseFormula($field,$filter,$value);
            $earning_array[$field]=round($earn);
            $total_earning+=$earn;
        }



        //advance
        $this->load->model('advance/advance_model');

        $adavances=$this->advance_model->getEmployeeAdvanceAdjustmentByMonth($result->user_id,$data['month']);
        $total_advance=0;
        foreach($adavances as $adavance){
            $total_advance+=(float)$adavance->installment_amount;
        }

        //loan
        $this->load->model('loan/loan_model');

        $loans=$this->loan_model->getEmployeeLoanAdjustmentByMonth($result->user_id,$data['month']);
        $total_loan=0;
        foreach($loans as $loan){
            $total_loan+=(float)$loan->installment_amount+(float)$loan->interest_amount;
        }


        $gross=$basic+$hra+$conveyance+$total_earning-$total_advance-$total_loan;

        $filter->gross=$gross;
        $earn_arrear=$perday_arrear*$arrear_days;

        $total_deduction=0;
        $deduction_array=[];
        foreach($deduction as $field=>$value){
            $dkey=array_search($field, array_column($deduction_fields, 'field'));

            $deduc=(float)$this->parseFormula($field,$filter,$value);
            $deduction_array[$field]=round($deduc);
            $dkey=array_search($field, array_column($data['deduction_fields'], 'field'));
            if($data['deduction_fields'][$dkey]->status){
                $debit[$deduction_fields[$dkey]->name]=round($deduc);
                $total_deduction+=$deduc;
            }
        }
        //printr($deduction);
        $ptc=isset($deduction['professional_tax'])?1:0;
        $pt=0;
        if($ptc){
            $pt=$debit['professional_tax']=(float)$this->taxRate('professional_tax',$result->ctc);
        }

        $tdeduction=$total_deduction+$total_advance+$total_loan+$gratuity+$pt;

        //exit;
        $net_arrear=$gross-$total_deduction-$gratuity-$pt;

        $arrear=array(
            'paycode'=>$result->paycode,
            'emp_name'=>$result->employee_name,
            'designation_name'=>$result->designation_name,
            'extra_duty'=>$result->arrear_days,
            'pwo'=>$result->pwo,
            'total_days'=>$result->month_days,
            'arrear_days'=>$arrear_days,
            'ctc'=>round($result->ctc),
            'basic'=>round($basic),
            'hra'=>round($hra),
            'conveyance'=>round($conveyance),
            'earning'=>$earning_array,
            'deduction'=>$deduction_array,
            'ot_amount'=>round($ot_amount),
            'gross'=>round($gross),
            'earn_arrear'=>round($earn_arrear),
            'pt'=>round($pt),
            'tds'=>round($result->tds),
            'advances'=>$advances,
            'advance'=>round($total_advance),
            'loans'=>$loans,
            'loan'=>round($total_loan),
            'tdeduction'=>round($tdeduction),
            'net_arrear'=>round($net_arrear),
            'sitedata'=>$sitedata,
            'debit'=>$debit,
            'credit'=>$credit
        );

        return $arrear;
    }
    public function getPayroll($result,$month){
        if(!is_object($result)){
            $filter_data = array(
                'user_id' => $result,
                'month'  	=> $month
            );
            $result=$this->arrear_model->getArrearSheets($filter_data);

        }
		
	    $filter=$result;
		
        $this->load->model('arrear/field_model');
        $earning_fields=$this->field_model->getFields(array('type'=>'earning'));
        $deduction_fields=$this->field_model->getFields(array('type'=>'deduction'));

        //branch info
        $this->load->model('branch/branch_model');
        $branch_info = $this->branch_model->getBranch($result->branch_id);

        //arrear info
        $envirnment=[];
        $credit=$debit=[];
        if(isset($branch_info->envirnment)){
            $envirnment = json_decode($branch_info->envirnment,true);
            //printr($envirnment);
            if(isset($envirnment['arrear_without_wo']) && $envirnment['arrear_without_wo']=="no"){
                $total_days=(float)$result->month_days-(float)$result->weekly_off;
            }else{
                $total_days=(float)$result->month_days;
            }
        }else{
            $total_days=(float)$result->month_days;
        }
		
        //$total_cl=$this->
        if(isset($envirnment['arrear_cl'])){

        }
        $arrear_days=(float)$total_days -(float)$result->absent_days+(float)$result->arrear_days-(float)$result->deduction_days;
        $basic=$this->parseFormula("basic",$filter,$result->basic);
		//echo $basic;
		//echo $total_days;
        $basic=$total_days?($basic*(float)$arrear_days)/(float)$total_days:0;
      //echo $basic;
	  //exit;
		$filter->basic=$basic;
        $perday_arrear=$total_days?$basic/(float)$total_days:0;
        $perday_narrear=$arrear_days?(float)$result->ctc/(float)$arrear_days:0;
		
        //sitewise calculation
        $sitedata=[];
        if($result->site_attendance_check){
            $sitesattendance=$this->arrear_model->getSiteAttendanceArrears($result->atttenace_id,$result->designation_id);
            $spresent_days=$spwo=$sarrear_day=$sdeduction_day=$sot=0;

            foreach($sitesattendance as $site){
                $sarrear_days=$site['present_days']+$site['pwo']+$site['arrear_days']+$site['ot']-$site['deduction_days'];
                $sperday_arrear=$site['arrear']/(float)$total_days;
                $sarrear=$sperday_arrear*$sarrear_days;
                $sitedata[]=array(
                    'site_id'=>$site['site_id'],
                    'site_name'=>$site['name'],
                    'site_arrear'=>$sarrear
                );
                $credit[$site['name']]=$sarrear;
            }
        }
        $ot_amount=$credit['OT']=$perday_narrear*((float)$result->holidays+(float)$result->ot+(float)$result->pwo);
        $hra=$credit['HRA']=$this->parseFormula("hra",$filter,$result->hra);
        $conveyance=$credit['conveyance']=$this->parseFormula("conveyance",$filter,$result->conveyance);

        $earning=(array)json_decode($result->earning);
        $deduction=(array)json_decode($result->deduction);

        $total_earning=0;
        $earning_array=[];
        foreach($earning as $field=>$value){
            $ekey=array_search($field, array_column($earning_fields, 'field'));
            $$field=$earn=$credit[$earning_fields[$ekey]->name]=(float)$this->parseFormula($field,$filter,$value);
            $earning_array[$field]=round($earn);
            $total_earning+=$earn;
        }



        //advance
        $this->load->model('advance/advance_model');

        $advances=$this->advance_model->getEmployeeAdvanceAdjustmentByMonth($result->user_id,$month);
        $total_advance=0;
        foreach($advances as $adavance){
            $total_advance+=(float)$adavance->installment_amount;
        }

        //loan
        $this->load->model('loan/loan_model');

        $loans=$this->loan_model->getEmployeeLoanAdjustmentByMonth($result->user_id,$month);
        $total_loan=0;
        foreach($loans as $loan){
            $total_loan+=(float)$loan->installment_amount+(float)$loan->interest_amount;
        }


        $gross=$basic+$hra+$conveyance+$total_earning;

        $filter->gross=$gross;
        $earn_arrear=$perday_arrear*$arrear_days;

        $total_deduction=0;
        $deduction_array=[];
        $gratuity=0;
        foreach($deduction as $field=>$value){
            //$dkey=array_search($field, array_column($deduction_fields, 'field'));

            $deduc=(float)$this->parseFormula($field,$filter,$value);
            $deduction_array[$field]=round($deduc);
            $dkey=array_search($field, array_column($deduction_fields, 'field'));
            if($dkey!=false && $deduction_fields[$dkey]->status){
                $$field=$debit[$deduction_fields[$dkey]->name]=round($deduc);
                $total_deduction+=$deduc;
            }
        }
        //printr($deduction);
        $ptc=isset($deduction['professional_tax'])?1:0;
        $pt=0;
        if($ptc){
            $pt=$debit['professional_tax']=(float)$this->taxRate('professional_tax',$result->ctc);
        }

        $tdeduction=$total_deduction+$total_advance+$total_loan+$gratuity+$pt;

        //exit;
        $net_arrear=$gross-$total_deduction-$gratuity-$pt-$total_advance-$total_loan;

        $arrear=array(
			'month'=>$month,
			'branch_id'=>$result->branch_id,
            'user_id'=>$result->user_id,
            'paycode'=>$result->paycode,
            'emp_name'=>$result->employee_name,
            'designation_name'=>$result->designation_name,
            'extra_duty'=>$result->arrear_days,
            'pwo'=>$result->pwo,
            'total_days'=>$result->month_days,
            'arrear_days'=>$arrear_days,
            'ctc'=>round($result->ctc),
            'basic'=>round($basic),
            'hra'=>round($hra),
            'conveyance'=>round($conveyance),
            'earning'=>$earning_array,
            'deduction'=>$deduction_array,
            'ot_amount'=>round($ot_amount),
            'gross'=>round($gross),
            'earn_arrear'=>round($earn_arrear),
            'pt'=>round($pt),
            'tds'=>$result->tds?round($result->tds):0,
            'advances'=>$advances,
            'advance'=>round($total_advance),
            'loans'=>$loans,
            'loan'=>round($total_loan),
            'tdeduction'=>round($tdeduction),
            'net_arrear'=>round($net_arrear),
            'sitedata'=>$sitedata,
            'debit'=>$debit,
            'credit'=>$credit
        );
		
        return $arrear;
    }
    private function parseFormula($name,$filter,$value=0){
		//echo $name;
		$this->load->model('formula/formula_model');
		$this->user=$filter;
		
		$formula='formula_'.$name;
		$formula_id = $this->settings->{$formula};
		$formularow=$this->formula_model->getFormula($formula_id);
		//printr($formularow);
		if($formularow){
			$formula=$formularow->formula;
			
			$calculator = new MathExecutor();
			//echo $name;
			
			$calculator->setVarNotFoundHandler(
				function ($varName) {
					//echo $name;
					$name=strtolower($varName);
					return (float)$this->user->{$name} ;
					/*if ($varName == 'CTC') {
						return (float)$this->user->ctc;
					}else if ($varName == 'GROSS') {
						return (float)$this->user->gross;
					}*/
					//return "0";
				}
			);
			return $calculator->execute($formula);
		}else{
			return (float)$value;
		}
	}
	private function parseFormula_old($name,$filter){
		$this->load->model('formula/formula_model');
		$this->user=$filter;
		//printr($this->value);
		$formula='formula_'.$name;
		$formula_id = $this->settings->{$formula};
		$formularow=$this->formula_model->getFormula($formula_id);
		if($formularow){
			$formula=$formularow->formula;
			$evaluator = new \Matex\Evaluator();
			/*$evaluator->variables = [
				'a' => 1
			];*/
			//echo $formula;
			$evaluator->onVariable = [$this, 'doVariable'];
			//if($formula)
			//echo $formula;
			//echo $evaluator->execute($formula);
			if($formula==$evaluator->execute($formula))	{
				$result=$evaluator->execute($formula);
			}else{
				$result=round($evaluator->execute($formula),2);
			}
		}else{
			$result=$this->user->{$name};
		}
		return $result;
	}
	public function doVariable($name, &$value) {
		
		switch ($name) {
			case 'ctc':
			case 'basic':
				$value = (float)$this->user->{$name};
				//echo $value;
				break;
			case 'esi_emp':
			case 'esi_empr':
			case 'pf_emp':
			case 'pf_empr':
			case 'gross':
				$value=1000;
				break;
			case 'professional_tax':
				$value = strtoupper($name);
				break;
		}
	}
	private function taxRate($field,$total=0){
		$this->load->model('tax/tax_model');
		$rates=$this->tax_model->getTaxRateByField($field);
		//echo $total;
		
		
		
		$rate=0;
		foreach($rates as $_rate){
			if($total > $_rate->to_amount && $total <= $_rate->from_amount){
				
				if($_rate->type=="P"){
					$rate=$total*$_rate->rate/100;
				}else{
					$rate=$_rate->rate;
				}
				break;
			}
		}
		
		//echo $rate;
		return $rate;
	}		
	protected function validateForm() {
		$user_id=$this->uri->segment(4);
		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
      	$regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
      	$regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor 
		$rules=array(
			'paycode' => array(
				'field' => 'paycode', 
				'label' => 'Paycode', 
				'rules' => 'trim|required|max_length[100]'
			),
			/*'basic' => array(
				'field' => 'basic', 
				'label' => 'basic', 
				'rules' => 'trim|required|max_length[100]'
			),
			'ctc' => array(
				'field' => 'ctc', 
				'label' => 'ctc', 
				'rules' => 'trim|required|max_length[100]'
			),*/
			
		);
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() == TRUE)
		{
			return true;
    	}
		else
		{
			$this->error['warning']=$this->lang->line('error_warning');
			return false;
    	}
		return !$this->error;
	}
    protected function validatePaymentForm() {
        $user_id=$this->uri->segment(4);
        $regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
        $regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
        $regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor
        $rules=array(
            'payment_amount' => array(
                'field' => 'payment_amount',
                'label' => 'Payment Amount',
                'rules' => 'trim|required|max_length[100]'
            ),
            'payment_type' => array(
                'field' => 'payment_type',
                'label' => 'Payment Type',
                'rules' => 'trim|required|max_length[100]'
            ),

        );
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == TRUE)
        {
            return true;
        }
        else
        {
            $this->error['warning']=$this->lang->line('error_warning');
            return false;
        }
        return !$this->error;
    }

    public function email_check($email, $user_id=''){
		$arrear = $this->arrear_model->getEmployeeByEmail($email);
      	if (!empty($arrear) && $arrear->id != $user_id){
			$this->form_validation->set_message('email_check', "This email address is already in use.");
         	return FALSE;
		}else{
         	return TRUE;
      	}
   	}
	public function username_check($username, $user_id=''){
      $arrear = $this->arrear_model->getEmployeeByUsername($username);
      if (!empty($arrear) && $arrear->id != $user_id){
            $this->form_validation->set_message('username_check', "This {field} provided is already in use.");
            return FALSE;
		}else{
         return TRUE;
      }
   }
	public function autocomplete(){
		$json = array();
		if (is_ajax()){
			$filter_data = array(
				'filter_search'  => $this->input->get('searchTerm'),
				'start' 		 => 0,
				'limit' 		 => 5
			);
			$filteredData = $this->arrear_model->getEmployees($filter_data);
			//printr($filteredData);
			foreach($filteredData as $result){
				$json[] = array(
					'id' => $result->id,
					'text'    => $result->paycode,
					'empname'    => $result->arrear_name,
					'card_no' => $result->card_no,
					'department_name'     => $result->department_name,
				);
			}
			echo json_encode($json);
		}else{
         	return show_404();
      	}
	}
	
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */