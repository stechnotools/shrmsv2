<?php
namespace Admin\Leave\Controllers;
use Admin\Leave\Models\LeaveModel;
use App\Controllers\AdminController;

class Leave extends AdminController {
	private $error = array();
	private $leaveModel;
	public function __construct(){
        $this->leaveModel=new LeaveModel();
	}
	
	public function index(){
		$this->template->set_meta_title(lang('Leave.heading_title'));
        return $this->getList();
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->leaveModel->getTotal();
		
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->leaveModel->getTotal($filter_data);
			
		$filteredData = $this->leaveModel->getAll($filter_data);
		//printr($filteredData);
		
		$datatable=array();
		foreach($filteredData as $result) {
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('leave/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('leave/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
			$action .= '</div>';

			$datatable[]=array(
				'<div class="checkbox checkbox-primary checkbox-single">
					<input type="checkbox" name="selected[]" value="'.$result->id.'" />
					<label></label>
				</div>',
				$result->leave_field,
				$result->leave_code,
				$result->leave_description,
				$result->week_exclude,
				$result->holiday_exclude,
				$result->leave_type,
				$result->insuff_leave_post,
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
		$this->template->set_meta_title(lang('Leave.heading_title'));
		
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			
			$this->leaveModel->insert($this->request->getPost());
			
			$this->session->setFlashdata('message', 'Leave Saved Successfully.');
			return redirect()->to(admin_url('leave'));
		
		}
		$this->getForm();
	}
	
	public function edit(){
		
		$this->template->set_meta_title(lang('Leave.heading_title'));
		
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			$leave_id=$this->uri->getSegment(4);
			$this->leaveModel->update($leave_id,$this->request->getPost());
			
			$this->session->setFlashdata('message', 'Leave Updated Successfully.');
			return redirect()->to(admin_url('leave'));
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->request->getPost('selected')){
         $selected = $this->request->getPost('selected');
      }else{
         $selected = (array) $this->uri->getSegment(4);
       }
		$this->leaveModel->deleteLeave($selected);
		$this->session->setFlashdata('message', 'Leave deleted Successfully.');
		redirect(ADMIN_PATH.'/leave');
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Leave.heading_title'),
			'href' => admin_url('leave')
		);
		
		$this->template->add_package(array('datatable'),true);
      

		$data['add'] = admin_url('leave/add');
		$data['delete'] = admin_url('leave/delete');
		$data['datatable_url'] = admin_url('leave/search');

		$data['heading_title'] = lang('Leave.heading_title');
		
		$data['text_list'] = lang('Leave.text_list');
		$data['text_no_results'] = lang('Leave.text_no_results');
		$data['text_confirm'] = lang('Leave.text_confirm');

		$data['column_leavename'] = lang('Leave.column_leavename');
		$data['column_status'] = lang('Leave.column_status');
		$data['column_date_added'] = lang('Leave.column_date_added');
		$data['column_action'] = lang('Leave.column_action');

		$data['button_add'] = lang('Leave.button_add');
		$data['button_edit'] = lang('Leave.button_edit');
		$data['button_delete'] = lang('Leave.button_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Leave\Views\leave', $data);
	}
	
	protected function getForm(){
		
		$this->template->add_package(array('ckeditor','ckfinder','colorbox','select2'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Leave.heading_title'),
			'href' => admin_url('leave')
		);

		$data['breadcrumbs'][] = array(
			'text' => lang('Leave.text_add'),
			'href' => admin_url('leave/add')
		);

		$_SESSION['isLoggedIn'] = true;
		
		$data['heading_title'] 	= lang('Leave.heading_title');
		
		$data['text_form'] = $this->uri->getSegment(4) ? lang('Leave.text_edit') : lang('Leave.text_add');
		$data['button_save'] = lang('Leave.button_save');
		$data['button_cancel'] = lang('Leave.button_cancel');
		$data['cancel'] = admin_url('leave');
		
		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->uri->getSegment(4) && ($this->request->getMethod('REQUEST_METHOD') != 'POST')) {
			$leave_info = $this->leaveModel->find($this->uri->getSegment(4));
		}

		foreach($this->leaveModel->getFieldNames($this->leaveModel->table) as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($leave_info->{$field}) && $leave_info->{$field}) {
				$data[$field] = $leave_info->{$field};
			} else {
				$data[$field] = '';
			}
		}
		
		$data['accuraltypes']=array('y'=>'yearly','m'=>'monthly');
		$data['leavetypes']=array('l'=>'leave','p'=>'present','a'=>'absent');
		
	
		echo $this->template->view('Admin\Leave\Views\leaveForm',$data);
	}

	protected function validateForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $rules = $this->leaveModel->validationRules;

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