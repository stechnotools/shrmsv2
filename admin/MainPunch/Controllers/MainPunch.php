<?php
namespace Admin\MainPunch\Controllers;

use Admin\Bank\Models\BankModel;
use Admin\Branch\Models\BranchModel;
use Admin\Category\Models\CategoryModel;
use Admin\Department\Models\DepartmentModel;
use Admin\Designation\Models\DesignationModel;
use Admin\Employee\Controllers\Employee;
use Admin\Employee\Models\EmployeeModel;
use Admin\Grade\Models\GradeModel;
use Admin\Hod\Models\HodModel;
use Admin\MainPunch\Models\MainPunchHistoryModel;
use Admin\MainPunch\Models\MainPunchModel;
use Admin\MainPunch\Models\MainRawPunchModel;
use Admin\Section\Controllers\Section;
use Admin\Section\Models\SectionModel;
use Admin\Shift\Models\ShiftModel;
use App\Controllers\AdminController;
use DateTime;

class MainPunch extends AdminController {
	private $error = array();
	private $mainPunchModel;
	private $employeeModel;
	public function __construct(){
        $this->mainPunchModel=new MainPunchModel();
		$this->employeeModel=new EmployeeModel();
	}

	public function index(){
		$this->template->set_meta_title(lang('MainPunch.heading_title'));
        return $this->getList();
	}

	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->mainPunchModel->getTotal();

		$totalFiltered = $totalData;

		$daterange=$requestData['daterange'];
		$daterange=explode("-",$daterange);
		$fromdate=date("Y-m-d",strtotime(trim($daterange[0])));
		$todate=date("Y-m-d",strtotime(trim($daterange[1])));

		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'branch_id' 	=> $requestData['branch_id'],
			'user_id' 		=> $requestData['user_id'],
			'fromdate'		=>	$fromdate,
			'todate'		=> $todate,
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->mainPunchModel->getTotal($filter_data);

		$filteredData = $this->mainPunchModel->getAll($filter_data);
		//printr($filteredData);

		$datatable=array();
		foreach($filteredData as $result) {


			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('mainpunch/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('mainpunch/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
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
				'<div class="checkbox checkbox-primary checkbox-single">
					<input type="checkbox" name="selected[]" value="'.$result->id.'" />
					<label></label>
				</div>',
				$result->branch_name,
				$result->employee_name,
				$result->paycode,
				$result->punch_date,
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

		return $this->response->setContentType('application/json')
            ->setJSON($json_data);  // send data as json format
	}

	public function add(){
		$this->template->set_meta_title(lang('MainPunch.heading_title'));

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){
			$json=[];
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

		$this->template->set_meta_title(lang('MainPunch.heading_title'));

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){
			$json=[];
			$punch_id=$this->uri->getSegment(4);
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
				$punch_id=$this->uri->getSegment(4);
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

	protected function savePunch(){
		$user_id=$this->request->getPost('user_id');
		$punchdate=(string)$this->request->getPost('mainpunch_date');

		$empdata=$this->employeeModel->getEmployee($user_id);
		$shiftdata=$this->employeeModel->getEmployeeShift($user_id);
		$officedata=$this->employeeModel->getEmployeeOffice($user_id);
		$timedata=$this->employeeModel->getEmployeeTime($user_id);
		$punch_date=date("Y-m-d",strtotime($punchdate));

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
				$countshift=$this->mainPunchModel->getShiftCount($user_id,$shift_change);
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
			'employee_name'=>$empdata->employee_name,
			'paycode'=>$empdata->paycode,
			'card_no'=>$empdata->card_no,
			'branch_id'=>$empdata->branch_id,
			'branch_name'=>$empdata->branch_name,
			'department_id'=>$empdata->department_id,
			'department_name'=>$empdata->department_name,
			'category_id'=>$empdata->category_id,
			'category_name'=>$empdata->category_name,
			'section_id'=>$empdata->section_id,
			'section_name'=>$empdata->section_name,
			'grade_id'=>$empdata->grade_id,
			'grade_name'=>$empdata->grade_name,
			'designation_id'=>$empdata->designation_id,
			'designation_name'=>$empdata->designation_name,
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
			'mainpunch_date'=>$punch_date,
			'status'=>1
		);


		$singledata=$this->mainPunchModel->where(array('mainpunch_date'=>$punch_date,'user_id'=>$user_id))->first();
		if(empty($singledata)){
			$punch_id=$this->mainPunchModel->skipValidation()->insert($punch_data);
		}else{
			$punch_id=$singledata->id;
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
		$user_id=$this->request->getPost('user_id');
		$punch_time=$this->request->getPost('mainpunch_time') ;
		$punch_date=(string)$this->request->getPost('mainpunch_date') ;
		$employee=$this->employeeModel->getEmployee($user_id);

		$timedata=$this->employeeModel->getEmployeeTime($user_id);
		$punch_date=date("Y-m-d",strtotime($punch_date));
		$no_of_punch=$this->mainPunchModel->getTotalPunchByPunchId($punch_id);
		if($timedata->punches==-1 || $timedata->punches==null){
			$punch_status=1;
		}else if($no_of_punch<=$timedata->punches){
			$punch_status=1;
		}else{
			$punch_status=0;
		}

		$punch_history=array(
			'mainpunch_id'=>$punch_id,
			'user_id'=>$user_id,
			'card_no'=>$employee->card_no,
			'mainpunch_date'=>$punch_date,
			'mainpunch_time'=>$punch_time,
			'mainpunch_type'=>'M',
			'no_of_punch'=>$no_of_punch,
			'mainpunch_status'=>$punch_status
		);

		$punch_id=$this->mainPunchModel->savePunchHistory($punch_history);

	}

	public function uploadpunch(){
		$mainrawpunchModel = new MainRawPunchModel();
		$mainpunchModel = new MainPunchModel();
		$mainpunchHistoryModel= new MainPunchHistoryModel();
		$json = $this->request->getBody();

        // Decode the JSON data into an array
        $sheets = json_decode($json, true);
		$punchdate=date('Y-m-d',strtotime($sheets['sheetName']));
		foreach($sheets['sheetData'] as $sheet){

			$safety_pass_no = trim($sheet[0]);
			$flag=	trim($sheet[3]);
			$shift = trim($sheet[4]);
			$punch_date = $punchdate;
			$punch_time = date('H:i:s', strtotime($sheet[5]));
			$location = trim($sheet[7]);


			$employee=$this->employeeModel->where('safety_pass_no', $safety_pass_no)->first();
			if($employee){
				$employeoffice=$this->employeeModel->getEmployee($employee->user_id);
				$machinerawpunch=[
					'user_id'=>$employeoffice->user_id,
					'branch_id'=>$employeoffice->branch_id,
					'safety_pass_no'=>$safety_pass_no,
					'punch_date'=>$punch_date,
					'punch_time'=>$punch_time,
					'flag'=>$flag,
					'shift'=>$employeoffice->shift_id,
					'department_id'=>$employeoffice->department_id,
					'location'=>$location
				];

				$checkdata = $mainrawpunchModel->where($machinerawpunch)->first();
				$lastquery= $mainrawpunchModel->getLastQuery();

				$errorMessage = 'Raw punch last query: ' . $lastquery;
				log_message('error', $errorMessage);
				if (empty($checkdata)) {
					$mainrawpunchModel->insert($machinerawpunch);
				} else {
					$mainrawpunchModel->update($checkdata->id, $machinerawpunch);
					$lastquery= $mainrawpunchModel->getLastQuery();
					$errorMessage = 'Raw punch update last query: ' . $lastquery;
					log_message('error', $errorMessage);
				}

				$pdata = [
					'user_id' => $employeoffice->user_id,
					'paycode' => $employeoffice->paycode,
					'branch_id' => $employeoffice->branch_id,
					'punch_date' => $punch_date,
					'punch_time' => $punch_time,
					'punch_type' => 'A'
				];

				$singledata = $mainpunchModel->where(['user_id' => $employeoffice->user_id, 'punch_date' => $punch_date, 'branch_id' => $employeoffice->branch_id])->first();

				if (empty($singledata)) {
					$punch_id = $this->saveMainPunch($pdata);
				} else {
					$punch_id = $singledata->id;
					$this->saveMainPunch($pdata, $punch_id);
				}

				$singlepdata = $mainpunchHistoryModel->where(['punch_id' => $punch_id, 'punch_date' => $punch_date, 'punch_time' => $punch_time, 'branch_id' => $employeoffice->branch_id])->first();

				if (empty($singlepdata)) {
					$this->saveMainPunchHistory($pdata, $punch_id);
				} else {
					$this->saveMainPunchHistory($pdata, $punch_id, $singlepdata->id);
				}


			}


		}

		echo json_encode(array("status"=>"added"));
		exit;

	}

	public function saveMainPunch($data,$punch_id=0){
		$mainpunchModel=new MainPunchModel();

		$employeedata=(new EmployeeModel())->getEmployee($data['user_id']);
		$shiftdata=(new EmployeeModel())->getEmployeeShift($data['user_id']);
		$timedata=(new EmployeeModel())->getEmployeeTime($data['user_id']);

		$punch_date=date("Y-m-d",strtotime($data['punch_date']));
		$punch_data=array(
			'user_id'=>$data['user_id'],
			'employee_name'=>$employeedata->employee_name,
			'paycode'=>$employeedata->paycode,
			'branch_id'=>$employeedata->branch_id,
			'branch_name'=>$employeedata->branch_name,
			'department_id'=>$employeedata->department_id,
			'department_name'=>$employeedata->department_name,
			'category_id'=>$employeedata->category_id,
			'category_name'=>$employeedata->category_name,
			'section_id'=>$employeedata->section_id,
			'section_name'=>$employeedata->section_name,
			'grade_id'=>$employeedata->grade_id,
			'grade_name'=>$employeedata->grade_name,
			'designation_id'=>$employeedata->designation_id,
			'designation_name'=>$employeedata->designation_name,
			'shift_type'=>$employeedata->shift_type,
			'shift_id'=>$shiftdata->shift_id,
			'shift_name'=>$shiftdata->shift_name,
			'shift_pattern'=>$shiftdata->shift_pattern,
			'shift_start_time'=>$shiftdata->shift_start_time,
			'shift_end_time'=>$shiftdata->shift_end_time,
			'auto_shift'=>$shiftdata->run_auto_shift,
			'first_week'=>$shiftdata->first_week,
			'second_week'=>$shiftdata->second_week,
			'late_arrival'=>$timedata ? ($timedata->perm_late ?? '00:00:00') : '00:00:00',
			'early_departure'=>$timedata ? ($timedata->perm_early ?? '00:00:00') : '00:00:00',
			'total_punch'=>$timedata ? ($timedata->punches ?? 0) : 0,
			'punch_date'=>$punch_date,
			'status'=>1
		);
		if($punch_id){
			$mainpunchModel->update($punch_id,$punch_data);
		}else{
			$punch_id=$mainpunchModel->insert($punch_data);
		}

		return $punch_id;

	}


	public function saveMainPunchHistory($data,$punch_id,$punch_history_id=0){
		$mainpunchHistoryModel=new MainPunchHistoryModel();
		$timedata=(new EmployeeModel())->getEmployeeTime($data['user_id']);
		$employeedata=(new EmployeeModel())->getEmployee($data['user_id']);

		$no_of_punch=$mainpunchHistoryModel->where(['punch_id'=>$punch_id])-> countAllResults();
		if($timedata->punches==-1){
			$punch_status=1;
		}else if($no_of_punch<=($timedata->punches)){
			$punch_status=1;
		}else{
			$punch_status=0;
		}
		$punch_history=array(
			'punch_id'=>$punch_id,
			'user_id'=>$data['user_id'],
			'card_no'=>$employeedata->card_no,
			'paycode'=>$data['paycode'],
			'punch_date'=>$data['punch_date'],
			'punch_time'=>$data['punch_time'],
			'punch_type'=>$data['punch_type'],
			'branch_id'=>$data['branch_id'],
			'no_of_punch'=>$no_of_punch+1,
			'punch_status'=>$punch_status
		);
		if($punch_history_id){
			$mainpunchHistoryModel->update($punch_history_id,$punch_history);
		}else{
			$punch_history_id=$mainpunchHistoryModel->insert($punch_history);
		}

	}

	public function delete(){
		if ($this->request->getPost('selected')){
         $selected = $this->request->getPost('selected');
      }else{
         $selected = (array) $this->uri->getSegment(4);
       }
		$this->mainPunchModel->deletePunch($selected);
		$this->session->setFlashdata('message', 'Punch deleted Successfully.');
		return redirect()->to(admin_url('mainpunch'));
	}

	protected function getList() {

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('MainPunch.heading_title'),
			'href' => admin_url('mainpunch')
		);

		$this->template->add_package(array('datatable','select2','daterangepicker'),true);


		$data['add'] = admin_url('mainpunch/add');
		$data['delete'] = admin_url('mainpunch/delete');
		$data['datatable_url'] = admin_url('mainpunch/search');

		$data['heading_title'] = lang('MainPunch.heading_title');

		$data['text_list'] = lang('MainPunch.text_list');
		$data['text_no_results'] = lang('MainPunch.text_no_results');
		$data['text_confirm'] = lang('MainPunch.text_confirm');

		$data['column_punchname'] = lang('MainPunch.column_punchname');
		$data['column_status'] = lang('MainPunch.column_status');
		$data['column_date_added'] = lang('MainPunch.column_date_added');
		$data['column_action'] = lang('MainPunch.column_action');

		$data['button_add'] = lang('MainPunch.button_add');
		$data['button_edit'] = lang('MainPunch.button_edit');
		$data['button_delete'] = lang('MainPunch.button_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		$data['download_sample']=base_url('uploads/files/samples/clm-sample.xlsx');

		$data['branches']=(new BranchModel())->getAll();
		$data['departments']=(new DepartmentModel())->getAll();
		$data['designations']=(new DesignationModel())->getAll();


		return $this->template->view('Admin\MainPunch\Views\mainpunch', $data);
	}

	protected function getForm(){

		$this->template->add_package(array('datatable','datepicker','timepicker','select2'),true);

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('MainPunch.heading_title'),
			'href' => admin_url('mainpunch')
		);

		$data['breadcrumbs'][] = array(
			'text' => lang('MainPunch.text_add'),
			'href' => admin_url('mainpunch/add')
		);

		$_SESSION['isLoggedIn'] = true;

		$data['heading_title'] 	= lang('MainPunch.heading_title');

		$data['text_form'] = $this->uri->getSegment(4) ? lang('MainPunch.text_edit') : lang('MainPunch.text_add');
		$data['button_save'] = lang('MainPunch.button_save');
		$data['button_cancel'] = lang('MainPunch.button_cancel');

		$data['cancel'] = admin_url('mainpunch');
		$data['save'] = admin_url('mainpunch/save');

		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}

		$user_id=0;
		if($this->uri->getSegment(4)){
			$id=$this->uri->getSegment(4);
			$data['edit']=true;
		}else if($this->request->getGet('id')){
			$user_id=$this->request->getGet('id');
			$id=0;
			$data['edit']=false;
		}else{
			$id=0;
			$data['edit']=false;
		}

		if ($id && ($this->request->getMethod('REQUEST_METHOD') != 'POST')) {
			$punch_info = $this->mainPunchModel->find($id);
			//$emp_info = $this->employeeModel->getEmployeeOffice($mpunch_info->user_id);
			$data['mainpunch_user_id']=$punch_info->user_id;
		}else{
			//$emp_info = $this->employeeModel->getEmployeeOffice($user_id);
			$data['mainpunch_user_id']=$user_id;
		}

		foreach($this->mainPunchModel->getFieldNames('mainpunch') as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($punch_info->{$field}) && $punch_info->{$field}) {
				$data[$field] = $punch_info->{$field};
			} else {
				$data[$field] = '';
			}
		}

