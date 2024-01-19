<?php
namespace Admin\Department\Controllers;
use Admin\Department\Models\DepartmentModel;
use Admin\Hod\Models\HodModel;
use App\Controllers\AdminController;

class Department extends AdminController {
	private $error = array();
	private $departmentModel;
	public function __construct(){
        $this->departmentModel=new DepartmentModel();
	}

	public function index(){
		$this->template->set_meta_title(lang('Department.heading_title'));
        return $this->getList();
	}

	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->departmentModel->getTotal();

		$totalFiltered = $totalData;

		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->departmentModel->getTotal($filter_data);

		$filteredData = $this->departmentModel->getAll($filter_data);
		//printr($filteredData);

		$datatable=array();
		foreach($filteredData as $result) {

			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('department/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('department/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
			$action .= '</div>';

			$datatable[]=array(
				'<div class="checkbox checkbox-primary checkbox-single">
					<input type="checkbox" name="selected[]" value="'.$result->id.'" />
					<label></label>
				</div>',
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

		return $this->response->setContentType('application/json')
            ->setJSON($json_data);  // send data as json format
	}

	public function add(){
		$this->template->set_meta_title(lang('Department.heading_title'));

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){

			$this->departmentModel->insert($this->request->getPost());

			$this->session->setFlashdata('message', 'Department Saved Successfully.');
			if($this->request->isAJAX()){
                echo "1";
                exit;
            }else{
                return redirect()->to(admin_url('department'));
            }

		}
		$this->getForm();
	}

	public function edit(){

		$this->template->set_meta_title(lang('Department.heading_title'));

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){
			$department_id=$this->uri->getSegment(4);
			$this->departmentModel->update($department_id,$this->request->getPost());

			$this->session->setFlashdata('message', 'Department Updated Successfully.');
			if($this->request->isAJAX()){
                echo "1";
                exit;
            }else{
                return redirect()->to(admin_url('department'));
            }

		}
		$this->getForm();
	}

	public function delete(){
		if ($this->request->getPost('selected')){
         $selected = $this->request->getPost('selected');
      }else{
         $selected = (array) $this->uri->getSegment(4);
       }
		$this->departmentModel->whereIn('id', $selected)->delete();
		$this->session->setFlashdata('message', 'Department deleted Successfully.');
		return redirect()->to(admin_url('department'));
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

		$data['button_add'] = lang('Department.text_add');
		$data['button_edit'] = lang('Department.text_edit');
		$data['button_delete'] = lang('Department.text_delete');

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

		$this->template->add_package(array('select2'),true);

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

		$data['text_form'] = $this->uri->getSegment(4) ? lang('Department.text_edit') : lang('Department.text_add');
		$data['button_save'] = lang('Department.button_save');
		$data['button_cancel'] = lang('Department.button_cancel');
		$data['cancel'] = admin_url('department');

		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->uri->getSegment(4) && ($this->request->getMethod('REQUEST_METHOD') != 'POST')) {
			$department_info = $this->departmentModel->find($this->uri->getSegment(4));
		}

		foreach($this->departmentModel->getFieldNames($this->departmentModel->table) as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($department_info->{$field}) && $department_info->{$field}) {
				$data[$field] = $department_info->{$field};
			} else {
				$data[$field] = '';
			}
		}

		$data['hods']=(new HodModel())->getAll();


		echo $this->template->view('Admin\Department\Views\departmentForm',$data);
	}

	protected function validateForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $rules = $this->departmentModel->validationRules;

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