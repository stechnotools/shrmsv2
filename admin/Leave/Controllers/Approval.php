<?php
namespace Admin\Leave\Controllers;
use Admin\Branch\Models\BranchModel;
use Admin\Department\Models\DepartmentModel;
use Admin\Employee\Models\EmployeeModel;
use Admin\Leave\Models\LeaveApplicationModel;
use Admin\Leave\Models\LeaveModel;

use Admin\LeaveOpening\Models\LeaveOpeningModel;
use Admin\Users\Models\UserModel;
use App\Controllers\AdminController;

class Approval extends AdminController {
	private $error = array();
	private $LeaveApplicationModel;
	public function __construct(){
        $this->LeaveApplicationModel=new LeaveApplicationModel();
	}

	public function index(){

		$this->template->set_meta_title(lang('LeaveApproval.heading_title'));
        return $this->getList();
	}

	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->LeaveApplicationModel->getTotal();

		$totalFiltered = $totalData;

		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->LeaveApplicationModel->getTotal($filter_data);

		$filteredData = $this->LeaveApplicationModel->getAll($filter_data);
		//printr($filteredData);
		$datatable=array();
		foreach($filteredData as $result) {
			if($result->leave_status == 0){
				$status = 'Applied';
				$btn_color='badge-warning';
			}elseif($result->leave_status == 1){
				$status = 'Approved';
				$btn_color='badge-success';
			}
			elseif($result->leave_status == 2){
				$status = 'Rejected';
				$btn_color='badge-danger';
			}
			$action  = '<div class="pull-right">';
			$action .= 		'<a class="btn btn-info btn-sm waves-effect waves-light mt-1 mr-1" data-href="'.admin_url('leave/approval/approve/'.$result->id).'">View</a>';
			$action .=  	'<div class="btn-group btn-group-sm mt-1 mr-1">';
			$action .=		'<button type="button" class="btn btn-pink dropdown-toggle waves-effect waves-light btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions<i class="mdi mdi-chevron-down"></i></button>';
			$action .=		'<ul class="dropdown-menu">';
			$action .=		'<li><a href="'.admin_url('leave/approval/action/'.$result->id).'" data-action="1" class="dropdown-item btn-action">Approve</a></li>';
			$action .=		'<li><a href="'.admin_url('leave/approval/action/'.$result->id).'" data-action="2" class="dropdown-item btn-action">Reject</a></li>';
			$action .=		'</ul>';
			$action .=		'</div>';
			//$action .= 		'<a class="btn btn-sm '.$btn_color.' btn-approve" data-href="'.admin_url('leave/approval/approve/'.$result->id).'">'.$status.'</a>';
			$action .= '</div>';


			$datatable[]=array(
				'<div class="checkbox checkbox-primary checkbox-single">
					<input type="checkbox" name="selected[]" value="'.$result->id.'" />
					<label></label>
				</div>',
				$result->paycode,
				$result->leave_from,
				$result->leave_to,
				$result->leave_id,
				$result->leave_type,
				$result->reason,
				'<span class="badge '.$btn_color.'">'.$status.'</span>',
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

	public function action(){
		$json=[];
		$leave_id=$this->uri->getSegment(5);
		$action = $this->request->getPost('action');
		$this->LeaveApplicationModel
		->where('id', $leave_id)
		->set('leave_status', $action)
		->update();
		$json['success']=true;
		echo json_encode($json);
		exit();
	}

	public function add(){
		$this->template->set_meta_title(lang('LeaveApproval.heading_title'));

		if (is_ajax() && $this->request->getMethod('REQUEST_METHOD') === 'POST'){

			if(!$this->validateForm()){
				echo json_encode(array('status'=>false,'message'=>$this->error));
				exit;
			}else{

				$officedata=(new EmployeeModel)->getEmployee($this->request->getPost('user_id'));

				$leave_data=array(
					'user_id'=>$this->request->getPost('user_id'),
					'employee_name'=>$officedata->employee_name,
					'paycode'=>$officedata->paycode,
					'branch_id'=>$officedata->branch_id,
					'branch_name'=>$officedata->branch_name,
					'department_id'=>$officedata->department_id,
					'department_name'=>$officedata->department_name,
					'category_id'=>$officedata->category_id,
					'category_name'=>$officedata->category_name,
					'designation_id'=>$officedata->designation_id,
					'designation_name'=>$officedata->designation_name,
					'leave_id'=>$this->request->getPost('leave_id'),
					'leave_from'=>date("Y-m-d",strtotime((string)$this->request->getPost('leave_from'))),
					'leave_to'=>date("Y-m-d",strtotime((string)$this->request->getPost('leave_to'))),
					'reason'=>$this->request->getPost('reason'),
					'chkLeavePost'=>$this->request->getPost('chkLeavePost'),
					'leave_type'=>$this->request->getPost('leave_type'),
					'leave_status'=>0,
					'status'=>1
				);

				$leaveid=$this->LeaveApprovalModel->insert($leave_data);
				$this->session->setFlashdata('message', 'LeaveApproval Saved Successfully.');
				echo json_encode(array('status'=>true,'message'=>'LeaveApproval Saved Successfully.'));
				exit;
			}
			//return redirect()->to(admin_url('leaveapplication'));

		}
		$this->getForm();
	}

	public function edit(){

		$this->template->set_meta_title(lang('LeaveApproval.heading_title'));

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){
			$leaveapplication_id=$this->uri->getSegment(4);

			$this->LeaveApprovalModel->editLeaveApproval($leaveapplication_id,$this->request->getPost());

			$this->session->setFlashdata('message', 'LeaveApproval Updated Successfully.');
			return redirect()->to(admin_url('leaveapplication'));
		}
		$this->getForm();
	}

	public function delete(){
		if ($this->request->getPost('selected')){
         $selected = $this->request->getPost('selected');
      }else{
         $selected = (array) $this->uri->getSegment(4);
       }
		$this->LeaveApprovalModel->deleteLeaveApproval($selected);
		$this->session->setFlashdata('message', 'LeaveApproval deleted Successfully.');
		redirect(ADMIN_PATH.'/leaveapplication');
	}

	protected function getList() {

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('LeaveApproval.heading_title'),
			'href' => admin_url('leaveapplication')
		);

		$this->template->add_package(array('datatable'),true);


		$data['add'] = admin_url('leave/approval/add');
		$data['delete'] = admin_url('leave/approval/delete');
		$data['datatable_url'] = admin_url('leave/approval/search');

		$data['heading_title'] = lang('LeaveApproval.heading_title');

		$data['text_list'] = lang('LeaveApproval.text_list');
		$data['text_no_results'] = lang('LeaveApproval.text_no_results');
		$data['text_confirm'] = lang('LeaveApproval.text_confirm');

		$data['column_leaveapplicationname'] = lang('LeaveApproval.column_leaveapplicationname');
		$data['column_status'] = lang('LeaveApproval.column_status');
		$data['column_date_added'] = lang('LeaveApproval.column_date_added');
		$data['column_action'] = lang('LeaveApproval.column_action');

		$data['button_add'] = lang('LeaveApproval.button_add');
		$data['button_edit'] = lang('LeaveApproval.button_edit');
		$data['button_delete'] = lang('LeaveApproval.button_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Leave\Views\leaveApproval', $data);
	}

	protected function getForm(){

		$this->template->add_package(array('datatable','select2','datepicker'),true);


		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('LeaveApproval.heading_title'),
			'href' => admin_url('leaveapplication')
		);

		$data['breadcrumbs'][] = array(
			'text' => lang('LeaveApproval.text_add'),
			'href' => admin_url('leaveapplication/add')
		);

		$_SESSION['isLoggedIn'] = true;

		$data['heading_title'] 	= lang('LeaveApproval.heading_title');

		$data['text_form'] = $this->uri->getSegment(4) ? lang('LeaveApproval.text_edit') : lang('LeaveApproval.text_add');
		$data['button_save'] = lang('LeaveApproval.button_save');
		$data['button_cancel'] = lang('LeaveApproval.button_cancel');
		$data['cancel'] = admin_url('leaveapplication');

		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->uri->getSegment(4) && ($this->request->getMethod('REQUEST_METHOD') != 'POST')) {
			$leaveapplication_info = $this->LeaveApprovalModel->find($this->uri->getSegment(4));
		}

		foreach($this->LeaveApprovalModel->getFieldNames($this->LeaveApprovalModel->table) as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($leaveapplication_info->{$field}) && $leaveapplication_info->{$field}) {
				$data[$field] = $leaveapplication_info->{$field};
			} else {
				$data[$field] = '';
			}
		}

		if(isset($leaveapplication_info->leave_from)) {
			$data['leave_from'] = $leaveapplication_info->leave_from;
		} else {
			$data['leave_from'] = date("d-m-Y");
		}

		if(isset($leaveapplication_info->leave_to)) {
			$data['leave_to'] = $leaveapplication_info->leave_to;
		} else {
			$data['leave_to'] = date("d-m-Y");
		}



		$data['branches']=(new BranchModel())->getAll();
		$data['users']=(new UserModel())->getAll();
		$data['leavecodes']=(new LeaveModel())->getAll();

		$data['leavetypes']=array('f'=>'Full day','h'=>'HalfDay','tf'=>'Three Fourth','q'=>'Quarter');


		echo $this->template->view('Admin\LeaveApproval\Views\LeaveApprovalForm',$data);
	}

	public function getLeaveDetails(){
		$data=$this->getEmployeeLeaveBalance();
		echo view('Admin\LeaveApproval\Views\leaveBalance', $data);
		exit;
	}

	protected function getEmployeeLeaveBalance(){
		$user_id=$this->request->getPost('user_id');
		$previous_balance=$this->getLeaveBalance($user_id,true);

		//printr($previous_balance);
		$current_balance=$this->getLeaveBalance($user_id,false);


		$data['leavebalance']=array();
		$leavetypes=(new LeaveModel())->getAll();

		$total_carried_leave = 0;
		$total_leave_opening_total = 0;
		$total_leave_taken_total = 0;
		$total_leave_total = 0;
		$total_balance_total = 0;


		foreach($leavetypes as $leavetype){

			$leave_opening_total=$current_balance[$leavetype->id]['leave_opening_total'];
			$leave_taken_total=$current_balance[$leavetype->id]['leave_taken_total'];
			$carried_leave=$previous_balance[$leavetype->id]['carried_leave'];
			$leave_total=$carried_leave+$leave_opening_total;
			$balance_total=$leave_total-$leave_taken_total;

			$total_carried_leave += $carried_leave;
			$total_leave_opening_total += $leave_opening_total;
			$total_leave_taken_total += $leave_taken_total;
			$total_leave_total += $leave_total;
			$total_balance_total += $balance_total;

			$data['leavebalance'][$leavetype->id]=[
				'leave_id'=>$leavetype->id,
				'leave_name'=>$leavetype->leave_field,
				'carried_leave'=>$carried_leave,
				'leave_opening_total'=>$leave_opening_total,
				'leave_total'=>$leave_total,
				'leave_taken_total'=>$leave_taken_total,
				'leave_balance'=>$balance_total,
			];
		}

		$data['total'] = [
			'total_carried_leave' => $total_carried_leave,
			'total_leave_opening_total' => $total_leave_opening_total,
			'total_leave_taken_total' => $total_leave_taken_total,
			'total_leave_total' => $total_leave_total,
			'total_balance_total' => $total_balance_total,
		];
		return $data;

	}

	protected function getLeaveBalance($user_id,$previous){

		$employee=(new EmployeeModel())->getEmployee($user_id);


		$fyear=financial_year($previous);
		if ($previous && $employee !== null && isset($employee->doj)) {
			$joiningDate = strtotime($employee->doj); // Start date in timestamp format (two years ago from the current date)
			$financialEndDate = strtotime($fyear['end']);
			$monthsWorked = (date('Y', $financialEndDate) - date('Y', $joiningDate)) * 12 + (date('m', $financialEndDate) - date('m', $joiningDate));
			if ($monthsWorked > 12) {
				$monthsWorked = 12;
			}
		}else{
			$monthsWorked = 12;
		}

		$filter=array(
			'user_id'=>$user_id,
			'branch_id'=>$employee->branch_id,
			'department_id'=>$employee->department_id,
			'from_date'=>$fyear['start'],
			'to_date'=>$fyear['end']
		);

		//calculate working day

		//printr($filter);
		$leavedatas=(new LeaveOpeningModel())->getOpeningLeaveBalance($filter);
		//printr($leavedatas);
		$leave_opening_balance=[];
		foreach ($leavedatas as $leavedata) {
			if ($leavedata['value'] != 0) {
				if ($leavedata['type'] == 'user') {
					$leave_opening_balance[$leavedata['leave_id']] = $leavedata['value'];
				} elseif ($leavedata['type'] == 'gender' && !isset($leave_opening_balance[$leavedata['leave_id']])) {
					$leave_opening_balance[$leavedata['leave_id']] = $leavedata['value'];
				} elseif ($leavedata['type'] == 'department' && !isset($leave_opening_balance[$leavedata['leave_id']])) {
					$leave_opening_balance[$leavedata['leave_id']] = $leavedata['value'];
				} elseif ($leavedata['type'] == 'branch' && !isset($leave_opening_balance[$leavedata['leave_id']])) {
					$leave_opening_balance[$leavedata['leave_id']] = $leavedata['value'];
				}
			}
		}
		//printr($leave_opening_balance);
		$leaveopening = [];
		foreach ($leave_opening_balance as $leave_id => $value) {
			$leaveopening[$leave_id] =$value;
		}



		$leave_taken_balance=$this->LeaveApprovalModel->getLeaveTakenByUser($filter);

		$leavetaken = [];
		foreach ($leave_taken_balance as  $value) {
			$leavetaken[$value['leave_id']] =$value['leave_taken_total'];
		}


		$leavetypes=(new LeaveModel)->getAll();
		$leave_opening_balance = [];
		foreach($leavetypes as $leavetype){
			$leave_opening_total = isset($leaveopening[$leavetype->id]) ? $leaveopening[$leavetype->id] : 0;
			$leave_taken_total = isset($leavetaken[$leavetype->id]) ? $leavetaken[$leavetype->id] : 0;
			$carriedLeave=0;
			if($previous && $leavetype->saction_type=="carried"){
				$accruedLeave = $leave_opening_total * ($monthsWorked / 12);
				$remainingLeave = $accruedLeave - $leave_taken_total;
				$maxAccumulationLimit = $leavetype->carried_leaves_limit;
				$carriedLeave = min($remainingLeave, $maxAccumulationLimit);
			}

			$leave_opening_balance[$leavetype->id]=array(
				'leave_id'=>$leavetype->id,
				'leave_opening_total'=>$leave_opening_total,
				'leave_taken_total'=>$leave_taken_total,
				'leave_code'=>$leavetype->leave_code,
				'week_exclude'=>$leavetype->week_exclude,
				'holiday_exclude'=>$leavetype->holiday_exclude,
				'leave_type'=>$leavetype->leave_type,
				'sanction_limit_min'=>$leavetype->saction_limit_min,
				'sanction_limit_max'=>$leavetype->saction_limit_max,
				'sanction_type'=>$leavetype->saction_type,
				'accural_type'=>$leavetype->accural_type,
				'carried_leave'=>$carriedLeave
			);
		}

		return $leave_opening_balance;
	}

	protected function validateForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $rules = $this->LeaveApprovalModel->validationRules;

        if ($this->validate($rules)){
            return true;
        }
        else{
            //printr($validation->getErrors());
			$this->error['errors']=$validation->getErrors();
            $this->error['warning']="Warning: Please check the form carefully for errors!";
            return false;
        }
        return !$this->error;
	}



}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */