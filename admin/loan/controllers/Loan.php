<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');use PhpOffice\PhpSpreadsheet\Spreadsheet;use PhpOffice\PhpSpreadsheet\Writer\Xlsx;class Loan extends Admin_Controller {	private $error = array();	public function __construct(){		parent::__construct();		$this->load->model('loan_model');		$this->load->model('employee/employee_model');				//$this->load->model('loan_shift_model');		//$this->load->model('loan_time_model');			}	public function index(){      	$this->lang->load('loan');		$this->template->set_meta_title($this->lang->line('heading_title'));		$this->getList();  	}	protected function search() {		$requestData= $_REQUEST;		$totalData = $this->loan_model->getTotalLoans();		$totalFiltered = $totalData;		$filter_data = array(			'filter_search'  => $requestData['search']['value'],			'order'  		 => $requestData['order'][0]['dir'],			'sort' 			 => $requestData['order'][0]['column'],			'start' 		 => $requestData['start'],			'limit' 		 => $requestData['length']		);		$totalFiltered = $this->loan_model->getTotalLoans($filter_data);		$filteredData = $this->loan_model->getLoans($filter_data);		//printr($filteredData);		$datatable=array();		foreach($filteredData as $result) {						$action  = '<div class="btn-group btn-group-sm pull-right">';			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('loan/view/'.$result->id).'"><i class="fa fa-eye"></i></a>';			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('loan/edit/'.$result->id).'"><i class="fa fa-pencil"></i></a>';			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('loan/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';			$action .= '</div>';			$datatable[]=array(				'<input type="checkbox" name="selected[]" value="'.$result->id.'" />',				$result->employee_name,				$result->paycode,				$result->paid_month,				$result->loan_amount,				$result->installment_amount,				$result->no_installment,				$action			);		}		//printr($datatable);		$json_data = array(			"draw"            => isset($requestData['draw']) ? intval( $requestData['draw'] ):1,			"recordsTotal"    => intval( $totalData ),			"recordsFiltered" => intval( $totalFiltered ),			"data"            => $datatable		);		$this->output		->set_content_type('application/json')		->set_output(json_encode($json_data));  // send data as json format	}	public function add(){		$this->lang->load('loan');		$this->template->set_meta_title($this->lang->line('heading_title'));		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){				$userid=$this->loan_model->addLoan($this->input->post());			$this->session->set_flashdata('message', 'loan Saved Successfully.');			redirect(ADMIN_PATH.'/loan');		}		$this->getForm();	}	public function edit(){		$this->lang->load('loan');		$this->template->set_meta_title($this->lang->line('heading_title'));		if ($this->input->server('REQUEST_METHOD') === 'POST'){				$user_id=$this->uri->segment(4);			$this->loan_model->editLoan($user_id,$this->input->post());			$this->session->set_flashdata('message', 'loan Updated Successfully.');			redirect(ADMIN_PATH.'/loan');		}		$this->getForm();	}	public function delete(){		if ($this->input->post('selected')){         $selected = $this->input->post('selected');      }else{         $selected = (array) $this->uri->segment(4);       }		$this->loan_model->deleteLoan($selected);		$this->session->set_flashdata('message', 'loan deleted Successfully.');		redirect(ADMIN_PATH.'/loan');	}	protected function getList() {		$data['breadcrumbs'] = array();		$data['breadcrumbs'][] = array(			'text' => $this->lang->line('heading_title'),			'href' => admin_url('loan')		);		$this->template->add_package(array('datatable'),true);		$data['add'] = admin_url('loan/add');		$data['delete'] = admin_url('loan/delete');		$data['datatable_url'] = admin_url('loan/search');		$data['emp_sample']=base_url('storage/uploads/files/loan-sample.xlsx');		$data['heading_title'] = $this->lang->line('heading_title');		$data['text_list'] = $this->lang->line('text_list');		$data['text_no_results'] = $this->lang->line('text_no_results');		$data['text_confirm'] = $this->lang->line('text_confirm');		$data['button_add'] = $this->lang->line('button_add');		$data['button_edit'] = $this->lang->line('button_edit');		$data['button_delete'] = $this->lang->line('button_delete');		if(isset($this->error['warning'])){			$data['error'] 	= $this->error['warning'];		}		if ($this->input->post('selected')) {			$data['selected'] = (array)$this->input->post('selected');		} else {			$data['selected'] = array();		}		$this->template->view('loans', $data);	}	protected function getForm(){		$data = array();		$data = $this->lang->load('loan');		$this->template->add_package(array('ckeditor','ckfinder','colorbox','select2','datepicker','datatable'),true);		$data['breadcrumbs'] = array();		$data['breadcrumbs'][] = array(			'text' => $this->lang->line('heading_title'),			'href' => admin_url('loan')		);		//printr($_SESSION);		$_SESSION['isLoggedIn'] = true;		$data['heading_title'] 	= $this->lang->line('heading_title');		$data['text_form'] = $this->uri->segment(4) ? "loan Edit" : "loan Add";		$data['text_image'] =$this->lang->line('text_image');		$data['text_none'] = $this->lang->line('text_none');		$data['text_clear'] = $this->lang->line('text_clear');		$data['cancel'] = admin_url('pages');		$data['button_save'] = $this->lang->line('button_save');		$data['button_cancel'] = $this->lang->line('button_cancel');		if(isset($this->error['warning'])){			$data['error'] 	= $this->error['warning'];		}		if ($this->uri->segment(4) && ($this->input->server('REQUEST_METHOD') != 'POST')) {			$emp_loan = $this->loan_model->getEmployeeLoan($this->uri->segment(4));			$empoffice_info = $this->employee_model->getEmployeeOffice($emp_loan->user_id);			$data['loan_id']=$this->uri->segment(4);			$data['edit']=true;		}else{			$data['edit']=false;			$data['loan_id']=0;		}		if($this->input->get('id')){			$empoffice_info = $this->employee_model->getEmployeeOffice($this->input->get('id'));		}				//branch				//printr($data['shifts']);		foreach($this->loan_model->getTableColumnsByName("loan") as $field) {			if($this->input->post($field)) {				$data[$field] = $this->input->post($field);			} else if(isset($emp_loan->{$field}) && $emp_loan->{$field}) {				$data[$field] = $emp_loan->{$field};			} else {				$data[$field] = '';			}		}				if (!empty($empoffice_info)) {			$data['user_id'] = $empoffice_info->user_id;		} else {			$data['user_id'] = '';		}				if ($this->input->post('paycode')) {			$data['paycode'] = $this->input->post('paycode');		} elseif (!empty($empoffice_info)) {			$data['paycode'] = $empoffice_info->paycode;		} else {			$data['paycode'] = '';		}				if ($this->input->post('employee_name')) {			$data['employee_name'] = $this->input->post('employee_name');		} elseif (!empty($empoffice_info)) {			$data['employee_name'] = $empoffice_info->employee_name;		} else {			$data['employee_name'] = '';		}				if ($this->input->post('card_no')) {			$data['card_no'] = $this->input->post('card_no');		} elseif (!empty($empoffice_info)) {			$data['card_no'] = $empoffice_info->card_no;		} else {			$data['card_no'] = '';		}				if ($this->input->post('branch_name')) {			$data['branch_name'] = $this->input->post('branch_name');		} elseif (!empty($empoffice_info)) {			$data['branch_name'] = $empoffice_info->branch_name;		} else {			$data['branch_name'] = '';		}				if ($this->input->post('department_name')) {			$data['department_name'] = $this->input->post('department_name');		} elseif (!empty($empoffice_info)) {			$data['department_name'] = $empoffice_info->department_name;		} else {			$data['department_name'] = '';		}				if ($this->input->post('designation_name')) {			$data['designation_name'] = $this->input->post('designation_name');		} elseif (!empty($empoffice_info)) {			$data['designation_name'] = $empoffice_info->designation_name;		} else {			$data['designation_name'] = '';		}				if ($this->input->post('paid_month')) {			$data['paid_month'] = $this->input->post('paid_month');		} elseif (!empty($emp_loan)) {			$data['paid_month'] = date("m-Y",strtotime($emp_loan->paid_month));		} else {			$data['paid_month'] = date("m-Y");		}				if ($this->input->post('install_start')) {			$data['install_start'] = $this->input->post('install_start');		} elseif (!empty($emp_loan)) {			$data['install_start'] = date("m-Y",strtotime($emp_loan->install_start));		} else {			$data['install_start'] = date("m-Y");		}						$this->template->view('loanForm',$data);	}	protected function validateForm() {		$user_id=$this->uri->segment(4);		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path      	$regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query      	$regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor 		$rules=array(
			'paycode' => array(
				'field' => 'paycode', 
				'label' => 'Paycode', 
				'rules' => 'trim|required|max_length[100]'
			),					);		$this->form_validation->set_rules($rules);		if ($this->form_validation->run() == TRUE)		{			return true;    	}		else		{			$this->error['warning']=$this->lang->line('error_warning');			return false;    	}		return !$this->error;	}		public function view(){		$this->lang->load('loan');				$this->template->add_package(array('datatable'),true);				if(isset($this->error['warning'])){			$data['error'] 	= $this->error['warning'];		}		if ($this->input->post('selected')) {			$data['selected'] = (array)$this->input->post('selected');		} else {			$data['selected'] = array();		}		if ($this->input->server('REQUEST_METHOD') === 'POST'){				$user_id=$this->uri->segment(4);			$this->loan_model->editLoan($user_id,$this->input->post());			$this->session->set_flashdata('message', 'loan Updated Successfully.');			redirect(ADMIN_PATH.'/loan');		}				if ($this->uri->segment(4) && ($this->input->server('REQUEST_METHOD') != 'POST')) {			$data['adjustments'] = $this->loan_model->getEmployeeLoanAdjustment($this->uri->segment(4));					}		$this->template->view('adjustment', $data);	}		public function process(){		return modules::run("common/errors/index");		}}/* End of file hmvc.php *//* Location: ./application/widgets/hmvc/controllers/hmvc.php */