<?php
namespace Admin\Users\Controllers;

use Admin\Branch\Models\BranchModel;
use Admin\Localisation\Models\CountryModel;
use App\Controllers\AdminController;
use Admin\Localisation\Models\DistrictModel;
use Admin\Users\Models\UserGroupModel;
use Admin\Users\Models\UserModel;
use Admin\Users\Models\UserRoleModel;
use Config\Database;

class Users extends AdminController{
	private $error = array();
	private $userModel;

	public function __construct(){
		$this->userModel=new UserModel();
    }

	public function index(){
		$this->template->set_meta_title(lang('Users.heading_title'));
		return $this->getList();
	}

	public function add(){

		$this->template->set_meta_title(lang('Users.heading_title'));

		if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){

            $userid=$this->userModel->insert($this->request->getPost());

			$this->session->setFlashdata('message', 'User Saved Successfully.');

			return redirect()->to(base_url('admin/users'));
		}
		$this->getForm();
	}

	public function edit(){


		$this->template->set_meta_title(lang('Users.heading_title'));

		if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){
			$id=$this->uri->getSegment(4);
			$udata=array(
				'firstname'=>$this->request->getPost('firstname'),
				'user_group_id'=>$this->request->getPost('user_group_id'),
				'email'=>$this->request->getPost('email'),
				'phone'=>$this->request->getPost('email')

			);


			$this->userModel->update($id,$this->request->getPost());

			$this->session->setFlashdata('message', 'User Updated Successfully.');

			return redirect()->to(base_url('admin/users'));
		}
		$this->getForm();
	}

	public function delete(){

		if ($this->request->getPost('selected')){
			$selected = $this->request->getPost('selected');
		}else{
			$selected = (array) $this->uri->getSegment(4);
		}
		$this->userModel->delete($selected);


		$this->session->setFlashdata('message', 'User deleted Successfully.');
		return redirect()->to(admin_url('users'));
	}

	protected function getList() {

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Users.heading_title'),
			'href' => admin_url('users')
		);

		$this->template->add_package(array('datatable'),true);

		$data['add'] = admin_url('users/add');
		$data['delete'] = admin_url('users/delete');
		$data['datatable_url'] = admin_url('users/search');

		$data['heading_title'] = lang('Users.heading_title');

		$data['text_list'] = lang('Users.text_list');
		$data['text_no_results'] = lang('Users.text_no_results');
		$data['text_confirm'] = lang('Users.text_confirm');

		$data['button_add'] = lang('Users.button_add');
		$data['button_edit'] = lang('Users.button_edit');
		$data['button_delete'] = lang('Users.button_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Users\Views\user', $data);
	}

	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->userModel->getTotal();
		$totalFiltered = $totalData;

		$filter_data = array(

			'filter_search' => $requestData['search']['value'],
			'order'  		 => $requestData['order'][0]['dir'],
			'sort' 			 => $requestData['order'][0]['column'],
			'start' 			 => $requestData['start'],
			'limit' 			 => $requestData['length']
		);
		$totalFiltered = $this->userModel->getTotal($filter_data);

		$filteredData = $this->userModel->getAll($filter_data);
		//printr($filteredData);
		$datatable=array();
		foreach($filteredData as $key=>$result) {

			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('users/login/'.$result->id).'"><i class="fa fa-key"></i></a>';
			$action .= 		'<a class="btn btn-sm btn-info" href="'.admin_url('users/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('users/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
			$action .= '</div>';

			if (is_file(DIR_UPLOAD . $result->image)) {
				$image = resize($result->image, 40, 40);
			} else {
				$image = resize('no_image.png', 40, 40);
			}

			$datatable[]=array(
				'<div class="checkbox checkbox-primary checkbox-single">
					<input type="checkbox" name="selected[]" value="'.$result->id.'" />
					<label></label>
				</div>',
				'<img src="'.$image.'" alt="'.$result->name.'" class="img-fluid" />',
				$result->name,
				$result->username,
				$result->phone,
				$result->email,
				$result->role,
				$result->enabled?'Enable':'Disable',
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

	public function login(){
	    $id=$this->uri->getSegment(4);
        $user = $this->userModel->find($id);
        $this->session->set('temp_user',$this->user->getUser());
        $this->session->set('user',$user);
        $this->user->assignUserAttr($user);
        return redirect()->to(base_url('admin'));
    }

	protected function getForm(){

		$this->template->add_package(array('ckfinder','select2'),true);

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Users.heading_title'),
			'href' => admin_url('users')
		);


		$data['heading_title'] 	= lang('Users.heading_title');
		$data['text_form'] = $this->uri->getSegment(3) ? "User Edit" : "User Add";
		$data['text_image'] =lang('Users.text_image');
		$data['text_none'] = lang('Users.text_none');
		$data['text_clear'] = lang('Users.text_clear');
		$data['cancel'] = admin_url('users');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->uri->getSegment(4) && ($this->request->getMethod(true) != 'POST')) {
			$user_info = $this->userModel->find($this->uri->getSegment(4));
		}

		foreach($this->userModel->getFieldNames('user') as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($user_info->{$field}) && $user_info->{$field}) {
				$data[$field] = html_entity_decode($user_info->{$field},ENT_QUOTES, 'UTF-8');
			} else {
				$data[$field] = '';
			}
		}

		if ($this->request->getPost('image') && is_file(DIR_UPLOAD . $this->request->getPost('image'))) {
			$data['thumb_image'] = resize($this->request->getPost('image'), 100, 100);
		} elseif (!empty($user_info) && is_file(DIR_UPLOAD . $user_info->image)) {
			$data['thumb_image'] = resize($user_info->image, 100, 100);
		} else {
			$data['thumb_image'] = resize('no_image.png', 100, 100);
		}

		$data['no_image'] = resize('no_image.png', 100, 100);

		if ($this->request->getPost('password')) {
			$data['password'] = $this->request->getPost('password');
		} elseif (!empty($user_info)) {
			$data['password'] = $user_info->show_password;
		} else {
			$data['password'] = '';
		}


		$data['user_groups'] =  (new UserGroupModel())->findAll();

		$data['countries']=(new CountryModel())->getAll();
		$data['branches']=(new BranchModel())->getAll();


		echo $this->template->view('Admin\Users\Views\userForm',$data);
	}

	protected function validateForm() {
		//printr($_POST);
		$validation =  \Config\Services::validation();
		$id=$this->uri->getSegment(4);
		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
		$regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
		$regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor

		$rules = $this->userModel->validationRules;

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