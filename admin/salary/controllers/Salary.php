<?php
namespace Admin\Salary\Controllers;

use Admin\Branch\Models\BranchModel;
use Admin\Department\Models\DepartmentModel;
use Admin\Designation\Models\DesignationModel;
use Admin\Leave\Models\LeaveModel;
use Admin\Salary\Models\SalaryModel;
use App\Controllers\AdminController;

class Salary extends AdminController {
	private $error = array();
	private $salaryModel;
	public function __construct(){
        $this->salaryModel=new SalaryModel();
	}

	public function index(){
		$this->template->set_meta_title(lang('Salary.heading_title'));
        return $this->getList();
	}

	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->salaryModel->getTotal();

		$totalFiltered = $totalData;

		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->salaryModel->getTotal($filter_data);

		$filteredData = $this->salaryModel->getAll($filter_data);
		//printr($filteredData);

		$datatable=array();
		foreach($filteredData as $result) {

			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('salary/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('salary/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
			$action .= '</div>';

			$datatable[]=array(
				'<div class="checkbox checkbox-primary checkbox-single">
					<input type="checkbox" name="selected[]" value="'.$result->id.'" />
					<label></label>
				</div>',
				$result->name,
				$result->type,
				$result->status?'Yes':'No',
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
		$this->template->set_meta_title(lang('Salary.heading_title'));

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){

			$this->salaryModel->insert($this->request->getPost());

			$this->session->setFlashdata('message', 'Salary Saved Successfully.');
			if($this->request->isAJAX()){
                echo "1";
                exit;
            }else{
                return redirect()->to(admin_url('salary'));
            }

		}
		$this->getForm();
	}

	public function edit(){

		$this->template->set_meta_title(lang('Salary.heading_title'));

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){
			$salary_id=$this->uri->getSegment(4);
			$this->salaryModel->update($salary_id,$this->request->getPost());

			$this->session->setFlashdata('message', 'Salary Updated Successfully.');
			if($this->request->isAJAX()){
                echo "1";
                exit;
            }else{
                return redirect()->to(admin_url('salary'));
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
		$this->salaryModel->whereIn('id', $selected)->delete();
		$this->session->setFlashdata('message', 'Salary deleted Successfully.');
		return redirect()->to(admin_url('salary'));
	}

	protected function getList() {

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Salary.heading_title'),
			'href' => admin_url('salary')
		);

		$this->template->add_package(array('datatable'),true);


		$data['add'] = admin_url('salary/add');
		$data['delete'] = admin_url('salary/delete');
		$data['datatable_url'] = admin_url('salary/search');

		$data['heading_title'] = lang('Salary.heading_title');

		$data['text_list'] = lang('Salary.text_list');
		$data['text_no_results'] = lang('Salary.text_no_results');
		$data['text_confirm'] = lang('Salary.text_confirm');

		$data['column_salaryname'] = lang('Salary.column_salaryname');
		$data['column_status'] = lang('Salary.column_status');
		$data['column_date_added'] = lang('Salary.column_date_added');
		$data['column_action'] = lang('Salary.column_action');

		$data['button_add'] = lang('Salary.text_add');
		$data['button_edit'] = lang('Salary.text_edit');
		$data['button_delete'] = lang('Salary.text_delete');

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
		

		return $this->template->view('Admin\Salary\Views\salary', $data);
	}

	protected function getForm(){

		$this->template->add_package(array('select2'),true);

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Salary.heading_title'),
			'href' => admin_url('salary')
		);

		$data['breadcrumbs'][] = array(
			'text' => lang('Salary.text_add'),
			'href' => admin_url('salary/add')
		);

		$_SESSION['isLoggedIn'] = true;

		$data['heading_title'] 	= lang('Salary.heading_title');

		$data['text_form'] = $this->uri->getSegment(4) ? lang('Salary.text_edit') : lang('Salary.text_add');
		$data['button_save'] = lang('Salary.button_save');
		$data['button_cancel'] = lang('Salary.button_cancel');
		$data['cancel'] = admin_url('salary');

		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->uri->getSegment(4) && ($this->request->getMethod('REQUEST_METHOD') != 'POST')) {
			$salary_info = $this->salaryModel->find($this->uri->getSegment(4));
		}

		foreach($this->salaryModel->getFieldNames($this->salaryModel->table) as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($salary_info->{$field}) && $salary_info->{$field}) {
				$data[$field] = $salary_info->{$field};
			} else {
				$data[$field] = '';
			}
		}

		
		echo $this->template->view('Admin\Salary\Views\salaryForm',$data);
	}

	protected function validateForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $rules = $this->salaryModel->validationRules;

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
/* Salary: ./application/widgets/hmvc/controllers/hmvc.php */