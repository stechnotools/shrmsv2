<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mpunch extends Admin_Controller {
	private $error = array();
	
	public function __construct(){
		parent::__construct();
		$this->load->model('mpunch_model');	
		$this->load->model('employee/employee_model');		
	}
	
	public function index(){
		$this->lang->load('mpunch');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		$this->template->add_package(array('select2'),true);
		
		$this->getList();  
	}
	
	public function search() {
		
		$requestData= $_REQUEST;
		$totalData = $this->mpunch_model->getTotalMpunchs();
		
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->mpunch_model->getTotalMpunchs($filter_data);
			
		$filteredData = $this->mpunch_model->getMpunchs($filter_data);
		//printr($filteredData);
		
		$datatable=array();
		foreach($filteredData as $result) {
			
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('mpunch/edit/'.$result->id).'"><i class="fa fa-pencil"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('mpunch/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';
			$action .= '</div>';
			$outtime=$late_arrrival=$working_hr=$late_departure='';
			if($result->total%2==0){
				$outtime=$result->outtime;
			}
			$early_arival_format=date("+i", strtotime($result->late_arrival)).' minutes';
			$shift_start_time=date("H:i:s", strtotime($early_arival_format, strtotime($result->shift_start_time)));
			if(strtotime($shift_start_time) < strtotime($result->intime) ){
				$datetime1 = date_create($result->intime);
				$datetime2 = date_create($result->shift_start_time);
				$interval = date_diff($datetime1, $datetime2);
				$late_arrrival=$interval->format('%H:%I:%S');
				//$late_arrrival=date('H:i:s',(strtotime($result->intime)-strtotime($shift_start_time)));
			}
			$early_departure_format=date("+i", strtotime($result->early_departure)).' minutes';
			$shift_end_time=date("H:i:s", strtotime($early_departure_format, strtotime($result->shift_end_time)));
			
			if($outtime && strtotime($shift_end_time) > strtotime($outtime) ){
				$datetime1 = date_create($outtime);
				$datetime2 = date_create($result->shift_end_time);
				$interval = date_diff($datetime1, $datetime2);
				$late_departure=$interval->format('%H:%I:%S');
				//$late_arrrival=date('H:i:s',(strtotime($result->intime)-strtotime($shift_start_time)));
			}
			if($outtime){
				$datetime1 = date_create($outtime);
				$datetime2 = date_create($result->intime);
				$interval = date_diff($datetime1, $datetime2);
				$working_hr=$interval->format('%H:%I:%S');
			}
			$datatable[]=array(
				$result->punch_date,
				$result->paycode,
				$result->shift_name,
				$result->intime,
				$outtime,
				$working_hr,
				$late_arrrival,
				$late_departure,
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
		$json=array();
		$this->lang->load('mpunch');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST'){	
			
			if(!$this->validateForm()){
         		if(isset($this->error['warning'])){
					$json['message'] 	= $this->error['warning'];
					$json['type']='error';
				}
				if(isset($this->error['errors'])){
					$json['errors'] 	= $this->error['errors'];
				}
         	}
			if(!$json){
				$punch_id=$this->savePunch();
				if($punch_id){
					$this->savePunchHistory($punch_id);
				}
				$json=array(
					"message"=>"Punch Updated Successfully.",
				);
			}
			echo json_encode($json);
			exit;
		}
		$this->getForm();
	}
	
	public function edit(){
		$json=array();
		$this->lang->load('mpunch');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST'){	
			if(!$this->validateForm()){
         		if(isset($this->error['warning'])){
					$json['message'] 	= $this->error['warning'];
					$json['type']='error';
				}
				if(isset($this->error['errors'])){
					$json['errors'] 	= $this->error['errors'];
				}
         	}
			if(!$json){
				$punch_id=$this->uri->segment(4);
				if($punch_id){
					$this->savePunchHistory($punch_id);
				}
				$json=array(
					"message"=>"Punch Updated Successfully.",
				);
			}
			echo json_encode($json);
			exit;
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->input->post('selected')){
         $selected = $this->input->post('selected');
      }else{
         $selected = (array) $this->uri->segment(4);
       }
		$this->mpunch_model->deleteMpunch($selected);
		$this->session->set_flashdata('message', 'Mpunch deleted Successfully.');
		redirect(ADMIN_PATH.'/mpunch');
	}
	
	public function hdelete(){
		
        $selected = (array) $this->uri->segment(4);
       
		$this->mpunch_model->deletePunchHistory($selected);
		$this->session->set_flashdata('message', 'punch history deleted Successfully.');
		redirect($_SERVER['HTTP_REFERER']);
	}

	public function savePunch(){      
		$user_id=$this->input->post('user_id');                             
		$shiftdata=$this->employee_model->getEmployeeShift($user_id);
		$officedata=$this->employee_model->getEmployeeOffice($user_id);
		
		$timedata=$this->employee_model->getEmployeeTime($user_id);
		$punch_date=date("Y-m-d",strtotime($this->input->post('punch_date')));
		
		//check shift id
		if($shiftdata->shift_type=="r"){
			$apply_shift=false;
			$shift_pattern=$shiftdata->shift_pattern;
			$shift_change=$shiftdata->shift_change;
			$shift_apply_date=$shiftdata->shift_apply_date;
			if($shift_apply_date){
				$dayDifference = (new DateTime())->diff((new DateTime($shift_apply_date))->modify('+1 day'))->format('%a');
				if($dayDifference<=0){
					$apply_shift=true;
				}
			}
			if($shift_pattern && $apply_shift){
				$shiftids=explode("-",$shift_pattern);
				$countshift=$this->employee_model->getShiftCount($user_id,$shift_change);
				if($countshift->shift_count<=$shift_change){
					$shift_id=$countshift->shift_id;
				}else{
					$shift_id=$this->getNextId($shiftids,$countshift->shift_id);
				}
			}
		}

		if(!isset($shift_id)){
			$shift_id=$shiftdata->shift_id;
		}
		$punch_data=array(
			'user_id'=>$user_id,
			'employee_name'=>$officedata->employee_name,
			'paycode'=>$officedata->paycode,
			'card_no'=>$officedata->card_no,
			'branch_id'=>$officedata->branch_id,
			'branch_name'=>$officedata->branch_name,
			'department_id'=>$officedata->department_id,
			'department_name'=>$officedata->department_name,
			'category_id'=>$officedata->category_id,
			'category_name'=>$officedata->category_name,
			'section_id'=>$officedata->section_id,
			'section_name'=>$officedata->section_name,
			'grade_id'=>$officedata->grade_id,
			'grade_name'=>$officedata->grade_name,
			'designation_id'=>$officedata->designation_id,
			'designation_name'=>$officedata->designation_name,
			'shift_type'=>$shiftdata->shift_type,
			'shift_id'=>$shift_id,
			'shift_name'=>$shiftdata->shift_name,
			'shift_pattern'=>$shiftdata->shift_pattern,
			'shift_start_time'=>$shiftdata->shift_start_time,
			'shift_end_time'=>$shiftdata->shift_end_time,
			'auto_shift'=>$shiftdata->run_auto_shift,
			'first_week'=>$shiftdata->first_week,
			'second_week'=>$shiftdata->second_week,
			'late_arrival'=>$timedata->perm_late,
			'early_departure'=>$timedata->perm_early,
			'total_punch'=>$timedata->punches,
			'punch_date'=>$punch_date,
			'status'=>1
		);
		
		$singledata=$this->mpunch_model->as_array()->get_by(array('punch_date'=>$punch_date,'user_id'=>$this->input->post('user_id')));
		if(empty($singledata)){
			$punch_id=$this->mpunch_model->addMpunch($punch_data);
		}else{
			$punch_id=$singledata['id'];
		}
		
		return $punch_id;
		
		
			
			
	}
	protected function getNextId($pattern,$shift_id){
		
		$key_count = count($pattern); // Count the number of elements in the array

		$given_index = array_search($shift_id, $pattern); // Find the index of the given key

		$next_index = ($given_index + 1) % $key_count; // Calculate the index of the next key in a cyclic manner
		$next_id = $pattern[$next_index]; // Retrieve the next key

		return $next_id; // Output: 3

	}
	public function savePunchHistory($punch_id){
		$user_id=$this->input->post('user_id'); 
		$punch_time=$this->input->post('punch_time') ;    
		$officedata=$this->employee_model->getEmployeeOffice($this->input->post('user_id'));
		
		$timedata=$this->employee_model->getEmployeeTime($this->input->post('user_id'));
		$punch_date=date("Y-m-d",strtotime($this->input->post('punch_date')));
		$no_of_punch=$this->mpunch_model->getTotalPunchByPunchId($punch_id);
		if($timedata->punches==-1 || $timedata->punches==null){
			$punch_status=1;
		}else if($no_of_punch<=$timedata->punches){
			$punch_status=1;
		}else{
			$punch_status=0;
		}
		
		$punch_history=array(
			'punch_id'=>$punch_id,
			'user_id'=>$user_id,
			'card_no'=>$officedata->card_no,
			'punch_date'=>$punch_date,
			'punch_time'=>$punch_time,
			'punch_type'=>'M',
			'no_of_punch'=>$no_of_punch,
			'punch_status'=>$punch_status
		);
		
		$punch_id=$this->mpunch_model->savePunchHistory($punch_history);
		
	}
	
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('heading_title'),
			'href' => admin_url('mpunch')
		);
		
		$this->template->add_package(array('datatable'),true);
      

		$data['add'] = admin_url('mpunch/add');
		$data['delete'] = admin_url('mpunch/delete');
		$data['datatable_url'] = admin_url('mpunch/search');

		$data['heading_title'] = $this->lang->line('heading_title');
		
		$data['text_list'] = $this->lang->line('text_list');
		$data['text_no_results'] = $this->lang->line('text_no_results');
		$data['text_confirm'] = $this->lang->line('text_confirm');

		$data['column_mpunchname'] = $this->lang->line('column_mpunchname');
		$data['column_status'] = $this->lang->line('column_status');
		$data['column_date_added'] = $this->lang->line('column_date_added');
		$data['column_action'] = $this->lang->line('column_action');

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

		$this->template->view('mpunch', $data);
	}
	
	protected function getForm(){
		
		$data = $this->lang->load('mpunch');
		$this->template->add_package(array('datatable','datepicker','timepicker','select2'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('heading_title'),
			'href' => admin_url('mpunch')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('text_add'),
			'href' => admin_url('mpunch/add')
		);

		$_SESSION['isLoggedIn'] = true;
		
		$data['heading_title'] 	= $this->lang->line('heading_title');
		
		$data['text_form'] = $this->uri->segment(4) ? $this->lang->line('text_edit') : $this->lang->line('text_add');
		$data['button_save'] = $this->lang->line('button_save');
		$data['button_cancel'] = $this->lang->line('button_cancel');
		$data['cancel'] = admin_url('mpunch');
		$data['save'] = admin_url('mpunch/save');
		
		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}
		$user_id=0;
		if($this->uri->segment(4)){
			$id=$this->uri->segment(4);
			$data['edit']=true;
		}else if($this->input->get('id')){
			$user_id=$this->input->get('id');
			$id=0;
			$data['edit']=false;
		}else{
			$id=0;
			$data['edit']=false;
		}			
		
		if ($id && ($this->input->server('REQUEST_METHOD') != 'POST')) {
			$mpunch_info = $this->mpunch_model->getMpunch($id);
			$emp_info = $this->employee_model->getEmployeeOffice($mpunch_info->user_id);
			$data['punch_user_id']=$mpunch_info->user_id;
		}else{
			$emp_info = $this->employee_model->getEmployeeOffice($user_id);
			$data['punch_user_id']=$user_id;
		}

		//printr($emp_info);
		
		/*if ($this->uri->segment(5) && ($this->input->server('REQUEST_METHOD') != 'POST')) {
			$emp_info = $this->employee_model->getEmployee($this->uri->segment(5));
		}*/
		//printr($mpunch_info);
		foreach($this->mpunch_model->getTableColumns() as $field) {
			if($this->input->post($field)) {
				$data[$field] = $this->input->post($field);
			} else if(isset($mpunch_info->{$field}) && $mpunch_info->{$field}) {
				$data[$field] = $mpunch_info->{$field};
			} else {
				$data[$field] = '';
			}
		}
		
		if(isset($mpunch_info->punch_date)) {
			$data['punch_date'] = $mpunch_info->punch_date;
		} else {
			$data['punch_date'] = date("d-m-Y");
		}
		
		if(isset($mpunch_info->punch_time)) {
			$data['punch_time'] = $mpunch_info->punch_time;
		} else {
			$data['punch_time'] = date("H:m:s");
		}
	
		if ($this->input->post('paycode')) {
			$data['paycode'] = $this->input->post('paycode');
		} elseif (!empty($emp_info)) {
			$data['paycode'] = $emp_info->paycode;
		} else {
			$data['paycode'] = '';
		}
		
		if ($this->input->post('employee_name')) {
			$data['employee_name'] = $this->input->post('employee_name');
		} elseif (!empty($emp_info)) {
			$data['employee_name'] = $emp_info->employee_name;
		} else {
			$data['employee_name'] = '';
		}
		
		if ($this->input->post('card_no')) {
			$data['card_no'] = $this->input->post('card_no');
		} elseif (!empty($emp_info)) {
			$data['card_no'] = $emp_info->card_no;
		} else {
			$data['card_no'] = '';
		}
		
		if ($this->input->post('branch_name')) {
			$data['branch_name'] = $this->input->post('branch_name');
		} elseif (!empty($emp_info)) {
			$data['branch_name'] = $emp_info->branch_name;
		} else {
			$data['branch_name'] = '';
		}
		
		if (!empty($emp_info)) {
			$data['branch_id'] = $emp_info->branch_id;
		} else {
			$data['branch_id'] = '';
		}
		
		if ($this->input->post('department_name')) {
			$data['department_name'] = $this->input->post('department_name');
		} elseif (!empty($emp_info)) {
			$data['department_name'] = $emp_info->department_name;
		} else {
			$data['department_name'] = '';
		}
		
		if ($this->input->post('designation_name')) {
			$data['designation_name'] = $this->input->post('designation_name');
		} elseif (!empty($emp_info)) {
			$data['designation_name'] = $emp_info->designation_name;
		} else {
			$data['designation_name'] = '';
		}
		
		
		
		$this->template->view('mpunchForm',$data);
	}
	
	public function history(){
		$data=array();
		$user_id=$this->input->get('user_id');
		$punch_date=$this->input->get('punch_date');
		$data['punches']= $this->mpunch_model->getPunchHistory($user_id,$punch_date);
		//printr($data['punches']);
		$this->load->view('mpunch_history',$data);
	}

	protected function validateForm() {
		$mpunch_id=$this->uri->segment(4);
		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
      	$regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
      	$regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor 
		$rules=array(
			'user_id' => array(
				'field' => 'user_id', 
				'label' => 'Paycode', 
				'rules' => 'trim|required|max_length[100]'
			),
			
			'punch_date' => array(
				'field' => 'punch_date', 
				'label' => 'Punch Date', 
				'rules' => "trim|required"
			),
			'punch_time' => array(
				'field' => 'punch_time', 
				'label' => 'Punch Time', 
				'rules' => "trim|required|callback_validate_time"
			),
		);
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() == TRUE)
		{
			return true;
    	}
		else
		{
			//echo validation_errors();
			$this->error['warning']=$this->lang->line('error_warning');
			$this->error['errors'] = $this->form_validation->error_array();
			return false;
    	}
		return !$this->error;
	}
	
	public function validate_time($str){
		if (strrchr($str,":")) {
			list($hh, $mm, $ss) = explode(':', $str);
			if (!is_numeric($hh) || !is_numeric($mm) || !is_numeric($ss)){
				$this->form_validation->set_message('validate_time', "Invalid Time Format");
				return FALSE;
			}elseif ((int) $hh <= 0 ){
				$this->form_validation->set_message('validate_time', "Invalid Time Format");
				return FALSE;
			}
			elseif ((int) $hh > 24 || (int) $mm > 59 || (int) $ss > 59){
				$this->form_validation->set_message('validate_time', "Invalid Time Format");
				return FALSE;
			}elseif (mktime((int) $hh, (int) $mm, (int) $ss) === FALSE){
				$this->form_validation->set_message('validate_time', "Invalid Time Format");
				return FALSE;
			}
			return TRUE;
		}else{
			return FALSE;
		}   
	}
	
	public function upload(){
		$json=array();
		$this->load->library('upload');
		$this->load->model('branch/branch_model');
		$this->load->model('department/department_model');
		$this->load->model('category/category_model');
		$this->load->model('section/section_model');
		$this->load->model('grade/grade_model');
		$this->load->model('designation/designation_model');
		$configmain = array(
			'allowed_types' => 'csv|xlsx|xls', 
			'upload_path' => FCPATH . 'storage/uploads/files', 
			'max_size' => 2097152,
			'overwrite' => True, 
			'file_name' =>  'attendance'
		);
		$this->upload->initialize($configmain); 
		if($this->upload->do_upload('bpunch')){  
			$data = array('upload_data' => $this->upload->data());					
			$file=$data['upload_data']['full_path']; 
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			$spreadsheet = $reader->load($file);
			$sheetCount = $spreadsheet->getSheetCount();
			//echo $sheetCount;
			$empdata=$employeeofficedata=$employeedata=$employeshiftdata=$employetimedata=array();
			for ($i = 0; $i < $sheetCount; $i++) {
				$sheet = $spreadsheet->getSheet($i);
				$sheetData = $sheet->toArray(null, true, true, true);
				array_shift($sheetData);
				foreach($sheetData as $sheet){
					if($i==0){
						$branch_name=trim($sheet['G']);
						if($branch_name){
							$com=$this->branch_model->getBranchByName($branch_name);
							if($com){
								$branch_id=$com->id;
							}else{
								$branch_data=array(
									'name'=>$branch_name,
									'code'=>'comp_'.time()
								);
								$branch_id=$this->branch_model->insert($branch_data);
							}
						}
						$department_name=trim($sheet['H']);
						if($department_name){
							$dep=$this->department_model->getDepartmentByName($department_name);
							if($dep){
								$department_id=$dep->id;
							}else{
								$department_data=array(
									'name'=>$department_name,
									'code'=>'dept_'.time()
								);
								$department_id=$this->department_model->insert($department_data);
							}
						}
						$category_name=trim($sheet['I']);
						if($category_name){
							$cat=$this->category_model->getCategoryByName($category_name);
							if($cat){
								$category_id=$cat->id;
							}else{
								$category_data=array(
									'name'=>$category_name,
									'code'=>'cat_'.time()
								);
								$category_id=$this->category_model->insert($category_data);
							}
						}
						$section=trim($sheet['J']);
						if($section){
							$sec=$this->section_model->getSectionByName($section);
							if($sec){
								$section=$sec->id;
							}else{
								$section_data=array(
									'name'=>$section,
									'code'=>'sec_'.time()
								);
								$section=$this->section_model->insert($section_data);
							}
						}
						$grade=trim($sheet['K']);
						if($grade){
							$grd=$this->grade_model->getGradeByName($grade);
							if($grd){
								$grade=$grd->id;
							}else{
								$grade_data=array(
									'name'=>$grade,
									'code'=>'grd_'.time()
								);
								$grade=$this->grade_model->insert($grade_data);
							}
						}
						$designation_name=trim($sheet['L']);
						if($designation_name){
							$desg=$this->designation_model->getDesignationByName($designation_name);
							if($desg){
								$designation_id=$desg->id;
							}else{
								$designation_data=array(
									'name'=>$designation_name,
									'code'=>'desg_'.time()
								);
								$designation_id=$this->designation_model->insert($designation_data);
							}
						}
						$employeeofficedata[]=array(
							"card_no"=>$sheet['B'],
							"employee_name"=>$sheet['C'],
							"guardian_name"=>$sheet['D'],
							"relationship"=>$sheet['E'],
							"paycode"=>$sheet['F'],
							"branch_id"=>$branch_id,
							"department_id"=>$department_id,
							"category_id"=>$category_id,
							"section_id"=>$section_id,
							"grade_id"=>$grade_id,
							"designation_id"=>$designation_id,
							"hod"=>$sheet['M'],
							"image"=>$sheet['N'],
							"signature"=>$sheet['O'],
							"pf_no"=>$sheet['P'],
							"esi"=>$sheet['Q'],
							"leaving_date"=>$sheet['R'],
							"reason"=>$sheet['S'],
						);
					}
					if($i==1){
						$employeedata[]=array(
							"dob"=>$sheet['B'],
							"doj"=>$sheet['C'],
							"married"=>$sheet['D'],
							"bg"=>$sheet['E'],
							"qualification"=>$sheet['F'],
							"experience"=>$sheet['G'],
							"sex"=>$sheet['H'],
							"email"=>$sheet['I'],
							"bus_route"=>$sheet['J'],
							"vehicle"=>$sheet['K'],
							"eid_no"=>$sheet['L'],
							"eid_time"=>$sheet['M'],
							"eid_name"=>$sheet['N'],
							"aadhar"=>$sheet['O'],
							"permanent"=>$sheet['P'],
							"pincode"=>$sheet['Q'],
							"telephone"=>$sheet['R'],
							"temporary"=>$sheet['S'],
							"temp_pin"=>$sheet['T'],
							"temp_tel"=>$sheet['U'],
							"bank"=>$sheet['V'],
							"ifsc"=>$sheet['W']
						);
					}
					if($i==2){
						$employeshiftdata[]=array(
							"shift_type"=>$sheet['B'],
							"shift"=>$sheet['C'],
							"shift_pattern"=>$sheet['D'],
							"run_auto_shift"=>$sheet['E'],
							"first_week"=>$sheet['F'],
							"second_week"=>$sheet['G'],
							"second_wo"=>$sheet['H'],
							"second_week_off"=>$sheet['I'],
							"half_day"=>$sheet['J'],
							"shift_remain"=>'',
							"shift_change"=>'',
						);
					}
					if($i==3){
						$employetimedata[]=array(
							"perm_late"=>$sheet['B'],
							"perm_early"=>$sheet['C'],
							"max_work"=>$sheet['D'],
							"out_dura"=>$sheet['E'],
							"out_freq"=>'',
							"clock_work"=>$sheet['F'],
							"time_loss"=>$sheet['G'],
							"half_markting"=>$sheet['H'],
							"short_markting"=>$sheet['I'],
							"punches"=>$sheet['J'],
							"single_punch"=>$sheet['K'],
							"overtime_app"=>$sheet['L'],
							"overstay_app"=>$sheet['M'],
							"halfday_late"=>$sheet['N'],
							"late_utility"=>$sheet['O'],
							"rate_hour"=>$sheet['P'],
							"overstay_min"=>$sheet['Q'],
							"half_late"=>$sheet['R'],
							"half_early"=>$sheet['S']
						);
					}
				}
			}
			//$sheetData = $spreadsheet->getActiveSheet()->toArray();
			foreach($employeeofficedata as $key=>$emp){
				$empdata[$key]=$emp;
			}
			foreach($employeedata as $key=>$emp){
				//$empdata[$key]=$emp;
				$empdata[$key]=array_merge($empdata[$key],$emp);
			}
			foreach($employeshiftdata as $key=>$emp){
				//$empdata[$key]=$emp;
				$empdata[$key]=array_merge($empdata[$key],$emp);
			}
			foreach($employetimedata as $key=>$emp){
				//$empdata[$key]=$emp;
				$empdata[$key]=array_merge($empdata[$key],$emp);
			}
			foreach($empdata as $edata){
				$user=$this->employee_model->getEmployeeByUsername($edata['paycode']);
				if($user){
					$this->employee_model->editEmployee($user->id,$edata);
				}else{
					$this->employee_model->addEmployee($edata);
				}
			}
			$json= array(
				'success'=>'Employee Upload successfully',
				'redirect'=>admin_url('employee')
			);
		}else{
			$json['error'] = $this->upload->display_errors();
		}
		echo json_encode($json);
    	exit;
	}
	
	
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */