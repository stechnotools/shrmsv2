<?php
namespace Admin\Workorder\Controllers;
use Admin\Workorder\Models\WorkorderModel;
use Admin\Hod\Models\HodModel;
use App\Controllers\AdminController;

class Workorder extends AdminController {
	private $error = array();
	private $workorderModel;
	public function __construct(){
        $this->workorderModel=new WorkorderModel();
	}
	
	public function index(){
		$this->template->set_meta_title(lang('Workorder.heading_title'));
        return $this->getList();
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->workorderModel->getTotal();
		
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->workorderModel->getTotal($filter_data);
			
		$filteredData = $this->workorderModel->getAll($filter_data);
		//printr($filteredData);
		
		$datatable=array();
		foreach($filteredData as $result) {
			
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('workorder/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('workorder/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
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
		$this->template->set_meta_title(lang('Workorder.heading_title'));
		
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			
			$this->workorderModel->insert($this->request->getPost());
			
			$this->session->setFlashdata('message', 'Workorder Saved Successfully.');
			if($this->request->isAJAX()){
                echo "1";
                exit;
            }else{
                return redirect()->to(admin_url('workorder'));
            }
		
		}
		$this->getForm();
	}
	
	public function edit(){
		
		$this->template->set_meta_title(lang('Workorder.heading_title'));
		
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			$workorder_id=$this->uri->getSegment(4);
			$this->workorderModel->update($workorder_id,$this->request->getPost());
			
			$this->session->setFlashdata('message', 'Workorder Updated Successfully.');
			if($this->request->isAJAX()){
                echo "1";
                exit;
            }else{
                return redirect()->to(admin_url('workorder'));
            }
			
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->request->getPost('selected')){
         $selected = $this->request->getPost('selected');
      }else{
         $selected = (array) $this->uri->getSegment(4);
       }
		$this->workorderModel->whereIn('id', $selected)->delete();
		$this->session->setFlashdata('message', 'Workorder deleted Successfully.');
		return redirect()->to(admin_url('workorder'));
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Workorder.heading_title'),
			'href' => admin_url('workorder')
		);
		
		$this->template->add_package(array('datatable'),true);
      

		$data['add'] = admin_url('workorder/add');
		$data['delete'] = admin_url('workorder/delete');
		$data['datatable_url'] = admin_url('workorder/search');

		$data['heading_title'] = lang('Workorder.heading_title');
		
		$data['text_list'] = lang('Workorder.text_list');
		$data['text_no_results'] = lang('Workorder.text_no_results');
		$data['text_confirm'] = lang('Workorder.text_confirm');

		$data['column_workordername'] = lang('Workorder.column_workordername');
		$data['column_status'] = lang('Workorder.column_status');
		$data['column_date_added'] = lang('Workorder.column_date_added');
		$data['column_action'] = lang('Workorder.column_action');

		$data['button_add'] = lang('Workorder.text_add');
		$data['button_edit'] = lang('Workorder.text_edit');
		$data['button_delete'] = lang('Workorder.text_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Workorder\Views\workorder', $data);
	}
	
	protected function getForm(){
		
		$this->template->add_package(array('select2'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Workorder.heading_title'),
			'href' => admin_url('workorder')
		);

		$data['breadcrumbs'][] = array(
			'text' => lang('Workorder.text_add'),
			'href' => admin_url('workorder/add')
		);

		$_SESSION['isLoggedIn'] = true;
		
		$data['heading_title'] 	= lang('Workorder.heading_title');
		
		$data['text_form'] = $this->uri->getSegment(4) ? lang('Workorder.text_edit') : lang('Workorder.text_add');
		$data['button_save'] = lang('Workorder.button_save');
		$data['button_cancel'] = lang('Workorder.button_cancel');
		$data['cancel'] = admin_url('workorder');
		
		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->uri->getSegment(4) && ($this->request->getMethod('REQUEST_METHOD') != 'POST')) {
			$workorder_info = $this->workorderModel->find($this->uri->getSegment(4));
		}

		foreach($this->workorderModel->getFieldNames($this->workorderModel->table) as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($workorder_info->{$field}) && $workorder_info->{$field}) {
				$data[$field] = $workorder_info->{$field};
			} else {
				$data[$field] = '';
			}
		}
		
		$data['hods']=(new HodModel())->getAll();
		
	
		echo $this->template->view('Admin\Workorder\Views\workorderForm',$data);
	}

	protected function validateForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $rules = $this->workorderModel->validationRules;

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
/* Workorder: ./application/widgets/hmvc/controllers/hmvc.php */