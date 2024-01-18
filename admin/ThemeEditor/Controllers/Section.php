<?php
namespace Admin\Section\Controllers;
use Admin\Section\Models\SectionModel;
use App\Controllers\AdminController;

class Section extends AdminController {
	private $error = array();
	private $sectionModel;
	public function __construct(){
        $this->sectionModel=new SectionModel();
	}
	
	public function index(){
		$this->template->set_meta_title(lang('Section.heading_title'));
        return $this->getList();
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->sectionModel->getTotal();
		
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->sectionModel->getTotal($filter_data);
			
		$filteredData = $this->sectionModel->getAll($filter_data);
		//printr($filteredData);
		
		$datatable=array();
		foreach($filteredData as $result) {
			
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('section/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('section/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
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
		$this->template->set_meta_title(lang('Section.heading_title'));
		
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			
			$this->sectionModel->insert($this->request->getPost());
			
			$this->session->setFlashdata('message', 'Section Saved Successfully.');
			return redirect()->to(admin_url('section'));
		
		}
		$this->getForm();
	}
	
	public function edit(){
		
		$this->template->set_meta_title(lang('Section.heading_title'));
		
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			$section_id=$this->uri->getSegment(4);
			$this->sectionModel->update($section_id,$this->request->getPost());
			
			$this->session->setFlashdata('message', 'Section Updated Successfully.');
			return redirect()->to(admin_url('section'));
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->request->getPost('selected')){
         $selected = $this->request->getPost('selected');
      }else{
         $selected = (array) $this->uri->getSegment(4);
       }
		$this->sectionModel->deleteSection($selected);
		$this->session->setFlashdata('message', 'Section deleted Successfully.');
		redirect(ADMIN_PATH.'/section');
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Section.heading_title'),
			'href' => admin_url('section')
		);
		
		$this->template->add_package(array('datatable'),true);
      

		$data['add'] = admin_url('section/add');
		$data['delete'] = admin_url('section/delete');
		$data['datatable_url'] = admin_url('section/search');

		$data['heading_title'] = lang('Section.heading_title');
		
		$data['text_list'] = lang('Section.text_list');
		$data['text_no_results'] = lang('Section.text_no_results');
		$data['text_confirm'] = lang('Section.text_confirm');

		$data['column_sectionname'] = lang('Section.column_sectionname');
		$data['column_status'] = lang('Section.column_status');
		$data['column_date_added'] = lang('Section.column_date_added');
		$data['column_action'] = lang('Section.column_action');

		$data['button_add'] = lang('Section.button_add');
		$data['button_edit'] = lang('Section.button_edit');
		$data['button_delete'] = lang('Section.button_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Section\Views\section', $data);
	}
	
	protected function getForm(){
		
		$this->template->add_package(array('ckeditor','ckfinder','colorbox','select2'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Section.heading_title'),
			'href' => admin_url('section')
		);

		$data['breadcrumbs'][] = array(
			'text' => lang('Section.text_add'),
			'href' => admin_url('section/add')
		);

		$_SESSION['isLoggedIn'] = true;
		
		$data['heading_title'] 	= lang('Section.heading_title');
		
		$data['text_form'] = $this->uri->getSegment(4) ? lang('Section.text_edit') : lang('Section.text_add');
		$data['button_save'] = lang('Section.button_save');
		$data['button_cancel'] = lang('Section.button_cancel');
		$data['cancel'] = admin_url('section');
		
		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->uri->getSegment(4) && ($this->request->getMethod('REQUEST_METHOD') != 'POST')) {
			$section_info = $this->sectionModel->find($this->uri->getSegment(4));
		}

		foreach($this->sectionModel->getFieldNames($this->sectionModel->table) as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($section_info->{$field}) && $section_info->{$field}) {
				$data[$field] = $section_info->{$field};
			} else {
				$data[$field] = '';
			}
		}
		
	
		echo $this->template->view('Admin\Section\Views\sectionForm',$data);
	}

	protected function validateForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $rules = $this->sectionModel->validationRules;

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