		if(isset($punch_info->punch_date)) {
			$data['mainpunch_date'] = $punch_info->punch_date;
		} else {
			$data['mainpunch_date'] = date("d-m-Y");
		}

		$data['employee_details']= $this->template->view('Admin\Employee\Views\employeeDetailsAttachForm',[],true);

		echo $this->template->view('Admin\Punch\Views\punchForm',$data);
	}

	public function history(){
		$data=array();
		$user_id=$this->request->getGet('user_id');
		$punch_date=$this->request->getGet('mainpunch_date');
		$data['mainpunches']= $this->mainPunchModel->getPunchHistory($user_id,$punch_date);

		return view('Admin\Punch\Views\punchHistory',$data);
	}

	public function deleteHistory(){
		$selected = (array) $this->uri->getSegment(4);
		$this->mainPunchModel->deletePunchHistory($selected);
		$this->session->setFlashdata('message', 'mainpunch history deleted Successfully.');
		return redirect()->to(previous_url());

	}

	protected function validateForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $rules = $this->mainPunchModel->validationRules;

        if ($this->validate($rules)){
            return true;
        }
        else{
           //printr($validation->getErrors());
			$this->error['errors'] = $validation->getErrors();
            $this->error['warning']="Warning: Please check the form carefully for errors!";
            return false;
        }
        return !$this->error;
	}



}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */