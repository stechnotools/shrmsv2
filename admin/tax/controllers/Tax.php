<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tax extends Admin_Controller {
	private $error = array();
	
	function __construct(){
      parent::__construct();
		$this->load->model('tax_model');
	}
	public function index(){
      $this->lang->load('tax');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		$this->getList();  
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('heading_title'),
			'href' => admin_url('pages')
		);
		
		$this->template->add_package(array('datatable'),true);
		
		$data['add'] = admin_url('tax/add');
		$data['delete'] = admin_url('tax/delete');
		$data['datatable_url'] = admin_url('tax/search');

		$data['heading_title'] = $this->lang->line('heading_title');
		
		$data['text_list'] = $this->lang->line('text_list');
		$data['text_no_results'] = $this->lang->line('text_no_results');
		$data['text_confirm'] = $this->lang->line('text_confirm');
		
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

		$this->template->view('taxs', $data);
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->tax_model->getTotalTaxs();
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		 => $requestData['order'][0]['dir'],
			'sort' 			 => $requestData['order'][0]['column'],
			'start' 			 => $requestData['start'],
			'limit' 			 => $requestData['length']
		);
		$totalFiltered = $this->tax_model->getTotalTaxs($filter_data);
			
		$filteredData = $this->tax_model->getTaxs($filter_data);
		
		$datatable=array();
		foreach($filteredData as $result) {

			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('tax/edit/'.$result->id).'"><i class="fa fa-pencil"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('tax/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';
			$action .= '</div>';
			
			$datatable[]=array(
				'<input type="checkbox" name="selected[]" value="'.$result->id.'" />',
				$result->name,
				$result->to_date,
				$result->from_date,
				$result->status?'Enabled':'Disabled',
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
		$this->lang->load('tax');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			
			$id=$this->tax_model->addTax($this->input->post());
			
			$this->session->set_flashdata('message', 'Tax Saved Successfully.');
			redirect(ADMIN_PATH.'/tax');
			
		}
		$this->getForm();
	}
	
	public function edit(){
		$this->lang->load('tax');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			$id=$this->uri->segment(4);
			
			$this->tax_model->editTax($id,$this->input->post());
			
			$this->session->set_flashdata('message', 'Tax Updated Successfully.');
			redirect(ADMIN_PATH.'/tax');
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->input->post('selected')){
         $selected = $this->input->post('selected');
      }else{
         $selected = (array) $this->uri->segment(4);
       }
		$this->tax_model->deleteTax($selected);
		$this->session->set_flashdata('message', 'Tax deleted Successfully.');
		redirect("tax");
	}
	
	protected function getForm(){
		
		$this->template->add_package(array('ckeditor','tablednd','colorbox','datepicker'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('heading_title'),
			'href' => admin_url('pages')
		);
		
		$_SESSION['isLoggedIn'] = true;
		
		
		$data['heading_title'] 	= $this->lang->line('heading_title');
		$data['text_form'] = $this->uri->segment(4) ? "Tax Edit" : "Tax Add";
		$data['cancel'] = admin_url('tax');
		
		$data['button_save'] = $this->lang->line('button_save');
		$data['button_cancel'] = $this->lang->line('button_cancel');
		
		
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->uri->segment(4) && ($this->input->server('REQUEST_METHOD') != 'POST')) {
			$tax_info = $this->tax_model->getTax($this->uri->segment(4));
		}
		
		foreach($this->tax_model->getTableColumns("tax_class") as $value) {
			if($this->input->post($value)) {
				$data[$value] = $this->input->post($value);
			} else if(isset($tax_info->{$value}) && $tax_info->{$value}) {
				$data[$value] = $tax_info->{$value};
			} else {
				$data[$value] = '';
			}
		}
		
		if ($this->input->post('to_date')) {
			$data['to_date'] = $this->input->post('to_date');
		} elseif (!empty($tax_info)) {
			$data['to_date'] = date("m-d-Y",strtotime($tax_info->to_date));
		} else {
			$data['to_date']='';
		}
		
		if ($this->input->post('from_date')) {
			$data['from_date'] = $this->input->post('from_date');
		} elseif (!empty($tax_info)) {
			$data['from_date'] = date("m-d-Y",strtotime($tax_info->from_date));
		} else {
			$data['from_date']='';
		}
		

		// rates
		if ($this->input->post('tax_rate')) {
			$tax_rates = $this->input->post('tax_rate');
		} elseif ($this->uri->segment(4)) {
			$tax_rates = $this->tax_model->getTaxRates($this->uri->segment(4));
		} else {
			$tax_rates = array();
		}

		$data['tax_rates'] = array();
		//printr($tax_rates);
		foreach ($tax_rates as $tax_rate) {
			

			$data['tax_rates'][] = array(
				'to_amount'  => $tax_rate->to_amount,
				'from_amount'=> $tax_rate->from_amount,
				'rate'		 => $tax_rate->rate,
				'additional'	 => $tax_rate->additional,
				'type'=> $tax_rate->type,
				
			);
		}
		
		
		$this->template->view('taxForm',$data);
	}
	
	protected function validateForm() {
		
		$rules=array(
			'name' => array(
				'field' => 'name', 
				'label' => 'Name', 
				'rules' => 'trim|required|max_length[100]'
			),
			'status' => array(
				'field' => 'status', 
				'label' => 'Status', 
				'rules' => 'trim|required'
			),
			
		);

		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run() == TRUE){
			return true;
    	}
		else{
			$this->error['warning']="Warning: Please check the form carefully for errors!";
			return false;
    	}
		return !$this->error;
	}
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */