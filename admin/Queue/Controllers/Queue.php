<?php
namespace Admin\Queue\Controllers;

use Admin\Bank\Models\BankModel;
use Admin\Branch\Models\BranchModel;
use Admin\Category\Models\CategoryModel;
use Admin\Department\Models\DepartmentModel;
use Admin\Designation\Models\DesignationModel;
use Admin\Employee\Controllers\Employee;
use Admin\Employee\Models\EmployeeModel;
use Admin\Queue\Models\QueueModel;
use Admin\Grade\Models\GradeModel;
use Admin\Hod\Models\HodModel;
use Admin\Section\Controllers\Section;
use Admin\Section\Models\SectionModel;
use Admin\Shift\Models\ShiftModel;
use App\Controllers\AdminController;
use CodeIgniter\Config\Services;
use CodeIgniter\Queue\Models\QueueJobModel as QueueJob;
use DateTime;

class Queue extends AdminController {
	private $error = array();
	private $queueModel;
	private $employeeModel;
	public function __construct(){
        $this->queueModel=new QueueModel();
		$this->employeeModel=new EmployeeModel();
	}

	public function index(){
		$this->template->set_meta_title(lang('Queue.heading_title'));
        return $this->getList();
	}

	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->queueModel->getTotal();

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
		$totalFiltered = $this->queueModel->getTotal($filter_data);

		$filteredData = $this->queueModel->getAll($filter_data);
		//printr($filteredData);

		$datatable=array();
		foreach($filteredData as $result) {


			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('queue/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('queue/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
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
				$result->queue_date,
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
		$this->template->set_meta_title(lang('Queue.heading_title'));

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
			   $queue_id=$this->saveQueue();
			   if($queue_id){
				   $this->saveQueueHistory($queue_id);
			   }
			   $json=array(
				   "message"=>"Queue Updated Successfully.",
			   );
		   }
		   echo json_encode($json);
		   exit;

		}
		$this->getForm();
	}

	public function edit(){

		$this->template->set_meta_title(lang('Queue.heading_title'));

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){
			$json=[];
			$queue_id=$this->uri->getSegment(4);
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
				$queue_id=$this->uri->getSegment(4);
			   	if($queue_id){
				   $this->saveQueueHistory($queue_id);
			   	}
			   	$json=array(
				   "message"=>"Queue Updated Successfully.",
			   	);
		   }
		   echo json_encode($json);
		   exit;
		}
		$this->getForm();
	}

	protected function saveQueue(){
		$user_id=$this->request->getPost('user_id');
		$queuedate=(string)$this->request->getPost('queue_date');

		$empdata=$this->employeeModel->getEmployee($user_id);
		$shiftdata=$this->employeeModel->getEmployeeShift($user_id);
		$officedata=$this->employeeModel->getEmployeeOffice($user_id);
		$timedata=$this->employeeModel->getEmployeeTime($user_id);
		$queue_date=date("Y-m-d",strtotime($queuedate));

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
				$countshift=$this->queueModel->getShiftCount($user_id,$shift_change);
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

		$queue_data=array(
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
			'total_queue'=>$timedata->queuees,
			'queue_date'=>$queue_date,
			'status'=>1
		);


		$singledata=$this->queueModel->where(array('queue_date'=>$queue_date,'user_id'=>$user_id))->first();
		if(empty($singledata)){
			$queue_id=$this->queueModel->skipValidation()->insert($queue_data);
		}else{
			$queue_id=$singledata->id;
		}

		return $queue_id;




	}
	protected function getNextId($pattern,$shift_id){
		$key_count = count($pattern); // Count the number of elements in the array
		$given_index = array_search($shift_id, $pattern); // Find the index of the given key
		$next_index = ($given_index + 1) % $key_count; // Calculate the index of the next key in a cyclic manner
		$next_id = $pattern[$next_index]; // Retrieve the next key
		return $next_id; // Output: 3

	}
	public function saveQueueHistory($queue_id){
		$user_id=$this->request->getPost('user_id');
		$queue_time=$this->request->getPost('queue_time') ;
		$queue_date=(string)$this->request->getPost('queue_date') ;
		$employee=$this->employeeModel->getEmployee($user_id);

		$timedata=$this->employeeModel->getEmployeeTime($user_id);
		$queue_date=date("Y-m-d",strtotime($queue_date));
		$no_of_queue=$this->queueModel->getTotalQueueByQueueId($queue_id);
		if($timedata->queuees==-1 || $timedata->queuees==null){
			$queue_status=1;
		}else if($no_of_queue<=$timedata->queuees){
			$queue_status=1;
		}else{
			$queue_status=0;
		}

		$queue_history=array(
			'queue_id'=>$queue_id,
			'user_id'=>$user_id,
			'card_no'=>$employee->card_no,
			'queue_date'=>$queue_date,
			'queue_time'=>$queue_time,
			'queue_type'=>'M',
			'no_of_queue'=>$no_of_queue,
			'queue_status'=>$queue_status
		);

		$queue_id=$this->queueModel->saveQueueHistory($queue_history);

	}


	public function delete(){
		if ($this->request->getPost('selected')){
         $selected = $this->request->getPost('selected');
      }else{
         $selected = (array) $this->uri->getSegment(4);
       }
		$this->queueModel->deleteQueue($selected);
		$this->session->setFlashdata('message', 'Queue deleted Successfully.');
		return redirect()->to(admin_url('queue'));
	}

	protected function getList() {

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Queue.heading_title'),
			'href' => admin_url('queue')
		);

		$this->template->add_package(array('datatable','select2','daterangepicker'),true);


		$data['add'] = admin_url('queue/add');
		$data['delete'] = admin_url('queue/delete');
		$data['datatable_url'] = admin_url('queue/search');

		$data['heading_title'] = lang('Queue.heading_title');

		$data['text_list'] = lang('Queue.text_list');
		$data['text_no_results'] = lang('Queue.text_no_results');
		$data['text_confirm'] = lang('Queue.text_confirm');

		$data['column_queuename'] = lang('Queue.column_queuename');
		$data['column_status'] = lang('Queue.column_status');
		$data['column_date_added'] = lang('Queue.column_date_added');
		$data['column_action'] = lang('Queue.column_action');

		$data['button_add'] = lang('Queue.button_add');
		$data['button_edit'] = lang('Queue.button_edit');
		$data['button_delete'] = lang('Queue.button_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		$data['branches']=(new BranchModel())->getAll();
		$data['departments']=(new DepartmentModel())->getAll();
		$data['designations']=(new DesignationModel())->getAll();

		 

       $tasks=(new QueueJob())->findAll();
	  
	   $formattedTasks = [];
        foreach ($tasks as $task) {
            $formattedTasks[] = [
                'id' => $task->id,
                'status' => $task->status,
                'queue' => $task->queue,
                'process_id' => $task->process_id ?? 'N/A',
            ];
        }

        $data['tasks'] = $formattedTasks;

		
		//$command = "php " . FCPATH . "spark queue:work 01052024";
        //shell_exec($command);

		return $this->template->view('Admin\Queue\Views\queue', $data);
	}

	protected function getForm(){

		$this->template->add_package(array('datatable','datepicker','timepicker','select2'),true);

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Queue.heading_title'),
			'href' => admin_url('queue')
		);

		$data['breadcrumbs'][] = array(
			'text' => lang('Queue.text_add'),
			'href' => admin_url('queue/add')
		);

		$_SESSION['isLoggedIn'] = true;

		$data['heading_title'] 	= lang('Queue.heading_title');

		$data['text_form'] = $this->uri->getSegment(4) ? lang('Queue.text_edit') : lang('Queue.text_add');
		$data['button_save'] = lang('Queue.button_save');
		$data['button_cancel'] = lang('Queue.button_cancel');

		$data['cancel'] = admin_url('queue');
		$data['save'] = admin_url('queue/save');

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
			$queue_info = $this->queueModel->find($id);
			//$emp_info = $this->employeeModel->getEmployeeOffice($mqueue_info->user_id);
			$data['queue_user_id']=$queue_info->user_id;
		}else{
			//$emp_info = $this->employeeModel->getEmployeeOffice($user_id);
			$data['queue_user_id']=$user_id;
		}

		foreach($this->queueModel->getFieldNames('queue') as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($queue_info->{$field}) && $queue_info->{$field}) {
				$data[$field] = $queue_info->{$field};
			} else {
				$data[$field] = '';
			}
		}

		if(isset($queue_info->queue_date)) {
			$data['queue_date'] = $queue_info->queue_date;
		} else {
			$data['queue_date'] = date("d-m-Y");
		}

		$data['employee_details']= $this->template->view('Admin\Employee\Views\employeeDetailsAttachForm',[],true);

		echo $this->template->view('Admin\Queue\Views\queueForm',$data);
	}

	public function history(){
		$data=array();
		$user_id=$this->request->getGet('user_id');
		$queue_date=$this->request->getGet('queue_date');
		$data['queuees']= $this->queueModel->getQueueHistory($user_id,$queue_date);

		return view('Admin\Queue\Views\queueHistory',$data);
	}

	public function deleteHistory(){
		$selected = (array) $this->uri->getSegment(4);
		$this->queueModel->deleteQueueHistory($selected);
		$this->session->setFlashdata('message', 'queue history deleted Successfully.');
		return redirect()->to(previous_url());

	}

	protected function validateForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $rules = $this->queueModel->validationRules;

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