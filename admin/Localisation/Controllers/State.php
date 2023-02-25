<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class State extends Admin_Controller {
	private $error = array();
	
	function __construct(){
      parent::__construct();
		$this->load->model('state_model');		
	}
	public function index(){
      $this->lang->load('state');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		$this->getListForm();  
	}
	protected function getListForm() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => "state",
			'href' => admin_url('localisation/state')
		);
		/*form*/
		$this->template->add_package(array('datatable','select2'),true);
      
		$data['heading_title'] 	= $this->lang->line('heading_title');
		
		$data['text_form'] = $this->uri->segment(5) ? $this->lang->line('text_edit') : $this->lang->line('text_add');
		$data['button_save'] = $this->lang->line('button_save');
		$data['button_cancel'] = $this->lang->line('button_cancel');
		
		if (!$this->uri->segment(4)) {
			$data['action'] = admin_url("localisation/state/add");
		} else {
			$data['action'] = admin_url("localisation/state/edit/".$this->uri->segment(5));
		}
		
		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}
		
		$data['cancel'] = admin_url('localisation/state');

		if ($this->uri->segment(5) && ($this->input->server('REQUEST_METHOD') != 'POST')) {
			$state_info = $this->state_model->getState($this->uri->segment(5));
		}
		
		if ($this->input->post('name')) {
			$data['name'] = $this->input->post('name');
		} elseif (!empty($state_info)) {
			$data['name'] = $state_info->name;
		} else {
			$data['name'] = '';
		}
		
		$this->load->model('localisation/country_model');
		$data['countries'] = $this->country_model->getCountries();
		
		if ($this->input->post('country_id')) {
			$data['country_id'] = $this->input->post('country_id');
		} elseif (!empty($state_info)) {
			$data['country_id'] = $state_info->country_id;
		} else {
			$data['country_id'] = '';
		}
		
		
		if ($this->input->post('code')) {
			$data['code'] = $this->input->post('code');
		} elseif (!empty($state_info)) {
			$data['code'] = $state_info->code;
		} else {
			$data['code'] = '';
		}
		
		if ($this->input->post('status')) {
			$data['status'] = $this->input->post('status');
		} elseif (!empty($state_info)) {
			$data['status'] = $state_info->status;
		} else {
			$data['status'] = '';
		}
		
		/*list*/
		$data['delete'] = admin_url('localisation/state/delete');
		$data['datatable_url'] = admin_url('localisation/state/search');

		
		$data['text_list'] = $this->lang->line('text_list');
		$data['text_no_results'] = $this->lang->line('text_no_results');
		$data['text_confirm'] = $this->lang->line('text_confirm');

		$data['button_edit'] = $this->lang->line('button_edit');
		$data['button_delete'] = $this->lang->line('button_delete');


		if ($this->input->post('selected')) {
			$data['selected'] = (array)$this->input->post('selected');
		} else {
			$data['selected'] = array();
		}

		$this->template->view('state_list_form', $data);
	}
	
	public function search() {
		$requestData= $_REQUEST;
		
		$columns = array( 
			0 => '',
			1 => 's.name',
			2 => 's.code',
			3 => 'c.name',
			4 => 's.status'
		);
		
		$totalData = $this->state_model->getTotalState();
		
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		 => $requestData['order'][0]['dir'],
			'sort' 			 => $columns[$requestData['order'][0]['column']],
			'start' 			 => $requestData['start'],
			'limit' 			 => $requestData['length']
		);
		$totalFiltered = $this->state_model->getTotalState($filter_data);
			
		$filteredData = $this->state_model->getStates($filter_data);
		//printr($filteredData);
		
		$datatable=array();
		foreach($filteredData as $result) {

			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('localisation/state/edit/'.$result->id).'"><i class="fa fa-pencil"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('localisation/state/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';
			$action .= '</div>';
			
			
			$datatable[]=array(
				'<input type="checkbox" name="selected[]" value="'.$result->id.'" />',
				$result->name,
				$result->code,
				$result->country,
				$result->status ? 'Enable':'Disable',
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
		$this->lang->load('state');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			
			
			$state_id=$this->state_model->addState($this->input->post());
			
			$this->session->set_flashdata('message', 'state Saved Successfully.');
			redirect(ADMIN_PATH."/localisation/state");
		}
		$this->getListForm();
	}
	
	public function edit(){
		
		$this->lang->load('state');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			$state_id=$this->uri->segment(5);
			
			
			$userid=$this->state_model->editState($state_id,$this->input->post());
			
			$this->session->set_flashdata('message', 'state Updated Successfully.');
			redirect(ADMIN_PATH."/localisation/state");
		}
		$this->getListForm();
	}
	
	public function delete(){
		if ($this->input->post('selected')){
         $selected = $this->input->post('selected');
      }else{
         $selected = (array) $this->uri->segment(5);
       }
		$this->state_model->deleteState($selected);
		$this->session->set_flashdata('message', 'state deleted Successfully.');
		redirect(ADMIN_PATH."/localisation/state");
	}
	
	protected function validateForm() {
		$state_id=$this->uri->segment(3);
		
		$rules=array(
			'state_name' => array(
				'field' => 'name', 
				'label' => 'State Name', 
				'rules' => 'trim|required|max_length[100]'
			),
			'state_country_id' => array(
				'field' => 'country_id', 
				'label' => 'Country Name', 
				'rules' => 'trim|required|max_length[100]'
			),
		);
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run($this) == TRUE)
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

	public function city($state_id=0){
		if (is_ajax()){
			$this->load->model('localisation/city_model');	
			$json = array();
			$json = array(
				'state_id'  => $state_id,
				'city'      => $this->city_model->getCitiesByStateId($state_id)		
			);
			echo json_encode($json);
		}else{
         	return show_404();
      	}
	}
	
	
	
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */