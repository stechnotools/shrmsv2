<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Canteen extends Admin_Controller {
	private $error = array();
	
	public function __construct(){
		parent::__construct();
		$this->load->model('canteen_model');
		$this->load->model('employee/employee_model');	
	}
	
	public function index(){
		$this->lang->load('canteen');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		$this->getList();  
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->canteen_model->getTotalCanteens();
		
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_user_id'=> $requestData['user_id']?$requestData['user_id']:$this->user->getEmployeeId(),
			'filter_search' => $requestData['search']['value'],
			'filter_from' => $requestData['from'],
			'filter_to' => $requestData['to'],
			'filter_depratment' => $requestData['department_id'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->canteen_model->getTotalCanteens($filter_data);
			
		$filteredData = $this->canteen_model->getCanteens($filter_data);
		//printr($filteredData);
		//$types=array(1=>'Breakfast',2=>'Lunch',3=>'Snacks',4=>'Dinner' );
		$datatable=array();
		foreach($filteredData as $result) {
			
			
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('canteen/edit/'.$result->id).'"><i class="fa fa-pencil"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('canteen/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';
			$action .= '</div>';

			$datatable[]=array(
				'<input type="checkbox" name="selected[]" value="'.$result->id.'" />',
				date("d-m-Y",strtotime($result->from_date)),
				$result->paycode,
				$result->employee_name,
				$result->department_name,
				$result->breakfast,
				$result->lunch,
				$result->snack,
				$result->dinner,
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
		$this->lang->load('canteen');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			//printr($this->input->post());
			if($this->input->post('breakfast')==0 && $this->input->post('lunch')==0 && $this->input->post('snack')==0 && $this->input->post('dinner')==0){
				$this->error['warning']= 'Please atleast one food timing booking';
				
			}else{
				//printr($this->input->post());
				//exit;
				$employee=$this->employee_model->getEmployee($this->input->post('user_id'));
				$_POST['branch_id']=$employee->branch_id;
				$_POST['department_id']=$employee->department_id;
				$canteenid=$this->canteen_model->addCanteen($this->input->post());
				
				$this->session->set_flashdata('message', 'Canteen Saved Successfully.');
				redirect(ADMIN_PATH.'/canteen');
			}
		}
		$this->getForm();
	}
	
	public function edit(){
		
		$this->lang->load('canteen');
		$this->template->set_meta_title($this->lang->line('heading_title'));
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			if($this->input->post('breakfast')==0 && $this->input->post('lunch')==0 && $this->input->post('snack')==0 && $this->input->post('dinner')==0){
				$this->error['warning']= 'Please atleast one food timing booking';
				
			}else{
				$canteen_id=$this->uri->segment(4);
				$this->canteen_model->editCanteen($canteen_id,$this->input->post());
				
				$this->session->set_flashdata('message', 'Canteen Updated Successfully.');
				redirect(ADMIN_PATH.'/canteen');
			}
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->input->post('selected')){
         $selected = $this->input->post('selected');
      }else{
         $selected = (array) $this->uri->segment(4);
       }
		$this->canteen_model->deleteCanteen($selected);
		$this->session->set_flashdata('message', 'Canteen deleted Successfully.');
		redirect(ADMIN_PATH.'/canteen');
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('heading_title'),
			'href' => admin_url('canteen')
		);
		
		$this->template->add_package(array('datatable','select2','datepicker'),true);
      

		$data['add'] = admin_url('canteen/add');
		$data['delete'] = admin_url('canteen/delete');
		$data['datatable_url'] = admin_url('canteen/search');

		$data['heading_title'] = $this->lang->line('heading_title');
		
		$data['text_list'] = $this->lang->line('text_list');
		$data['text_no_results'] = $this->lang->line('text_no_results');
		$data['text_confirm'] = $this->lang->line('text_confirm');

		$data['column_canteenname'] = $this->lang->line('column_canteenname');
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
		
		$this->load->model('employee/employee_model');
		$data['employees']=$this->employee_model->getEmployees();
		
		$this->load->model('department/department_model');
		$data['departments']=$this->department_model->getDepartments();
		
		$user=$this->employee_model->getEmployee($this->user->getID());
		
		$data['department_id']=$user->department_id;
		$data['user_id']=$this->user->getID();
		
		$this->template->view('canteen', $data);
	}
	
	protected function getForm(){
		
		$data = $this->lang->load('canteen');
		$this->template->add_package(array('datepicker','select2'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('heading_title'),
			'href' => admin_url('canteen')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('text_add'),
			'href' => admin_url('canteen/add')
		);

		$_SESSION['isLoggedIn'] = true;
		
		$data['heading_title'] 	= $this->lang->line('heading_title');
		
		$data['text_form'] = $this->uri->segment(4) ? $this->lang->line('text_edit') : $this->lang->line('text_add');
		$data['button_save'] = $this->lang->line('button_save');
		$data['button_cancel'] = $this->lang->line('button_cancel');
		$data['cancel'] = admin_url('canteen');
		
		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}
		$data['edit']=$data['bdate']=$data['emp']=false;
		if ($this->uri->segment(4) && ($this->input->server('REQUEST_METHOD') != 'POST')) {
			$canteen_info = $this->canteen_model->getCanteen($this->uri->segment(4));
			$canteen_type_info = $this->canteen_model->getCanteenType($this->uri->segment(4));
			
		}
		
		if($this->uri->segment(4)){
			$data['edit']=true;
		}

		foreach($this->canteen_model->getTableColumns() as $field) {
			if($this->input->post($field)) {
				$data[$field] = $this->input->post($field);
			} else if(isset($canteen_info->{$field}) && $canteen_info->{$field}) {
				$data[$field] = $canteen_info->{$field};
			} else {
				$data[$field] = '';
			}
		}
		
		if($this->input->post('breakfast')) {
			$data['breakfast']=$this->input->post('breakfast');
		}else if(!empty($canteen_info->breakfast)) {
			$data['breakfast'] = $canteen_info->breakfast;
		}else{
			$data['breakfast']=0;
		}
		
		if($this->input->post('lunch')) {
			$data['lunch']=$this->input->post('lunch');
		}else if(!empty($canteen_info->lunch)) {
			$data['lunch'] = $canteen_info->lunch;
		}else{
			$data['lunch']=0;
		}
		
		if($this->input->post('snack')) {
			$data['snack']=$this->input->post('snack');
		}else if(!empty($canteen_info->snack)) {
			$data['snack'] = $canteen_info->snack;
		}else{
			$data['snack']=0;
		}
		
		if($this->input->post('dinner')) {
			$data['dinner']=$this->input->post('dinner');
		}else if(!empty($canteen_info->dinner)) {
			$data['dinner'] = $canteen_info->dinner;
		}else{
			$data['dinner']=0;
		}
		
		
		
		if($this->input->post('types')) {
			$data['types']=$this->input->post('types');
		}else if(!empty($canteen_info)) {
			if($canteen_info->breakfast){
				$data['types'][]='breakfast';
			}
			if($canteen_info->lunch){
				$data['types'][]='lunch';
			}
			if($canteen_info->snack){
				$data['types'][]='snack';
			}
			if($canteen_info->dinner){
				$data['types'][]='dinner';
			}
			
		}else {
			$data['types'] = array();
		}
		
		//printr($data['types']);
		
		
	
		$this->load->model('employee/employee_model');
		$employees=$this->employee_model->getEmployees(array('filter_user_id'=>$this->user->getEmployeeId()));
		$data['employees']=[];
		foreach($employees as $emp){
			$data['employees'][]=array(
				'id'=>$emp->id,
				'name'=>$emp->employee_name.'('.$emp->paycode.')'
			);
		}
		if($this->input->post('from_date')) {
			$data['from_date'] = date("d-m-Y",strtotime($this->input->post('from_date')));
		}else if(isset($canteen_info->from_date) && $canteen_info->from_date) {
			$data['from_date'] = date("d-m-Y",strtotime($canteen_info->from_date));
		} else {
			$data['from_date'] = date("d-m-Y");
		}
		
		
		
		if($data['from_date'] < date("d-m-Y")){
			$data['bdate']=true;
		}
		
		if($this->user->getGroupId()==4){
			$data['emp']=true;
		}
		
		$data['dbreakfast']=$data['dlunch']=$data['dsnack']=$data['ddinner']=false;
		if($data['from_date'] == date("d-m-Y") && $data['edit']){
			if(time() > strtotime($this->settings->canteen_breakfast)){
				$data['dbreakfast']=true;
				//echo "ok";
			}
			if(time() > strtotime($this->settings->canteen_lunch)){
				$data['dlunch']=true;
			}
			if(time() > strtotime($this->settings->canteen_snack)){
				$data['dsnack']=true;
			}
			if(time() > strtotime($this->settings->canteen_dinner)){
				$data['ddinner']=true;
			}
			
		}
		
		//printr($data['ddinner']);
		
		/*$data['typelist']=array(
			array(
				'name'=>'breakfast',
				'disable'=>$dbreakfast
			),
			array(
				'name'=>'lunch',
				'disable'=>$dlunch
			),
			array(
				'name'=>'snack',
				'disable'=>$dsnack
			),
			array(
				'name'=>'dinner',
				'disable'=>$ddinner
			),
			
		);*/
		
		//printr($data['typelist']);
		
		$this->template->view('canteenForm',$data);
	}
	
	public function setup(){
		$data = $this->lang->load('canteen');
		$this->template->add_package(array('timepicker','select2'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('heading_title'),
			'href' => admin_url('canteen')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->lang->line('text_add'),
			'href' => admin_url('canteen/setup')
		);

		$_SESSION['isLoggedIn'] = true;
		$data['button_save'] = $this->lang->line('button_save');
		$data['button_cancel'] = $this->lang->line('button_cancel');
		$data['cancel'] = admin_url('canteen');
		
		$data['heading_title'] 	= $this->lang->line('heading_title');
		
		if ($this->input->server('REQUEST_METHOD') === 'POST' ){
			$this->load->model('setting/setting_model');
			$this->setting_model->editSetting('canteen',$this->input->post());
			$this->session->set_flashdata('message', 'canteen timing Saved');
			redirect(current_url());
		}
				
		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}
		
		/*General Tab*/
		if ($this->input->post('canteen_breakfast')){
			$data['canteen_breakfast'] = $this->input->post('canteen_breakfast');
		} else {
			$data['canteen_breakfast'] = $this->settings->canteen_breakfast;
		}
		
		if ($this->input->post('canteen_lunch')){
			$data['canteen_lunch'] = $this->input->post('canteen_lunch');
		} else {
			$data['canteen_lunch'] = $this->settings->canteen_lunch;
		}
		
		if ($this->input->post('canteen_dinner')){
			$data['canteen_dinner'] = $this->input->post('canteen_dinner');
		} else {
			$data['canteen_dinner'] = $this->settings->canteen_dinner;
		}
		
		if ($this->input->post('canteen_snack')){
			$data['canteen_snack'] = $this->input->post('canteen_snack');
		} else {
			$data['canteen_snack'] = $this->settings->canteen_snack;
		}
		
		$this->template->view('canteenSetup',$data);
	}

	protected function validateForm() {
		$canteen_id=$this->uri->segment(4);
		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
      	$regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
      	$regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor 
		$rules=array(
			'user_id' => array(
				'field' => 'user_id', 
				'label' => 'Employee Name', 
				'rules' => 'trim|required|max_length[100]'
			),
			
			'breakfast' => array(
				'field' => 'breakfast', 
				'label' => 'Breakfast', 
				'rules' => "trim|required|is_natural|callback_foodtiming[breakfast]"
			),
			'lunch' => array(
				'field' => 'lunch', 
				'label' => 'Lunch', 
				'rules' => "trim|callback_foodtiming[lunch]"
			),
			'snack' => array(
				'field' => 'snack', 
				'label' => 'Snack', 
				'rules' => "trim|callback_foodtiming[snack]"
			),
			'dinner' => array(
				'field' => 'dinner', 
				'label' => 'Dinner', 
				'rules' => "trim|callback_foodtiming[dinner]"
			),
			
			'from_date' => array(
				'field' => 'from_date', 
				'label' => 'Order Date', 
				'rules' => "trim|callback_orderdate[$canteen_id]"
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
	
	public function foodtiming($field,$name){
		$canteen_id=$this->uri->segment(4);
		$order_date = $this->input->post('from_date');
		$err=[];
		$order_dates=explode(',',$order_date);
		foreach($order_dates as $date){
			if(date("Y-m-d",strtotime($date))==date("Y-m-d")){
				$t=$this->settings->{'canteen_'.$name};
				if (time() >= strtotime($t) && !$canteen_id) {
					$err[]=$name." ordered before ".$t." on date:".$date;	
				}	
			}			
		}
		
		if($err && $field){
		   $message=implode("<br>",$err);
		   $this->form_validation->set_message('foodtiming',$message);
		   return false;
	   }else{
		   return TRUE;
	   }
	}

	public function orderdate($field,$canteen_id) {
		
		$user_id = $this->input->post('user_id');
		$order_date = $this->input->post('from_date');
		$err=[];
		$order_dates=explode(',',$order_date);
		//printr($order_dates);
		foreach($order_dates as $date){
			
			$check = $this->canteen_model->checkOrderDate($user_id,$date) ;
			//printr($check);
			
			if($check && !$canteen_id){
				$err[]="Already ordered in date:".$date." ,please edit this order on that day";
			}		
	   }
	   //exit;
	   if($err){
		   //printr($err);
		   $message=implode("<br>",$err);
		   $this->form_validation->set_message('orderdate',$message);
		   return false;
	   }else{
		   return TRUE;
	   }

	   
	}
	
	
	
}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */