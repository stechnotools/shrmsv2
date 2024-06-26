<?php
namespace Admin\Leave\Controllers;

use Admin\Branch\Models\BranchModel;
use Admin\Department\Models\DepartmentModel;
use Admin\Leave\Models\LeaveModel;
use Admin\Leave\Models\LeaveOpeningModel;
use App\Controllers\AdminController;
class Opening extends AdminController {
	private $error = array();
	private $leaveOpeningModel;
	public function __construct(){
        $this->leaveOpeningModel=new LeaveOpeningModel();
	}

	public function index(){
		$this->template->set_meta_title(lang('LeaveOpening.heading_title'));
        return $this->getList();
	}

	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->leaveOpeningModel->getTotal();

		$totalFiltered = $totalData;

		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->leaveOpeningModel->getTotal($filter_data);

		$filteredData = $this->leaveOpeningModel->getAll($filter_data);
		//printr($filteredData);
		$datatable=array();
		foreach($filteredData as $result) {
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('leave/opening/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('leave/opening/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
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
		$this->template->set_meta_title(lang('LeaveOpening.heading_title'));

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){

			$this->leaveOpeningModel->addLeaveOpening($this->request->getPost());

			$this->session->setFlashdata('message', 'LeaveOpening Saved Successfully.');
			return redirect()->to(admin_url('leaveopening'));

		}
		$this->getForm();
	}

	public function edit(){

		$this->template->set_meta_title(lang('LeaveOpening.heading_title'));

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){
			$leaveopening_id=$this->uri->getSegment(4);

			$this->leaveOpeningModel->editLeaveOpening($leaveopening_id,$this->request->getPost());

			$this->session->setFlashdata('message', 'LeaveOpening Updated Successfully.');
			return redirect()->to(admin_url('leaveopening'));
		}
		$this->getForm();
	}

	public function delete(){
		if ($this->request->getPost('selected')){
         $selected = $this->request->getPost('selected');
      }else{
         $selected = (array) $this->uri->getSegment(4);
       }
		$this->leaveOpeningModel->deleteLeaveOpening($selected);
		$this->session->setFlashdata('message', 'LeaveOpening deleted Successfully.');
		redirect(ADMIN_PATH.'/leave/opening');
	}

	protected function getList() {

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('LeaveOpening.heading_title'),
			'href' => admin_url('leave/opening')
		);

		$this->template->add_package(array('datatable'),true);


		$data['add'] = admin_url('leave/opening/add');
		$data['delete'] = admin_url('leave/opening/delete');
		$data['datatable_url'] = admin_url('leave/opening/search');

		$data['heading_title'] = lang('LeaveOpening.heading_title');

		$data['text_list'] = lang('LeaveOpening.text_list');
		$data['text_no_results'] = lang('LeaveOpening.text_no_results');
		$data['text_confirm'] = lang('LeaveOpening.text_confirm');

		$data['column_leaveopeningname'] = lang('LeaveOpening.column_leaveopeningname');
		$data['column_status'] = lang('LeaveOpening.column_status');
		$data['column_date_added'] = lang('LeaveOpening.column_date_added');
		$data['column_action'] = lang('LeaveOpening.column_action');

		$data['button_add'] = lang('LeaveOpening.button_add');
		$data['button_edit'] = lang('LeaveOpening.button_edit');
		$data['button_delete'] = lang('LeaveOpening.button_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Leave\Views\leaveOpening', $data);
	}

	protected function getForm(){

		$this->template->add_package(array('datatable','select2','datepicker'),true);


		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('LeaveOpening.heading_title'),
			'href' => admin_url('leave/opening')
		);

		$data['breadcrumbs'][] = array(
			'text' => lang('LeaveOpening.text_add'),
			'href' => admin_url('leave/opening/add')
		);

		$_SESSION['isLoggedIn'] = true;

		$data['heading_title'] 	= lang('LeaveOpening.heading_title');

		$data['text_form'] = $this->uri->getSegment(4) ? lang('LeaveOpening.text_edit') : lang('LeaveOpening.text_add');
		$data['button_save'] = lang('LeaveOpening.button_save');
		$data['button_cancel'] = lang('LeaveOpening.button_cancel');
		$data['cancel'] = admin_url('leave/opening');

		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->uri->getSegment(5) && ($this->request->getMethod('REQUEST_METHOD') != 'POST')) {
			$leaveopening_info = $this->leaveOpeningModel->find($this->uri->getSegment(5));
		}

		foreach($this->leaveOpeningModel->getFieldNames($this->leaveOpeningModel->table) as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($leaveopening_info->{$field}) && $leaveopening_info->{$field}) {
				$data[$field] = $leaveopening_info->{$field};
			} else {
				$data[$field] = '';
			}
		}

		$data['fyear']=date("Y",strtotime($data['year_from'])).'-'.date("Y",strtotime($data['year_to']));

		if ($this->request->getPost('leave_field')) {
			$data['leave_fields'] = $this->request->getPost('leave_field');
		} elseif ($this->uri->getSegment(5)) {
			$leavefields = $this->leaveOpeningModel->getLeaveOpeningValues($this->uri->getSegment(5));
			foreach($leavefields as $leavefield){
				$data['leave_fields'][$leavefield->leave_id]=$leavefield->value;
			}
		} else {
			$data['leave_fields'] = array();
		}

		$data['types']=array(
			0=>'Select Type',
			'user'=>'Employee',
			'department'=>'Department',
			'branch'=>'Branch'
		);


		$data['branches']=(new BranchModel())->getAll();
		$data['leaves']=(new LeaveModel())->getAll();
		$data['departments']=(new DepartmentModel())->getAll();


		echo $this->template->view('Admin\Leave\Views\leaveOpeningForm',$data);
	}

	protected function validateForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $rules = $this->leaveOpeningModel->validationRules;

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