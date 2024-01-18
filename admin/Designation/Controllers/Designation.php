<?php
namespace Admin\Designation\Controllers;
use Admin\Designation\Models\DesignationModel;
use App\Controllers\AdminController;

class Designation extends AdminController {
	private $error = array();
	
	public function __construct(){
        $this->designationModel=new DesignationModel();
	}
	
	public function index(){
		$this->template->set_meta_title(lang('Designation.heading_title'));
        return $this->getList();
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->designationModel->getTotal();
		
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->designationModel->getTotal($filter_data);
			
		$filteredData = $this->designationModel->getAll($filter_data);
		//printr($filteredData);
		
		$datatable=array();
		foreach($filteredData as $result) {
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('designation/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('designation/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
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
		$this->template->set_meta_title(lang('Designation.heading_title'));
		
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			
			$this->designationModel->insert($this->request->getPost());
			
			$this->session->setFlashdata('message', 'Designation Saved Successfully.');
			return redirect()->to(admin_url('designation'));
		
		}
		$this->getForm();
	}
	
	public function edit(){
		
		$this->template->set_meta_title(lang('Designation.heading_title'));
		
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			$designation_id=$this->uri->getSegment(4);
			$this->designationModel->update($designation_id,$this->request->getPost());
			
			$this->session->setFlashdata('message', 'Designation Updated Successfully.');
			return redirect()->to(admin_url('designation'));
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->request->getPost('selected')){
         $selected = $this->request->getPost('selected');
      }else{
         $selected = (array) $this->uri->getSegment(4);
       }
		$this->designationModel->deleteDesignation($selected);
		$this->session->setFlashdata('message', 'Designation deleted Successfully.');
		redirect(ADMIN_PATH.'/designation');
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Designation.heading_title'),
			'href' => admin_url('designation')
		);
		
		$this->template->add_package(array('datatable'),true);
      

		$data['add'] = admin_url('designation/add');
		$data['delete'] = admin_url('designation/delete');
		$data['datatable_url'] = admin_url('designation/search');

		$data['heading_title'] = lang('Designation.heading_title');
		
		$data['text_list'] = lang('Designation.text_list');
		$data['text_no_results'] = lang('Designation.text_no_results');
		$data['text_confirm'] = lang('Designation.text_confirm');

		$data['column_designationname'] = lang('Designation.column_designationname');
		$data['column_status'] = lang('Designation.column_status');
		$data['column_date_added'] = lang('Designation.column_date_added');
		$data['column_action'] = lang('Designation.column_action');

		$data['button_add'] = lang('Designation.button_add');
		$data['button_edit'] = lang('Designation.button_edit');
		$data['button_delete'] = lang('Designation.button_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Designation\Views\designation', $data);
	}
	
	protected function getForm(){
		
		$this->template->add_package(array('ckeditor','ckfinder','colorbox','select2'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Designation.heading_title'),
			'href' => admin_url('designation')
		);

		$data['breadcrumbs'][] = array(
			'text' => lang('Designation.text_add'),
			'href' => admin_url('designation/add')
		);

		$_SESSION['isLoggedIn'] = true;
		
		$data['heading_title'] 	= lang('Designation.heading_title');
		
		$data['text_form'] = $this->uri->getSegment(4) ? lang('Designation.text_edit') : lang('Designation.text_add');
		$data['button_save'] = lang('Designation.button_save');
		$data['button_cancel'] = lang('Designation.button_cancel');
		$data['cancel'] = admin_url('designation');
		
		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->uri->getSegment(4) && ($this->request->getMethod('REQUEST_METHOD') != 'POST')) {
			$designation_info = $this->designationModel->find($this->uri->getSegment(4));
		}

		foreach($this->designationModel->getFieldNames($this->designationModel->table) as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($designation_info->{$field}) && $designation_info->{$field}) {
				$data[$field] = $designation_info->{$field};
			} else {
				$data[$field] = '';
			}
		}
		
	
		echo $this->template->view('Admin\Designation\Views\designationForm',$data);
	}

	protected function validateForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $rules = $this->designationModel->validationRules;

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