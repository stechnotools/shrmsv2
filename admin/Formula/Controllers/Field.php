<?php
namespace Admin\Formula\Controllers;
use Admin\Formula\Models\FieldModel;
use App\Controllers\AdminController;

class Field extends AdminController {
	private $error = array();
	private $fieldModel;
	public function __construct(){
        $this->fieldModel=new FieldModel();
	}
	
	public function index(){
		$this->template->set_meta_title(lang('Field.heading_title'));
        return $this->getList();
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->fieldModel->getTotal();
		
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->fieldModel->getTotal($filter_data);
			
		$filteredData = $this->fieldModel->getAll($filter_data);
		//printr($filteredData);
		
		$datatable=array();
		foreach($filteredData as $result) {
			
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('formula/field/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('formula/field/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
			$action .= '</div>';


			$datatable[]=array(
				'<div class="checkbox checkbox-primary checkbox-single">
					<input type="checkbox" name="selected[]" value="'.$result->id.'" />
					<label></label>
				</div>',
				$result->group,
				$result->code,
				$result->name,
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
		$this->template->set_meta_title(lang('Field.heading_title'));
		
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			
			$this->fieldModel->insert($this->request->getPost());
			
			$this->session->setFlashdata('message', 'Field Saved Successfully.');
			return redirect()->to(admin_url('formula/field'));
		
		}
		$this->getForm();
	}
	
	public function edit(){
		
		$this->template->set_meta_title(lang('Field.heading_title'));
		
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			$field_id=$this->uri->getSegment(4);
			$this->fieldModel->update($field_id,$this->request->getPost());
			
			$this->session->setFlashdata('message', 'Field Updated Successfully.');
			return redirect()->to(admin_url('formula/field'));
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->request->getPost('selected')){
         	$selected = $this->request->getPost('selected');
      	}else{
         	$selected = (array) $this->uri->getSegment(4);
       	}
		$this->fieldModel->whereIn("id",$selected)->delete();
		$this->session->setFlashdata('message', 'Field deleted Successfully.');
		return redirect()->to(admin_url('formula/field'));
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Field.heading_title'),
			'href' => admin_url('field')
		);
		
		$this->template->add_package(array('datatable'),true);
      

		$data['add'] = admin_url('formula/field/add');
		$data['delete'] = admin_url('formula/field/delete');
		$data['datatable_url'] = admin_url('formula/field/search');

		$data['heading_title'] = lang('Field.heading_title');
		
		$data['text_list'] = lang('Field.text_list');
		$data['text_no_results'] = lang('Field.text_no_results');
		$data['text_confirm'] = lang('Field.text_confirm');

		$data['column_fieldname'] = lang('Field.column_fieldname');
		$data['column_status'] = lang('Field.column_status');
		$data['column_date_added'] = lang('Field.column_date_added');
		$data['column_action'] = lang('Field.column_action');

		$data['button_add'] = lang('Field.button_add');
		$data['button_edit'] = lang('Field.button_edit');
		$data['button_delete'] = lang('Field.button_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Formula\Views\field', $data);
	}
	
	protected function getForm(){
		
		$this->template->add_package(array('ckeditor','ckfinder','colorbox','select2'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Field.heading_title'),
			'href' => admin_url('field')
		);

		$data['breadcrumbs'][] = array(
			'text' => lang('Field.text_add'),
			'href' => admin_url('field/add')
		);

		$_SESSION['isLoggedIn'] = true;
		
		$data['heading_title'] 	= lang('Field.heading_title');
		
		$data['text_form'] = $this->uri->getSegment(4) ? lang('Field.text_edit') : lang('Field.text_add');
		$data['button_save'] = lang('Field.button_save');
		$data['button_cancel'] = lang('Field.button_cancel');
		$data['cancel'] = admin_url('formula/field');
		
		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->uri->getSegment(5) && ($this->request->getMethod('REQUEST_METHOD') != 'POST')) {
			$field_info = $this->fieldModel->find($this->uri->getSegment(5));
		}

		foreach($this->fieldModel->getFieldNames($this->fieldModel->table) as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($field_info->{$field}) && $field_info->{$field}) {
				$data[$field] = $field_info->{$field};
			} else {
				$data[$field] = '';
			}
		}
		
		$data['field_groups']=array(
			'attendance_field'=>'Attendance Field',
			'actual_earned_deduction'=>'Actual Earned/Deduction',
			'actual_salary_field'=>'Actual Salary Field',
			'earned_salary_field'=>'Earned Salary Field',
			'earned_earned_deduction'=>'Earned Earned/Deduction'
		);
	
		echo $this->template->view('Admin\Formula\Views\fieldForm',$data);
	}

	protected function validateForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $rules = $this->fieldModel->validationRules;

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