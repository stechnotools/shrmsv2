<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Branch extends Admin_Controller {
	private $error = array();
	
	public function __construct(){
      parent::__construct();
		$this->load->model('branch_model');		
	}
	
	public function index(){
		$this->lang->load('branch');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		$this->getList();  
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->branch_model->getTotalBranches();
		
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->branch_model->getTotalBranches($filter_data);
			
		$filteredData = $this->branch_model->getBranches($filter_data);
		//printr($filteredData);
		
		$datatable=array();
		foreach($filteredData as $result) {
			
			
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('branch/edit/'.$result->id).'"><i class="fa fa-pencil"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('branch/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';
			$action .= '</div>';

			$datatable[]=array(
				'<input type="checkbox" name="selected[]" value="'.$result->id.'" />',
				$result->code,
				$result->name,
				$result->address,
				$result->short,
				$result->email1,
				$result->email2,
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
		$this->lang->load('branch');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			
			$branchid=$this->branch_model->addBranch($this->input->post());
			
			$this->session->set_flashdata('message', 'Branch Saved Successfully.');
			redirect(ADMIN_PATH.'/branch');
		}
		$this->getForm();
	}
	
	public function edit(){
		
		$this->lang->load('branch');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			$branch_id=$this->uri->segment(4);
			$this->branch_model->editBranch($branch_id,$this->input->post());
			
			$this->session->set_flashdata('message', 'Branch Updated Successfully.');
			redirect(ADMIN_PATH.'/branch');
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->input->post('selected')){
         $selected = $this->input->post('selected');
      }else{
         $selected = (array) $this->uri->segment(4);
       }
		$this->branch_model->deleteBranch($selected);
		$this->session->set_flashdata('message', 'Branch deleted Successfully.');
		redirect(ADMIN_PATH.'/branch');
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('heading_title'),
			'href' => admin_url('branch')
		);
		
		$this->template->add_package(array('datatable'),true);
      

		$data['add'] = admin_url('branch/add');
		$data['delete'] = admin_url('branch/delete');
		$data['datatable_url'] = admin_url('branch/search');

		$data['heading_title'] = $this->lang->line('heading_title');
		
		$data['text_list'] = $this->lang->line('text_list');
		$data['text_no_results'] = $this->lang->line('text_no_results');
		$data['text_confirm'] = $this->lang->line('text_confirm');

		$data['column_branchname'] = $this->lang->line('column_branchname');
		$data['column_status'] = $this->lang->line('column_status');
		$data['column_date_added'] = $this->lang->line('column_date_added');
		$data['column_action'] = $this->lang->line('column_action');

		$data['button_add'] = $this->lang->line('button_add');
		$data['button_edit'] = $this->lang->line('button_edit');
		$data['button_delete'] = $this->lang->line('button_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->input->post('selected')) {
			$data['selected'] = (array)$this->input->post('selected');
		} else {
			$data['selected'] = array();
		}

		$this->template->view('branch', $data);
	}
	
	protected function getForm(){
		
		$data = $this->lang->load('branch');
		$this->template->add_package(array('ckeditor','ckfinder','colorbox','select2'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('heading_title'),
			'href' => admin_url('branch')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('text_add'),
			'href' => admin_url('branch/add')
		);

		$_SESSION['isLoggedIn'] = true;
		
		$data['heading_title'] 	= $this->lang->line('heading_title');
		
		$data['text_form'] = $this->uri->segment(4) ? $this->lang->line('text_edit') : $this->lang->line('text_add');
		$data['button_save'] = $this->lang->line('button_save');
		$data['button_cancel'] = $this->lang->line('button_cancel');
		$data['cancel'] = admin_url('branch');
		
		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->uri->segment(4) && ($this->input->server('REQUEST_METHOD') != 'POST')) {
			$branch_info = $this->branch_model->getBranch($this->uri->segment(4));
		}

		foreach($this->branch_model->getTableColumns() as $field) {
			if($this->input->post($field)) {
				$data[$field] = $this->input->post($field);
			} else if(isset($branch_info->{$field}) && $branch_info->{$field}) {
				$data[$field] = $branch_info->{$field};
			} else {
				$data[$field] = '';
			}
		}
		
		$this->template->view('branchForm',$data);
	}

	protected function validateForm() {
		$branch_id=$this->uri->segment(4);
		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
      	$regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
      	$regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor 
		$rules=array(
			'name' => array(
				'field' => 'name', 
				'label' => 'Branch Name', 
				'rules' => 'trim|required|max_length[100]'
			),
			
			'code' => array(
				'field' => 'code', 
				'label' => 'Branch Code', 
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
			$this->error['warning']=$this->lang->line('error_warning');
			return false;
    	}
		return !$this->error;
	}

	public function shift($branch_id){
		if (is_ajax()){
			$this->load->model('shift/shift_model');	
			$json = array();
			$json = array(
				'branch_id'  	=> $branch_id,
				'shift_id'     => $this->shift_model->getShiftByBranch($branch_id)		
			);
			echo json_encode($json);
		}else{
         	return show_404();
      	}
	}
	
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */