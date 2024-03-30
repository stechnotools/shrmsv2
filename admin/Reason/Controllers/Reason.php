<?php
namespace Admin\Reason\Controllers;

use Admin\Leave\Models\LeaveModel;
use Admin\Reason\Models\ReasonModel;
use App\Controllers\AdminController;

class Reason extends AdminController {
	private $error = array();
	private $reasonModel;
	public function __construct(){
        $this->reasonModel=new ReasonModel();
	}

	public function index(){
		$this->template->set_meta_title(lang('Reason.heading_title'));
        return $this->getList();
	}

	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->reasonModel->getTotal();

		$totalFiltered = $totalData;

		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->reasonModel->getTotal($filter_data);

		$filteredData = $this->reasonModel->getAll($filter_data);
		//printr($filteredData);

		$datatable=array();
		foreach($filteredData as $result) {

			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('reason/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('reason/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
			$action .= '</div>';

			$datatable[]=array(
				'<div class="checkbox checkbox-primary checkbox-single">
					<input type="checkbox" name="selected[]" value="'.$result->id.'" />
					<label></label>
				</div>',
				$result->code,
				$result->name,
				$result->leave_id,
				$result->leave_value,
				$result->leave_reason,
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
		$this->template->set_meta_title(lang('Reason.heading_title'));

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){

			$this->reasonModel->insert($this->request->getPost());

			$this->session->setFlashdata('message', 'Reason Saved Successfully.');
			if($this->request->isAJAX()){
                echo "1";
                exit;
            }else{
                return redirect()->to(admin_url('reason'));
            }

		}
		$this->getForm();
	}

	public function edit(){

		$this->template->set_meta_title(lang('Reason.heading_title'));

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){
			$reason_id=$this->uri->getSegment(4);
			$this->reasonModel->update($reason_id,$this->request->getPost());

			$this->session->setFlashdata('message', 'Reason Updated Successfully.');
			if($this->request->isAJAX()){
                echo "1";
                exit;
            }else{
                return redirect()->to(admin_url('reason'));
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
		$this->reasonModel->whereIn('id', $selected)->delete();
		$this->session->setFlashdata('message', 'Reason deleted Successfully.');
		return redirect()->to(admin_url('reason'));
	}

	protected function getList() {

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Reason.heading_title'),
			'href' => admin_url('reason')
		);

		$this->template->add_package(array('datatable'),true);


		$data['add'] = admin_url('reason/add');
		$data['delete'] = admin_url('reason/delete');
		$data['datatable_url'] = admin_url('reason/search');

		$data['heading_title'] = lang('Reason.heading_title');

		$data['text_list'] = lang('Reason.text_list');
		$data['text_no_results'] = lang('Reason.text_no_results');
		$data['text_confirm'] = lang('Reason.text_confirm');

		$data['column_reasonname'] = lang('Reason.column_reasonname');
		$data['column_status'] = lang('Reason.column_status');
		$data['column_date_added'] = lang('Reason.column_date_added');
		$data['column_action'] = lang('Reason.column_action');

		$data['button_add'] = lang('Reason.text_add');
		$data['button_edit'] = lang('Reason.text_edit');
		$data['button_delete'] = lang('Reason.text_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Reason\Views\reason', $data);
	}

	protected function getForm(){

		$this->template->add_package(array('select2'),true);

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Reason.heading_title'),
			'href' => admin_url('reason')
		);

		$data['breadcrumbs'][] = array(
			'text' => lang('Reason.text_add'),
			'href' => admin_url('reason/add')
		);

		$_SESSION['isLoggedIn'] = true;

		$data['heading_title'] 	= lang('Reason.heading_title');

		$data['text_form'] = $this->uri->getSegment(4) ? lang('Reason.text_edit') : lang('Reason.text_add');
		$data['button_save'] = lang('Reason.button_save');
		$data['button_cancel'] = lang('Reason.button_cancel');
		$data['cancel'] = admin_url('reason');

		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->uri->getSegment(4) && ($this->request->getMethod('REQUEST_METHOD') != 'POST')) {
			$reason_info = $this->reasonModel->find($this->uri->getSegment(4));
		}

		foreach($this->reasonModel->getFieldNames($this->reasonModel->table) as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($reason_info->{$field}) && $reason_info->{$field}) {
				$data[$field] = $reason_info->{$field};
			} else {
				$data[$field] = '';
			}
		}
		$data['leaves']=(new LeaveModel())->getAll();
		$data['reasons']=[
			'late_arrival'=>'Late Arrival',
			'early_departure'=>'Early Departure',
			'excess_lunch'=>'Excess Lunch',
			'hours_worked'=>'Hours Worked',
		];
		$data['leave_values']=[
			'0.25'=>'00.25',
			'0.50' => '00.50',
			'0.75'=>'00.75',
			'1'=>'01.00'
		];
		echo $this->template->view('Admin\Reason\Views\reasonForm',$data);
	}

	protected function validateForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $rules = $this->reasonModel->validationRules;

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
/* Reason: ./application/widgets/hmvc/controllers/hmvc.php */