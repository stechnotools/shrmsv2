<?php
namespace Admin\Location\Controllers;
use Admin\Location\Models\LocationModel;
use Admin\Hod\Models\HodModel;
use App\Controllers\AdminController;

class Location extends AdminController {
	private $error = array();
	private $locationModel;
	public function __construct(){
        $this->locationModel=new LocationModel();
	}
	
	public function index(){
		$this->template->set_meta_title(lang('Location.heading_title'));
        return $this->getList();
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->locationModel->getTotal();
		
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->locationModel->getTotal($filter_data);
			
		$filteredData = $this->locationModel->getAll($filter_data);
		//printr($filteredData);
		
		$datatable=array();
		foreach($filteredData as $result) {
			
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('location/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('location/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
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
		$this->template->set_meta_title(lang('Location.heading_title'));
		
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			
			$this->locationModel->insert($this->request->getPost());
			
			$this->session->setFlashdata('message', 'Location Saved Successfully.');
			if($this->request->isAJAX()){
                echo "1";
                exit;
            }else{
                return redirect()->to(admin_url('location'));
            }
		
		}
		$this->getForm();
	}
	
	public function edit(){
		
		$this->template->set_meta_title(lang('Location.heading_title'));
		
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			$location_id=$this->uri->getSegment(4);
			$this->locationModel->update($location_id,$this->request->getPost());
			
			$this->session->setFlashdata('message', 'Location Updated Successfully.');
			if($this->request->isAJAX()){
                echo "1";
                exit;
            }else{
                return redirect()->to(admin_url('location'));
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
		$this->locationModel->whereIn('id', $selected)->delete();
		$this->session->setFlashdata('message', 'Location deleted Successfully.');
		return redirect()->to(admin_url('location'));
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Location.heading_title'),
			'href' => admin_url('location')
		);
		
		$this->template->add_package(array('datatable'),true);
      

		$data['add'] = admin_url('location/add');
		$data['delete'] = admin_url('location/delete');
		$data['datatable_url'] = admin_url('location/search');

		$data['heading_title'] = lang('Location.heading_title');
		
		$data['text_list'] = lang('Location.text_list');
		$data['text_no_results'] = lang('Location.text_no_results');
		$data['text_confirm'] = lang('Location.text_confirm');

		$data['column_locationname'] = lang('Location.column_locationname');
		$data['column_status'] = lang('Location.column_status');
		$data['column_date_added'] = lang('Location.column_date_added');
		$data['column_action'] = lang('Location.column_action');

		$data['button_add'] = lang('Location.text_add');
		$data['button_edit'] = lang('Location.text_edit');
		$data['button_delete'] = lang('Location.text_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Location\Views\location', $data);
	}
	
	protected function getForm(){
		
		$this->template->add_package(array('select2'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Location.heading_title'),
			'href' => admin_url('location')
		);

		$data['breadcrumbs'][] = array(
			'text' => lang('Location.text_add'),
			'href' => admin_url('location/add')
		);

		$_SESSION['isLoggedIn'] = true;
		
		$data['heading_title'] 	= lang('Location.heading_title');
		
		$data['text_form'] = $this->uri->getSegment(4) ? lang('Location.text_edit') : lang('Location.text_add');
		$data['button_save'] = lang('Location.button_save');
		$data['button_cancel'] = lang('Location.button_cancel');
		$data['cancel'] = admin_url('location');
		
		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->uri->getSegment(4) && ($this->request->getMethod('REQUEST_METHOD') != 'POST')) {
			$location_info = $this->locationModel->find($this->uri->getSegment(4));
		}

		foreach($this->locationModel->getFieldNames($this->locationModel->table) as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($location_info->{$field}) && $location_info->{$field}) {
				$data[$field] = $location_info->{$field};
			} else {
				$data[$field] = '';
			}
		}
		
		$data['hods']=(new HodModel())->getAll();
		
	
		echo $this->template->view('Admin\Location\Views\locationForm',$data);
	}

	protected function validateForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $rules = $this->locationModel->validationRules;

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