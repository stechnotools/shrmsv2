<?php
namespace Admin\Hod\Controllers;

use Admin\Branch\Models\BranchModel;
use Admin\Employee\Models\EmployeeModel;
use Admin\Hod\Models\HodModel;
use App\Controllers\AdminController;

class Hod extends AdminController {
	private $error = array();
	private $hodModel;
	public function __construct(){
        $this->hodModel=new HodModel();
	}

	public function index(){
		$this->template->set_meta_title(lang('Hod.heading_title'));
        return $this->getList();
	}

	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->hodModel->getTotal();

		$totalFiltered = $totalData;

		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->hodModel->getTotal($filter_data);

		$filteredData = $this->hodModel->getAll($filter_data);
		//printr($filteredData);

		$datatable=array();
		foreach($filteredData as $result) {


			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('hod/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('hod/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
			$action .= '</div>';

			$datatable[]=array(
				'<div class="checkbox checkbox-primary checkbox-single">
					<input type="checkbox" name="selected[]" value="'.$result->id.'" />
					<label></label>
				</div>',
				$result->branch_name,
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
		$this->template->set_meta_title(lang('Hod.heading_title'));

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){

			$this->hodModel->insert($this->request->getPost());

			$this->session->setFlashdata('message', 'Hod Saved Successfully.');
			return redirect()->to(admin_url('hod'));

		}
		$this->getForm();
	}

	public function edit(){

		$this->template->set_meta_title(lang('Hod.heading_title'));

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){
			$hod_id=$this->uri->getSegment(4);
			$this->hodModel->update($hod_id,$this->request->getPost());

			$this->session->setFlashdata('message', 'Hod Updated Successfully.');
			return redirect()->to(admin_url('hod'));
		}
		$this->getForm();
	}

	public function delete(){
		if ($this->request->getPost('selected')){
         $selected = $this->request->getPost('selected');
      }else{
         $selected = (array) $this->uri->getSegment(4);
       }
		$this->hodModel->deleteHod($selected);
		$this->session->setFlashdata('message', 'Hod deleted Successfully.');
		redirect(ADMIN_PATH.'/hod');
	}

	protected function getList() {

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Hod.heading_title'),
			'href' => admin_url('hod')
		);

		$this->template->add_package(array('datatable'),true);


		$data['add'] = admin_url('hod/add');
		$data['delete'] = admin_url('hod/delete');
		$data['datatable_url'] = admin_url('hod/search');

		$data['heading_title'] = lang('Hod.heading_title');

		$data['text_list'] = lang('Hod.text_list');
		$data['text_no_results'] = lang('Hod.text_no_results');
		$data['text_confirm'] = lang('Hod.text_confirm');

		$data['column_hodname'] = lang('Hod.column_hodname');
		$data['column_status'] = lang('Hod.column_status');
		$data['column_date_added'] = lang('Hod.column_date_added');
		$data['column_action'] = lang('Hod.column_action');

		$data['button_add'] = lang('Hod.button_add');
		$data['button_edit'] = lang('Hod.button_edit');
		$data['button_delete'] = lang('Hod.button_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Hod\Views\hod', $data);
	}

	protected function getForm(){
		$this->template->add_package(array('select2'),true);
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Hod.heading_title'),
			'href' => admin_url('hod')
		);

		$data['breadcrumbs'][] = array(
			'text' => lang('Hod.text_add'),
			'href' => admin_url('hod/add')
		);

		$_SESSION['isLoggedIn'] = true;

		$data['heading_title'] 	= lang('Hod.heading_title');

		$data['text_form'] = $this->uri->getSegment(4) ? lang('Hod.text_edit') : lang('Hod.text_add');
		$data['button_save'] = lang('Hod.button_save');
		$data['button_cancel'] = lang('Hod.button_cancel');
		$data['cancel'] = admin_url('hod');

		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->uri->getSegment(4) && ($this->request->getMethod('REQUEST_METHOD') != 'POST')) {
			$hod_info = $this->hodModel->find($this->uri->getSegment(4));
		}

		foreach($this->hodModel->getFieldNames($this->hodModel->table) as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($hod_info->{$field}) && $hod_info->{$field}) {
				$data[$field] = $hod_info->{$field};
			} else {
				$data[$field] = '';
			}
		}

		$data['branches']=(new BranchModel())->getAll();
		$data['users']=(new EmployeeModel())->getAll();

		echo $this->template->view('Admin\Hod\Views\hodForm',$data);
	}

	protected function validateForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $rules = $this->hodModel->validationRules;

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