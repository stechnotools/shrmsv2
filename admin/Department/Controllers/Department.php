<?php
namespace Admin\Department\Controllers;
use Admin\Department\Models\DepartmentModel;
use App\Controllers\AdminController;

class Department extends AdminController {
	private $error = array();
	
	public function __construct(){
        $this->departmentModel=new DepartmentModel();
	}
	
	public function index(){
		$this->template->set_meta_title(lang('Department.heading_title'));
        return $this->getList();
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->departmentModel->getTotalDepartments();
		
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->departmentModel->getTotalDepartments($filter_data);
			
		$filteredData = $this->departmentModel->getDepartments($filter_data);
		//printr($filteredData);
		
		$datatable=array();
		foreach($filteredData as $result) {
			
			
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('department/edit/'.$result->id).'"><i class="fa fa-pencil"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('department/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';
			$action .= '</div>';

			$datatable[]=array(
				'<request type="checkbox" name="selected[]" value="'.$result->id.'" />',
				$result->code,
				$result->name,
				$result->hod,
				$result->email,
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
		$this->template->set_meta_title(lang('Department.heading_title'));
		
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			
			$this->departmentModel->addDepartment($this->request->getPost());
			
			$this->session->setFlashdata('message', 'Department Saved Successfully.');
			redirect(ADMIN_PATH.'/department');
		}
		$this->getForm();
	}
	
	public function edit(){
		
		$this->lang->load('department');
		$this->template->set_meta_title(lang('Department.heading_title'));
		
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			$department_id=$this->uri->segment(4);
			$this->departmentModel->editDepartment($department_id,$this->request->getPost());
			
			$this->session->setFlashdata('message', 'Department Updated Successfully.');
			redirect(ADMIN_PATH.'/department');
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->request->getPost('selected')){
         $selected = $this->request->getPost('selected');
      }else{
         $selected = (array) $this->uri->segment(4);
       }
		$this->departmentModel->deleteDepartment($selected);
		$this->session->setFlashdata('message', 'Department deleted Successfully.');
		redirect(ADMIN_PATH.'/department');
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Department.heading_title'),
			'href' => admin_url('department')
		);
		
		$this->template->add_package(array('datatable'),true);
      

		$data['add'] = admin_url('department/add');
		$data['delete'] = admin_url('department/delete');
		$data['datatable_url'] = admin_url('department/search');

		$data['heading_title'] = lang('Department.heading_title');
		
		$data['text_list'] = lang('Department.text_list');
		$data['text_no_results'] = lang('Department.text_no_results');
		$data['text_confirm'] = lang('Department.text_confirm');

		$data['column_departmentname'] = lang('Department.column_departmentname');
		$data['column_status'] = lang('Department.column_status');
		$data['column_date_added'] = lang('Department.column_date_added');
		$data['column_action'] = lang('Department.column_action');

		$data['button_add'] = lang('Department.button_add');
		$data['button_edit'] = lang('Department.button_edit');
		$data['button_delete'] = lang('Department.button_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Department\Views\department', $data);
	}
	
	protected function getForm(){
		
		$data = $this->lang->load('department');
		$this->template->add_package(array('ckeditor','ckfinder','colorbox','select2'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Department.heading_title'),
			'href' => admin_url('department')
		);

		$data['breadcrumbs'][] = array(
			'text' => lang('Department.text_add'),
			'href' => admin_url('department/add')
		);

		$_SESSION['isLoggedIn'] = true;
		
		$data['heading_title'] 	= lang('Department.heading_title');
		
		$data['text_form'] = $this->uri->segment(4) ? lang('Department.text_edit') : lang('Department.text_add');
		$data['button_save'] = lang('Department.button_save');
		$data['button_cancel'] = lang('Department.button_cancel');
		$data['cancel'] = admin_url('department');
		
		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->uri->segment(4) && ($this->request->getMethod('REQUEST_METHOD') != 'POST')) {
			$department_info = $this->departmentModel->getDepartment($this->uri->segment(4));
		}

		foreach($this->departmentModel->getTableColumns() as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($department_info->{$field}) && $department_info->{$field}) {
				$data[$field] = $department_info->{$field};
			} else {
				$data[$field] = '';
			}
		}
		
		$this->template->view('departmentForm',$data);
	}

	protected function validateForm() {
		$department_id=$this->uri->segment(4);
		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
      	$regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
      	$regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor 
		$rules=array(
			'name' => array(
				'field' => 'name', 
				'label' => 'Department Name', 
				'rules' => 'trim|required|max_length[100]'
			),
			
			'code' => array(
				'field' => 'code', 
				'label' => 'Department Code', 
				'rules' => "trim|required"
			),
		);
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() == TRUE)
		{
			return true;
    	}
		else
		{
			$this->error['warning']=lang('Department.error_warning');
			return false;
    	}
		return !$this->error;
	}

	
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */