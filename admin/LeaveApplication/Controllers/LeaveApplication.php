<?php
namespace Admin\LeaveApplication\Controllers;

use Admin\Branch\Models\BranchModel;
use Admin\Department\Models\DepartmentModel;
use Admin\Leave\Models\LeaveModel;
use Admin\LeaveApplication\Models\LeaveApplicationModel;
use Admin\Users\Models\UserModel;
use App\Controllers\AdminController;

class LeaveApplication extends AdminController {
	private $error = array();
	private $leaveApplicationModel;
	public function __construct(){
        $this->leaveApplicationModel=new LeaveApplicationModel();
	}

	public function index(){
		$this->template->set_meta_title(lang('LeaveApplication.heading_title'));
        return $this->getList();
	}

	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->leaveApplicationModel->getTotal();

		$totalFiltered = $totalData;

		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->leaveApplicationModel->getTotal($filter_data);

		$filteredData = $this->leaveApplicationModel->getAll($filter_data);
		//printr($filteredData);
		$datatable=array();
		foreach($filteredData as $result) {
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('leaveapplication/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('leaveapplication/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
			$action .= '</div>';

			$datatable[]=array(
				'<div class="checkbox checkbox-primary checkbox-single">
					<input type="checkbox" name="selected[]" value="'.$result->id.'" />
					<label></label>
				</div>',
				$result->type,
				$result->type_name,
				date("Y",strtotime($result->year_from)).'-'.date("Y",strtotime($result->year_to)),
				$result->leave_total,
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
		$this->template->set_meta_title(lang('LeaveApplication.heading_title'));

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){

			$this->leaveApplicationModel->addLeaveApplication($this->request->getPost());

			$this->session->setFlashdata('message', 'LeaveApplication Saved Successfully.');
			return redirect()->to(admin_url('leaveapplication'));

		}
		$this->getForm();
	}

	public function edit(){

		$this->template->set_meta_title(lang('LeaveApplication.heading_title'));

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){
			$leaveapplication_id=$this->uri->getSegment(4);

			$this->leaveApplicationModel->editLeaveApplication($leaveapplication_id,$this->request->getPost());

			$this->session->setFlashdata('message', 'LeaveApplication Updated Successfully.');
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
		$this->leaveApplicationModel->deleteLeaveApplication($selected);
		$this->session->setFlashdata('message', 'LeaveApplication deleted Successfully.');
		redirect(ADMIN_PATH.'/leaveapplication');
	}

	protected function getList() {

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('LeaveApplication.heading_title'),
			'href' => admin_url('leaveapplication')
		);

		$this->template->add_package(array('datatable'),true);


		$data['add'] = admin_url('leaveapplication/add');
		$data['delete'] = admin_url('leaveapplication/delete');
		$data['datatable_url'] = admin_url('leaveapplication/search');

		$data['heading_title'] = lang('LeaveApplication.heading_title');

		$data['text_list'] = lang('LeaveApplication.text_list');
		$data['text_no_results'] = lang('LeaveApplication.text_no_results');
		$data['text_confirm'] = lang('LeaveApplication.text_confirm');

		$data['column_leaveapplicationname'] = lang('LeaveApplication.column_leaveapplicationname');
		$data['column_status'] = lang('LeaveApplication.column_status');
		$data['column_date_added'] = lang('LeaveApplication.column_date_added');
		$data['column_action'] = lang('LeaveApplication.column_action');

		$data['button_add'] = lang('LeaveApplication.button_add');
		$data['button_edit'] = lang('LeaveApplication.button_edit');
		$data['button_delete'] = lang('LeaveApplication.button_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\LeaveApplication\Views\leaveApplication', $data);
	}

	protected function getForm(){

		$this->template->add_package(array('datatable','select2','datepicker'),true);


		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('LeaveApplication.heading_title'),
			'href' => admin_url('leaveapplication')
		);

		$data['breadcrumbs'][] = array(
			'text' => lang('LeaveApplication.text_add'),
			'href' => admin_url('leaveapplication/add')
		);

		$_SESSION['isLoggedIn'] = true;

		$data['heading_title'] 	= lang('LeaveApplication.heading_title');

		$data['text_form'] = $this->uri->getSegment(4) ? lang('LeaveApplication.text_edit') : lang('LeaveApplication.text_add');
		$data['button_save'] = lang('LeaveApplication.button_save');
		$data['button_cancel'] = lang('LeaveApplication.button_cancel');
		$data['cancel'] = admin_url('leaveapplication');

		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->uri->getSegment(4) && ($this->request->getMethod('REQUEST_METHOD') != 'POST')) {
			$leaveapplication_info = $this->leaveApplicationModel->find($this->uri->getSegment(4));
		}

		foreach($this->leaveApplicationModel->getFieldNames($this->leaveApplicationModel->table) as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($leaveapplication_info->{$field}) && $leaveapplication_info->{$field}) {
				$data[$field] = $leaveapplication_info->{$field};
			} else {
				$data[$field] = '';
			}
		}



		$data['branches']=(new BranchModel())->getAll();
		$data['users']=(new UserModel())->getAll();
	


		echo $this->template->view('Admin\LeaveApplication\Views\leaveApplicationForm',$data);
	}

	protected function validateForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $rules = $this->leaveApplicationModel->validationRules;

        if ($this->validate($rules)){
            return true;
        }
        else{
            //printr($validation->getErrors());
            $this->error['warning']="Warning: Please check the form carefully for errors!";
            return false;
        }
        return !$this->error;
	}



}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */