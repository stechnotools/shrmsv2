<?php
namespace Admin\Branch\Controllers;
use Admin\Branch\Models\BranchModel;
use App\Controllers\AdminController;

class Branch extends AdminController {
	private $error = array();
	private $branchModel;
	public function __construct(){
		$this->branchModel=new BranchModel();
	}

	public function index(){
		$this->template->set_meta_title(lang('Branch.heading_title'));
		return $this->getList();
	}

	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->branchModel->getTotal();

		$totalFiltered = $totalData;

		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->branchModel->getTotal($filter_data);

		$filteredData = $this->branchModel->getAll($filter_data);

		$datatable=array();
		foreach($filteredData as $result) {

			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('branch/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('branch/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
			$action .= '</div>';

			$datatable[]=array(
				'<div class="checkbox checkbox-primary checkbox-single">
					<input type="checkbox" name="selected[]" value="'.$result->id.'" />
					<label></label>
				</div>',
				$result->code,
				$result->name,
				$result->address,
				$result->short,
				$result->email,
				$action
			);

		}
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
		$this->template->set_meta_title(lang('Branch.heading_title'));

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST'){

			if($this->validateForm()){
				$branchid=$this->branchModel->insert($this->request->getPost());
				if(is_ajax()){
					$json=[
						'value'=>$branchid,
						'label'=>$this->request->getPost('name')
					];
					return $this->response->setContentType('application/json')->setJSON($json);
				}
				return redirect()->to(admin_url('branch'))->with('message', 'Branch Saved Successfully.');

			}elseif(is_ajax()){
				$json = [];
				if(isset($this->error['warning'])){
					$json['message'] = $this->error['warning'];
					$json['type']='error';
				}
				if(isset($this->error['errors'])){
					$json['errors'] 	= $this->error['errors'];
				}
				return $this->response->setContentType('application/json')->setJSON($json);

			}
		}
		$this->getForm();
	}

	public function edit(){

		$this->template->set_meta_title(lang('Branch.heading_title'));

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){
			$branch_id=$this->uri->getSegment(4);
			$this->branchModel->update($branch_id,$this->request->getPost());
			$this->session->setFlashdata('message', 'Branch Updated Successfully.');
			return redirect()->to(admin_url('branch'))->with('message', 'Branch Updated Successfully.');
		}
		$this->getForm();
	}

	public function delete(){
		if ($this->request->getPost('selected')){
         	$selected = $this->request->getPost('selected');
      	}else{
         	$selected = (array) $this->uri->getSegment(4);
       	}
		$this->branchModel->whereIn('id', $selected)->delete();
		$this->session->setFlashdata('message', 'Branch deleted Successfully.');
        return redirect()->to(admin_url('branch'))->with('message', 'Branch deleted Successfully.');;;
	}

	protected function getList() {

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Branch.heading_title'),
			'href' => admin_url('branch')
		);

		$this->template->add_package(array('datatable'),true);


		$data['add'] = admin_url('branch/add');
		$data['delete'] = admin_url('branch/delete');
		$data['datatable_url'] = admin_url('branch/search');

		$data['heading_title'] = lang('Branch.heading_title');

		$data['text_list'] = lang('Branch.text_list');
		$data['text_no_results'] = lang('Branch.text_no_results');
		$data['text_confirm'] = lang('Branch.text_confirm');

		$data['column_branchname'] = lang('Branch.column_branchname');
		$data['column_status'] = lang('Branch.column_status');
		$data['column_date_added'] = lang('Branch.column_date_added');
		$data['column_action'] = lang('Branch.column_action');

		$data['button_add'] = lang('Branch.text_add');
		$data['button_edit'] = lang('Branch.text_edit');
		$data['button_delete'] = lang('Branch.text_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Branch\Views\branch', $data);
	}

	protected function getForm(){

		$this->template->add_package(array('timepicker','select2'),true);
		$this->template->add_javascript('https://maps.googleapis.com/maps/api/js?key=AIzaSyAm4I8FCVWVTpnMXnKcGMlsBdAOmEtiB80&libraries=drawing,places&sensor=false',true);
        //$this->template->add_javascript('themes/admin/assets/js/map.js',true);

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Branch.heading_title'),
			'href' => admin_url('branch')
		);

		$data['breadcrumbs'][] = array(
			'text' => lang('Branch.text_add'),
			'href' => admin_url('branch/add')
		);

		$_SESSION['isLoggedIn'] = true;

		$data['heading_title'] 	= lang('Branch.heading_title');

		$data['text_form'] = $this->uri->getSegment(4) ? lang('Branch.text_edit') : lang('Branch.text_add');
		$data['button_save'] = lang('Branch.button_save');
		$data['button_cancel'] = lang('Branch.button_cancel');
		$data['cancel'] = admin_url('branch');

		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->uri->getSegment(4) && ($this->request->getMethod('REQUEST_METHOD') != 'POST')) {
			$branch_info = $this->branchModel->find($this->uri->getSegment(4));
		}

		foreach($this->branchModel->getFieldNames($this->branchModel->table) as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($branch_info->{$field}) && $branch_info->{$field}) {
				$data[$field] = $branch_info->{$field};
			} else {
				$data[$field] = '';
			}
		}

		if($this->request->getPost('envirnment')) {
			$data['envirnment'] = $this->request->getPost('envirnment');
		} else if(isset($branch_info->envirnment) && $branch_info->envirnment) {
			$data['envirnment'] = json_decode($branch_info->envirnment,true);
		} else {
			$data['envirnment'] = [];
		}

		if($data['boundary']){
			$points=explode("\n",$data['boundary']);
			foreach($points as &$v){
				$v = explode(',',$v);
			}

			$data['points']=$points;
		}else{
			$data['points']=[];
		}

		$data['rule_types']=['absent'=>'Absent','weekend'=>'Weekend'];


		echo $this->template->view('Admin\Branch\Views\branchForm',$data);
	}

	protected function validateForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $rules = $this->branchModel->validationRules;

        if ($this->validate($rules)){
            return true;
        }
        else{
            $this->error['errors']=$validation->getErrors();
            $this->error['warning']="Warning: Please check the form carefully for errors!";
            return false;
        }
        return !$this->error;
	}

	public function shift($branch_id){
		if (is_ajax()){
			$this->load->model('shift/shift_model');
			$json = array();
			$json = array(
				'branch_id'  	=> $branch_id,
				'shift'     => $this->shift_model->getShiftByBranch($branch_id)
			);
			echo json_encode($json);
		}else{
         	return show_404();
      	}
	}

}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */