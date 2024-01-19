<?php
namespace Admin\Grade\Controllers;
use Admin\Grade\Models\GradeModel;
use App\Controllers\AdminController;

class Grade extends AdminController {
	private $error = array();
	
	public function __construct(){
        $this->gradeModel=new GradeModel();
	}
	
	public function index(){
		$this->template->set_meta_title(lang('Grade.heading_title'));
        return $this->getList();
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->gradeModel->getTotal();
		
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->gradeModel->getTotal($filter_data);
			
		$filteredData = $this->gradeModel->getAll($filter_data);
		//printr($filteredData);
		
		$datatable=array();
		foreach($filteredData as $result) {
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('grade/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('grade/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
			$action .= '</div>';

			$datatable[]=array(
				'<div class="checkbox checkbox-primary checkbox-single">
					<input type="checkbox" name="selected[]" value="'.$result->id.'" />
					<label></label>
				</div>',
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
		$this->template->set_meta_title(lang('Grade.heading_title'));
		
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			
			$this->gradeModel->insert($this->request->getPost());
			
			$this->session->setFlashdata('message', 'Grade Saved Successfully.');
			return redirect()->to(admin_url('grade'));
		
		}
		$this->getForm();
	}
	
	public function edit(){
		
		$this->template->set_meta_title(lang('Grade.heading_title'));
		
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			$grade_id=$this->uri->getSegment(4);
			$this->gradeModel->update($grade_id,$this->request->getPost());
			
			$this->session->setFlashdata('message', 'Grade Updated Successfully.');
			return redirect()->to(admin_url('grade'));
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->request->getPost('selected')){
         $selected = $this->request->getPost('selected');
      }else{
         $selected = (array) $this->uri->getSegment(4);
       }
		$this->gradeModel->deleteGrade($selected);
		$this->session->setFlashdata('message', 'Grade deleted Successfully.');
		redirect(ADMIN_PATH.'/grade');
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Grade.heading_title'),
			'href' => admin_url('grade')
		);
		
		$this->template->add_package(array('datatable'),true);
      

		$data['add'] = admin_url('grade/add');
		$data['delete'] = admin_url('grade/delete');
		$data['datatable_url'] = admin_url('grade/search');

		$data['heading_title'] = lang('Grade.heading_title');
		
		$data['text_list'] = lang('Grade.text_list');
		$data['text_no_results'] = lang('Grade.text_no_results');
		$data['text_confirm'] = lang('Grade.text_confirm');

		$data['column_gradename'] = lang('Grade.column_gradename');
		$data['column_status'] = lang('Grade.column_status');
		$data['column_date_added'] = lang('Grade.column_date_added');
		$data['column_action'] = lang('Grade.column_action');

		$data['button_add'] = lang('Grade.button_add');
		$data['button_edit'] = lang('Grade.button_edit');
		$data['button_delete'] = lang('Grade.button_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Grade\Views\grade', $data);
	}
	
	protected function getForm(){
		
		$this->template->add_package(array('ckeditor','ckfinder','colorbox','select2'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Grade.heading_title'),
			'href' => admin_url('grade')
		);

		$data['breadcrumbs'][] = array(
			'text' => lang('Grade.text_add'),
			'href' => admin_url('grade/add')
		);

		$_SESSION['isLoggedIn'] = true;
		
		$data['heading_title'] 	= lang('Grade.heading_title');
		
		$data['text_form'] = $this->uri->getSegment(4) ? lang('Grade.text_edit') : lang('Grade.text_add');
		$data['button_save'] = lang('Grade.button_save');
		$data['button_cancel'] = lang('Grade.button_cancel');
		$data['cancel'] = admin_url('grade');
		
		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->uri->getSegment(4) && ($this->request->getMethod('REQUEST_METHOD') != 'POST')) {
			$grade_info = $this->gradeModel->find($this->uri->getSegment(4));
		}

		foreach($this->gradeModel->getFieldNames($this->gradeModel->table) as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($grade_info->{$field}) && $grade_info->{$field}) {
				$data[$field] = $grade_info->{$field};
			} else {
				$data[$field] = '';
			}
		}
		
	
		echo $this->template->view('Admin\Grade\Views\gradeForm',$data);
	}

	protected function validateForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $rules = $this->gradeModel->validationRules;

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