<?php
namespace Admin\Punch\Controllers;

use Admin\Bank\Models\BankModel;
use Admin\Branch\Models\BranchModel;
use Admin\Category\Models\CategoryModel;
use Admin\Department\Models\DepartmentModel;
use Admin\Designation\Models\DesignationModel;
use Admin\Employee\Controllers\Employee;
use Admin\Punch\Models\PunchModel;
use Admin\Grade\Models\GradeModel;
use Admin\Hod\Models\HodModel;
use Admin\Section\Controllers\Section;
use Admin\Section\Models\SectionModel;
use Admin\Shift\Models\ShiftModel;
use App\Controllers\AdminController;

class Punch extends AdminController {
	private $error = array();
	private  $punchModel;
	public function __construct(){
        $this->punchModel=new PunchModel();
	}
	
	public function index(){
		$this->template->set_meta_title(lang('Punch.heading_title'));
        return $this->getList();
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->punchModel->getTotal();
		
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->punchModel->getTotal($filter_data);
			
		$filteredData = $this->punchModel->getAll($filter_data);
		//printr($filteredData);
		
		$datatable=array();
		foreach($filteredData as $result) {
			if (is_file(DIR_UPLOAD . $result->image)) {
				$image = resize($result->image, 40, 40);
			} else {
				$image = resize('no_image.png', 40, 40);
			}
			
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('punch/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('punch/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
			$action .= '</div>';

			$datatable[]=array(
				'<div class="checkbox checkbox-primary checkbox-single">
					<input type="checkbox" name="selected[]" value="'.$result->id.'" />
					<label></label>
				</div>',
				'<img src="'.$image.'" alt="'.$result->punch_name.'" class="img-fluid" />',
				$result->punch_name,
				$result->card_no,
				$result->mobile,
				$result->enabled ? 'Enable':'Disable',
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
		$this->template->set_meta_title(lang('Punch.heading_title'));
		
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			
			$this->punchModel->addPunch($this->request->getPost());
			
			$this->session->setFlashdata('message', 'Punch Saved Successfully.');
			return redirect()->to(admin_url('punch'));
		
		}
		$this->getForm();
	}
	
	public function edit(){
		
		$this->template->set_meta_title(lang('Punch.heading_title'));
		
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			$punch_id=$this->uri->getSegment(4);
			$this->punchModel->editPunch($punch_id,$this->request->getPost());
			
			$this->session->setFlashdata('message', 'Punch Updated Successfully.');
			return redirect()->to(admin_url('punch'));
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->request->getPost('selected')){
         $selected = $this->request->getPost('selected');
      }else{
         $selected = (array) $this->uri->getSegment(4);
       }
		$this->punchModel->deletePunch($selected);
		$this->session->setFlashdata('message', 'Punch deleted Successfully.');
		return redirect()->to(admin_url('punch'));
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Punch.heading_title'),
			'href' => admin_url('punch')
		);
		
		$this->template->add_package(array('datatable','select2','daterangepicker'),true);
      

		$data['add'] = admin_url('punch/add');
		$data['delete'] = admin_url('punch/delete');
		$data['datatable_url'] = admin_url('punch/search');

		$data['heading_title'] = lang('Punch.heading_title');
		
		$data['text_list'] = lang('Punch.text_list');
		$data['text_no_results'] = lang('Punch.text_no_results');
		$data['text_confirm'] = lang('Punch.text_confirm');

		$data['column_punchname'] = lang('Punch.column_punchname');
		$data['column_status'] = lang('Punch.column_status');
		$data['column_date_added'] = lang('Punch.column_date_added');
		$data['column_action'] = lang('Punch.column_action');

		$data['button_add'] = lang('Punch.button_add');
		$data['button_edit'] = lang('Punch.button_edit');
		$data['button_delete'] = lang('Punch.button_delete');

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
		
		
		return $this->template->view('Admin\Punch\Views\punch', $data);
	}
	
	protected function getForm(){
		
		$this->template->add_package(array('datatable','datepicker','timepicker','select2'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Punch.heading_title'),
			'href' => admin_url('punch')
		);

		$data['breadcrumbs'][] = array(
			'text' => lang('Punch.text_add'),
			'href' => admin_url('punch/add')
		);

		$_SESSION['isLoggedIn'] = true;
		
		$data['heading_title'] 	= lang('Punch.heading_title');
		
		$data['text_form'] = $this->uri->getSegment(4) ? lang('Punch.text_edit') : lang('Punch.text_add');
		$data['button_save'] = lang('Punch.button_save');
		$data['button_cancel'] = lang('Punch.button_cancel');
		
		$data['cancel'] = admin_url('punch');
		$data['save'] = admin_url('punch/save');
		
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
			$punch_info = $this->punchModel->find($id);
			//$emp_info = $this->employee_model->getEmployeeOffice($mpunch_info->user_id);
			$data['punch_user_id']=$mpunch_info->user_id;
		}else{
			//$emp_info = $this->employee_model->getEmployeeOffice($user_id);
			$data['punch_user_id']=$user_id;
		}
		
		foreach($this->punchModel->getFieldNames('punch') as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($punch_info->{$field}) && $punch_info->{$field}) {
				$data[$field] = $punch_info->{$field};
			} else {
				$data[$field] = '';
			}
		}

		if(isset($punch_info->punch_date)) {
			$data['punch_date'] = $punch_info->punch_date;
		} else {
			$data['punch_date'] = date("d-m-Y");
		}
	
		$data['employee_details']= $this->template->view('Admin\Employee\Views\employeeDetailsAttachForm',[],true);
		
		echo $this->template->view('Admin\Punch\Views\punchForm',$data);
	}

	protected function validateForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $rules = $this->punchModel->validationRules;

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