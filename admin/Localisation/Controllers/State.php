<?php
namespace Admin\Localisation\Controllers;
use App\Controllers\AdminController;
use Admin\Localisation\Models\StateModel;
use Admin\Localisation\Models\ClusterModel;
use Admin\Localisation\Models\CountryModel;
use Admin\Localisation\Models\DistrictModel;
use Admin\Localisation\Models\GrampanchayatModel;

class State extends AdminController{
	private $error = array();
	private $stateModel;
	
	public function __construct(){
		$this->stateModel=new StateModel();
	}
	
	public function index(){
		$this->template->set_meta_title(lang('State.heading_title'));
		return $this->getList();  
	}
	
	public function add(){
		
		$this->template->set_meta_title(lang('State.heading_title'));
		
		if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){	
			
			//$code=$this->stateModel->getStateCode($this->request->getPost());
			//$_POST['code']=$code;
			
			$id=$this->stateModel->insert($this->request->getPost());
			
			$this->session->setFlashdata('message', 'State Saved Successfully.');
			
			return redirect()->to(base_url('admin/state'));
		}
		$this->getForm();
	}
	
	public function edit(){
		
		$this->template->set_meta_title(lang('State.heading_title'));
		
		if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){	
			$id=$this->uri->getSegment(4);
			
			$this->stateModel->update($id,$this->request->getPost());
			
			$this->session->setFlashdata('message', 'State Updated Successfully.');
		
			return redirect()->to(base_url('admin/state'));
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->request->getPost('selected')){
			$selected = $this->request->getPost('selected');
		}else{
			$selected = (array) $this->uri->getSegment(4);
		}
		$this->stateModel->delete($selected);
		
		$this->session->setFlashdata('message', 'State deleted Successfully.');
		return redirect()->to(base_url('admin/state'));
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('State.heading_title'),
			'href' => admin_url('state')
		);
		
		$this->template->add_package(array('datatable','select2'),true);

		$data['add'] = admin_url('state/add');
		$data['delete'] = admin_url('state/delete');
		$data['datatable_url'] = admin_url('state/search');

		$data['heading_title'] = lang('State.heading_title');
		
		$data['text_list'] = lang('State.text_list');
		$data['text_no_results'] = lang('State.text_no_results');
		$data['text_confirm'] = lang('State.text_confirm');
		
		$data['button_add'] = lang('State.button_add');
		$data['button_edit'] = lang('State.button_edit');
		$data['button_delete'] = lang('State.button_delete');
		
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}
		
		$coutryModel=new CountryModel();
		$data['countries'] = $coutryModel->getAll();



		return $this->template->view('Admin\Localisation\Views\state', $data);
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->stateModel->getTotals();
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'filter_district' => $requestData['district'],
			'filter_state' => $requestData['state'],
			'order'  		 => $requestData['order'][0]['dir'],
			'sort' 			 => $requestData['order'][0]['column'],
			'start' 			 => $requestData['start'],
			'limit' 			 => $requestData['length']
		);
		$totalFiltered = $this->stateModel->getTotals($filter_data);
			
		$filteredData = $this->stateModel->getAll($filter_data);
		
		$datatable=array();
		foreach($filteredData as $result) {

			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('state/edit/'.$result->id).'"><i class="fa fa-pencil"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('state/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';
			$action .= '</div>';
			
			$datatable[]=array(
				'<input type="checkbox" name="selected[]" value="'.$result->id.'" />',
				$result->code,
				$result->name,
				$result->district,
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
								->setJSON($json_data);
		
	}
	
	protected function getForm(){
		
		$this->template->add_package(array('colorbox'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('State.heading_title'),
			'href' => admin_url('state')
		);
		
		//printr($_SESSION);
		$_SESSION['isLoggedIn'] = true;
        
		$data['heading_title'] 	= lang('State.heading_title');
		$data['text_form'] = $this->uri->getSegment(3) ? "State Edit" : "State Add";
		$data['cancel'] = admin_url('state');
		
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->uri->getSegment(4) && ($this->request->getMethod(true) != 'POST')) {
			$state_info = $this->stateModel->find($this->uri->getSegment(4));
		}
		
		foreach($this->stateModel->getFieldNames('state') as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($state_info->{$field}) && $state_info->{$field}) {
				$data[$field] = html_entity_decode($state_info->{$field},ENT_QUOTES, 'UTF-8');
			} else {
				$data[$field] = '';
			}
		}
		
		$districtModel=new DistrictModel();
		$data['districts'] = $districtModel->getAll();
		
	    if($this->request->isAJAX()){
            echo $this->template->view('Admin\Localisation\Views\stateForm',$data,true);
        }else{
            echo $this->template->view('Admin\Localisation\Views\stateForm',$data);
        }
    }

	
	protected function validateForm() {
		//printr($_POST);
		$validation =  \Config\Services::validation();
		$id=$this->uri->getSegment(4);
		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
		$regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
		$regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor 

		$rules = $this->stateModel->validationRules;
		
		
		
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
	
	public function cluster($state=''){
		if (is_ajax()){
			$clusterModel=new ClusterModel();
			$json = array(
				'state'  	=> $state,
				'cluster'   => $clusterModel->getClustersByState($state)
			);
			echo json_encode($json);
		}else{
         	return show_404();
      	}
	}
	
	public function grampanchayat($state=''){
		if (is_ajax()){
			$grampanchayatModel=new GrampanchayatModel();
            if(!is_numeric($state)){
                $staterow=$this->stateModel->where('code', $state)->first();

                $state=$staterow->id;
            }
			$json = array(
				'state'  	=> $state,
				'grampanchayat'   => $grampanchayatModel->getGpsByState($state)
			);
			echo json_encode($json);
		}else{
         	return show_404();
      	}
	}
	
}

/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */