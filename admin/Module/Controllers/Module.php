<?php
namespace Admin\Module\Controllers;
use App\Controllers\AdminController;
use Admin\Module\Models\ModuleModel;

class Module extends AdminController{
	private $error = array();
	private $moduleModel;

	public function __construct(){
		$this->moduleModel=new ModuleModel();
    }
	
	public function index(){
		$this->template->set_meta_title(lang('Module.heading_title'));
		return $this->getList();  
	}
	
	public function add(){
		
		$this->template->set_meta_title(lang('Module.heading_title'));
		if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){
            //echo command('module:create Customers -f admin');
            //exit;
            $id=$this->moduleModel->insert($this->request->getPost());
            if($id){
                $module_name=$this->request->getPost('name');
                $module_type=$this->request->getPost('module_type');
                $moduletype='';
                if($module_type){
                    $moduletype=implode('',$module_type);
                }
                echo command("module:create $module_name -f admin -c $moduletype");

            }
            $this->session->setFlashdata('message', 'Module Saved Successfully.');
			
			return redirect()->to(base_url('admin/module'));
		}
		$this->getForm();
	}
	
	public function edit(){
		
		
		$this->template->set_meta_title(lang('Module.heading_title'));
		
		if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){	
			$id=$this->uri->getSegment(4);
            
			$this->moduleModel->update($id,$this->request->getPost());
            $this->session->setFlashdata('message', 'Module Updated Successfully.');
		
			return redirect()->to(base_url('admin/module'));
		}
		$this->getForm();
	}
	
	public function delete(){
        helper('filesystem');
		if ($this->request->getPost('selected')){
			$selected = $this->request->getPost('selected');
		}else{
			$selected = (array) $this->uri->getSegment(4);
		}
        foreach($selected as $moduleid){
		    $module=$this->moduleModel->find($moduleid);
		  // printr($module);
		     $modulename=$module->name;
             $module_folder = APPPATH . '..'. DIRECTORY_SEPARATOR. "admin/".$modulename;

            $modulepath=realpath($module_folder);
            //delete_files($modulepath, TRUE);
            //array_map('unlink', glob("$modulepath/*.*"));
            //rmdir($modulepath);
            //echo $modulepath;
            //exit;
            //unlink($modulepath);

        }
		$this->moduleModel->delete($selected);

		$this->session->setFlashdata('message', 'Module deleted Successfully.');
		return redirect()->to(base_url('admin/module'));
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Module.heading_title'),
			'href' => admin_url('module')
		);
		
		$this->template->add_package(array('datatable'),true);

		$data['add'] = admin_url('module/add');
		$data['delete'] = admin_url('module/delete');
		$data['datatable_url'] = admin_url('module/search');

		$data['heading_title'] = lang('Module.heading_title');
		
		$data['text_list'] = lang('Module.text_list');
		$data['text_no_results'] = lang('Module.text_no_results');
		$data['text_confirm'] = lang('Module.text_confirm');
		
		$data['button_add'] = lang('Module.button_add');
		$data['button_edit'] = lang('Module.button_edit');
		$data['button_delete'] = lang('Module.button_delete');
		
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Module\Views\module', $data);
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->moduleModel->getTotal();
		$totalFiltered = $totalData;
		
		$filter_data = array(

			'filter_search' => $requestData['search']['value'],
			'order'  		 => $requestData['order'][0]['dir'],
			'sort' 			 => $requestData['order'][0]['column'],
			'start' 			 => $requestData['start'],
			'limit' 			 => $requestData['length']
		);
		$totalFiltered = $this->moduleModel->getTotal($filter_data);
			
		$filteredData = $this->moduleModel->getAll($filter_data);
		//printr($filteredData);
		$datatable=array();
		foreach($filteredData as $result) {

			$action  = '<div class="btn-group btn-group-sm pull-right">';
            $action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('module/edit/'.$result->id).'"><i class="fa fa-pencil"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('module/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';
			$action .= '</div>';
			
			$datatable[]=array(
				'<input type="checkbox" name="selected[]" value="'.$result->id.'" />',
				$result->name,
				$result->created_at,
                $result->mstatus?'Module Created':'Module Uploaded',
				$result->status?'Enable':'Disable',
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
		
		$this->template->add_package(array('select2'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Module.heading_title'),
			'href' => admin_url('module')
		);
		

		$data['heading_title'] 	= lang('Module.heading_title');
		$data['text_form'] = $this->uri->getSegment(4) ? "Module Edit" : "Module Add";
		$data['cancel'] = admin_url('module');
		
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->uri->getSegment(4) && ($this->request->getMethod(true) != 'POST')) {
			$module_info = $this->moduleModel->find($this->uri->getSegment(4));
		}
		
		foreach($this->moduleModel->getFieldNames('module') as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($module_info->{$field}) && $module_info->{$field}) {
				$data[$field] = html_entity_decode($module_info->{$field},ENT_QUOTES, 'UTF-8');
			} else {
				$data[$field] = '';
			}
		}

		$data['module_types']=[
		    "C"=>"Controller",
            "M"=>"Model",
            "V"=>"View",
            "F"=>"Config",
            "L"=>"Library",
            "O"=>"Other Directory"

        ];

		echo $this->template->view('Admin\Module\Views\moduleForm',$data);
	}
	
	protected function validateForm() {
		//printr($_POST);
		$validation =  \Config\Services::validation();
		$id=$this->uri->getSegment(4);
		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
		$regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
		$regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor 

		$rules = $this->moduleModel->validationRules;

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