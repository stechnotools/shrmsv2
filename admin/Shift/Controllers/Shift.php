<?php
namespace Admin\Shift\Controllers;

use Admin\Branch\Models\BranchModel;
use Admin\Shift\Models\ShiftModel;
use App\Controllers\AdminController;

class Shift extends AdminController {
	private $error = array();
	private $shiftModel;
	public function __construct(){
        $this->shiftModel=new ShiftModel();
	}
	
	public function index(){
		$this->template->set_meta_title(lang('Shift.heading_title'));
        return $this->getList();
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->shiftModel->getTotal();
		
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->shiftModel->getTotal($filter_data);
			
		$filteredData = $this->shiftModel->getAll($filter_data);
		//printr($filteredData);
		
		$datatable=array();
		foreach($filteredData as $result) {
			
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('shift/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('shift/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
			$action .= '</div>';

			$datatable[]=array(
				'<div class="checkbox checkbox-primary checkbox-single">
					<input type="checkbox" name="selected[]" value="'.$result->id.'" />
					<label></label>
				</div>',
				$result->branch_code,
				$result->code,
				$result->shift_start_time,
				$result->shift_end_time,
				$result->lunch_starttime,
				$result->lunch_endtime,
				$result->lunch_duration,
				$result->ot_start_after,
				$result->ot_deduction,
				$result->lunch_deduction,
				$result->flexible_lunch_deduction,
				$result->shift_hours,
				$result->ot_deduct_after,
				$result->shift_position,
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
		$this->template->set_meta_title(lang('Shift.heading_title'));
		
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			
			$this->shiftModel->insert($this->request->getPost());
			
			$this->session->setFlashdata('message', 'Shift Saved Successfully.');
			return redirect()->to(admin_url('shift'));
		
		}
		$this->getForm();
	}
	
	public function edit(){
		
		$this->template->set_meta_title(lang('Shift.heading_title'));
		
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			$shift_id=$this->uri->getSegment(4);
			$this->shiftModel->update($shift_id,$this->request->getPost());
			
			$this->session->setFlashdata('message', 'Shift Updated Successfully.');
			return redirect()->to(admin_url('shift'));
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->request->getPost('selected')){
         $selected = $this->request->getPost('selected');
      }else{
         $selected = (array) $this->uri->getSegment(4);
       }
		$this->shiftModel->whereIn('id',$selected)->delete();
		$this->session->setFlashdata('message', 'Shift deleted Successfully.');
		return redirect()->to(admin_url('shift'));
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Shift.heading_title'),
			'href' => admin_url('shift')
		);
		
		$this->template->add_package(array('datatable'),true);
      

		$data['add'] = admin_url('shift/add');
		$data['delete'] = admin_url('shift/delete');
		$data['datatable_url'] = admin_url('shift/search');

		$data['heading_title'] = lang('Shift.heading_title');
		
		$data['text_list'] = lang('Shift.text_list');
		$data['text_no_results'] = lang('Shift.text_no_results');
		$data['text_confirm'] = lang('Shift.text_confirm');

		$data['column_shiftname'] = lang('Shift.column_shiftname');
		$data['column_status'] = lang('Shift.column_status');
		$data['column_date_added'] = lang('Shift.column_date_added');
		$data['column_action'] = lang('Shift.column_action');

		$data['button_add'] = lang('Shift.button_add');
		$data['button_edit'] = lang('Shift.button_edit');
		$data['button_delete'] = lang('Shift.button_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Shift\Views\shift', $data);
	}
	
	protected function getForm(){
		
		$this->template->add_package(array('timepicker','select2'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Shift.heading_title'),
			'href' => admin_url('shift')
		);

		$data['breadcrumbs'][] = array(
			'text' => lang('Shift.text_add'),
			'href' => admin_url('shift/add')
		);

		$_SESSION['isLoggedIn'] = true;
		
		$data['heading_title'] 	= lang('Shift.heading_title');
		
		$data['text_form'] = $this->uri->getSegment(4) ? lang('Shift.text_edit') : lang('Shift.text_add');
		$data['button_save'] = lang('Shift.button_save');
		$data['button_cancel'] = lang('Shift.button_cancel');
		$data['cancel'] = admin_url('shift');
		
		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->uri->getSegment(4) && ($this->request->getMethod('REQUEST_METHOD') != 'POST')) {
			$shift_info = $this->shiftModel->find($this->uri->getSegment(4));
		}

		foreach($this->shiftModel->getFieldNames($this->shiftModel->table) as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($shift_info->{$field}) && $shift_info->{$field}) {
				$data[$field] = $shift_info->{$field};
			} else {
				$data[$field] = '';
			}
		}

		$data['branches']=(new BranchModel())->getAll();
		$data['spositions']=array("N"=>"Night","D"=>"Day","H"=>"Half Day");

		echo $this->template->view('Admin\Shift\Views\shiftForm',$data);
	}

	protected function validateForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $rules = $this->shiftModel->validationRules;

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