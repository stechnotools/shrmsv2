<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class City extends Admin_Controller {
	private $error = array();
	
	function __construct(){
      parent::__construct();
		$this->load->model('city_model');		
	}
	public function index(){
      $this->lang->load('city');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		$this->getListForm();  
	}
	protected function getListForm() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => "City",
			'href' => admin_url('localisation/city')
		);
		/*form*/
		$this->template->add_package(array('datatable','select2'),true);
      
		$data['heading_title'] 	= $this->lang->line('heading_title');
		
		$data['text_form'] = $this->uri->segment(5) ? $this->lang->line('text_edit') : $this->lang->line('text_add');
		$data['button_save'] = $this->lang->line('button_save');
		$data['button_cancel'] = $this->lang->line('button_cancel');
		
		if (!$this->uri->segment(4)) {
			$data['action'] = admin_url("localisation/city/add");
		} else {
			$data['action'] = admin_url("localisation/city/edit/".$this->uri->segment(5));
		}
		
		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}
		
		$data['cancel'] = admin_url('localisation/city');

		if ($this->uri->segment(5) && ($this->input->server('REQUEST_METHOD') != 'POST')) {
			$city_info = $this->city_model->getCity($this->uri->segment(5));
		}
		
		if ($this->input->post('name')) {
			$data['name'] = $this->input->post('name');
		} elseif (!empty($city_info)) {
			$data['name'] = $city_info->name;
		} else {
			$data['name'] = '';
		}
		
		if ($this->input->post('state_id')) {
			$data['state_id'] = $this->input->post('state_id');
		} elseif (!empty($city_info)) {
			$data['state_id'] = $city_info->state_id;
		} else {
			$data['state_id'] = '';
		}
		

		
		$this->load->model('localisation/country_model');
		$data['countries'] = $this->country_model->getCountries();
		
		if ($this->input->post('country_id')) {
			$data['country_id'] = $this->input->post('country_id');
		} elseif (!empty($city_info)) {
			$data['country_id'] = $city_info->country_id;
		} else {
			$data['country_id'] = '';
		}
		
		
		
		/*list*/
		$data['delete'] = admin_url('localisation/city/delete');
		$data['datatable_url'] = admin_url('localisation/city/search');

		
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

		//printr($data);
		//exit;

		$this->template->view('city_list_form', $data);
	}
	
	public function search() {
		$requestData= $_REQUEST;
		
		$columns = array( 
			0 => '',
			1 => 'ct.name',
			2 => 's.name',
			3 => 'c.name'
		);
		
		$totalData = $this->city_model->getTotalCity();
		
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		 => $requestData['order'][0]['dir'],
			'sort' 			 => $columns[$requestData['order'][0]['column']] ,
			'start' 			 => $requestData['start'],
			'limit' 			 => $requestData['length']
		);
		$totalFiltered = $this->city_model->getTotalCity($filter_data);
			
		$filteredData = $this->city_model->getCities($filter_data);
		//printr($filteredData);
		
		$datatable=array();
		foreach($filteredData as $result) {
			
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('localisation/city/edit/'.$result->id).'"><i class="fa fa-pencil"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('localisation/city/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';
			$action .= '</div>';
			
			
			$datatable[]=array(
				'<input type="checkbox" name="selected[]" value="'.$result->id.'" />',
				$result->name,
				$result->state,
				$result->country,
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
		$this->lang->load('city');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			
			
			$city_id=$this->city_model->addCity($this->input->post());
			
			$this->session->set_flashdata('message', 'city Saved Successfully.');
			redirect("localisation/city");
		}
		$this->getListForm();
	}
	
	public function edit(){
		
		$this->lang->load('city');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			$city_id=$this->uri->segment(5);
			
			
			$userid=$this->city_model->editCity($city_id,$this->input->post());
			
			$this->session->set_flashdata('message', 'city Updated Successfully.');
			redirect("localisation/city");
		}
		$this->getListForm();
	}
	
	public function delete(){
		if ($this->input->post('selected')){
         $selected = $this->input->post('selected');
      }else{
         $selected = (array) $this->uri->segment(5);
       }
		$this->city_model->deleteCity($selected);
		$this->session->set_flashdata('message', 'city deleted Successfully.');
		redirect("localisation/city");
	}
	
	

	protected function validateForm() {
		$city_id=$this->uri->segment(5);
		
		$rules=array(
			'city_name' => array(
				'field' => 'name', 
				'label' => 'City Name', 
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
	

	public function updatecity(){
		
	}
	
	
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */