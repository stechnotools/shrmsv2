<?php
namespace Admin\Machine\Controllers;

use Admin\Branch\Models\BranchModel;
use Admin\Machine\Models\MachineModel;
use App\Controllers\AdminController;

class Machine extends AdminController {
	private $error = array();
	
	public function __construct(){
        $this->machineModel=new MachineModel();
	}
	
	public function index(){
		$this->template->set_meta_title(lang('Machine.heading_title'));
        return $this->getList();
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->machineModel->getTotal();
		
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->machineModel->getTotal($filter_data);
			
		$filteredData = $this->machineModel->getAll($filter_data);
		//printr($filteredData);
		
		$datatable=array();
		foreach($filteredData as $result) {
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('machine/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('machine/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
			$action .= '</div>';

			$datatable[]=array(
				'<div class="checkbox checkbox-primary checkbox-single">
					<input type="checkbox" name="selected[]" value="'.$result->id.'" />
					<label></label>
				</div>',
				$result->code,
				$result->name,
				$result->branch_name,
				$result->location,
				$result->used_for,
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
		$this->template->set_meta_title(lang('Machine.heading_title'));
		
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			
			$this->machineModel->insert($this->request->getPost());
			
			$this->session->setFlashdata('message', 'Machine Saved Successfully.');
			return redirect()->to(admin_url('machine'));
		
		}
		$this->getForm();
	}
	
	public function edit(){
		
		$this->template->set_meta_title(lang('Machine.heading_title'));
		
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			$machine_id=$this->uri->getSegment(4);
			$this->machineModel->update($machine_id,$this->request->getPost());
			
			$this->session->setFlashdata('message', 'Machine Updated Successfully.');
			return redirect()->to(admin_url('machine'));
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->request->getPost('selected')){
         $selected = $this->request->getPost('selected');
      }else{
         $selected = (array) $this->uri->getSegment(4);
       }
		$this->machineModel->deleteMachine($selected);
		$this->session->setFlashdata('message', 'Machine deleted Successfully.');
		redirect(ADMIN_PATH.'/machine');
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Machine.heading_title'),
			'href' => admin_url('machine')
		);
		
		$this->template->add_package(array('datatable'),true);
      

		$data['add'] = admin_url('machine/add');
		$data['delete'] = admin_url('machine/delete');
		$data['datatable_url'] = admin_url('machine/search');

		$data['heading_title'] = lang('Machine.heading_title');
		
		$data['text_list'] = lang('Machine.text_list');
		$data['text_no_results'] = lang('Machine.text_no_results');
		$data['text_confirm'] = lang('Machine.text_confirm');

		$data['column_machinename'] = lang('Machine.column_machinename');
		$data['column_status'] = lang('Machine.column_status');
		$data['column_date_added'] = lang('Machine.column_date_added');
		$data['column_action'] = lang('Machine.column_action');

		$data['button_add'] = lang('Machine.button_add');
		$data['button_edit'] = lang('Machine.button_edit');
		$data['button_delete'] = lang('Machine.button_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Machine\Views\machine', $data);
	}
	
	protected function getForm(){
		
		$this->template->add_package(array('ckeditor','ckfinder','colorbox','select2'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Machine.heading_title'),
			'href' => admin_url('machine')
		);

		$data['breadcrumbs'][] = array(
			'text' => lang('Machine.text_add'),
			'href' => admin_url('machine/add')
		);

		$_SESSION['isLoggedIn'] = true;
		
		$data['heading_title'] 	= lang('Machine.heading_title');
		
		$data['text_form'] = $this->uri->getSegment(4) ? lang('Machine.text_edit') : lang('Machine.text_add');
		$data['button_save'] = lang('Machine.button_save');
		$data['button_cancel'] = lang('Machine.button_cancel');
		$data['cancel'] = admin_url('machine');
		
		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->uri->getSegment(4) && ($this->request->getMethod('REQUEST_METHOD') != 'POST')) {
			$machine_info = $this->machineModel->find($this->uri->getSegment(4));
		}

		foreach($this->machineModel->getFieldNames($this->machineModel->table) as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($machine_info->{$field}) && $machine_info->{$field}) {
				$data[$field] = $machine_info->{$field};
			} else {
				$data[$field] = '';
			}
		}
		
		$data['branches']=(new BranchModel())->getAll();
	
		echo $this->template->view('Admin\Machine\Views\machineForm',$data);
	}

	protected function validateForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $rules = $this->machineModel->validationRules;

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