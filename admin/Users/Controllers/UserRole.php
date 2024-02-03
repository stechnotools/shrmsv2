<?php
namespace Admin\Users\Controllers;
use Admin\Users\Models\UserRoleModel;
use App\Controllers\AdminController;

class UserRole extends AdminController {
	private $error = array();
    private $userRoleModel;
    private $permissionModel;

	public function __construct(){
        $this->userRoleModel=new UserRoleModel();
	}

	public function index(){
        $this->template->set_meta_title(lang('Users.heading_title'));
        return $this->getList();
    }

    public function add(){

        $this->template->set_meta_title(lang('UserRole.heading_title'));

        if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){

            $this->userRoleModel->insert($this->request->getPost());
            $this->session->setFlashdata('message', 'Role Saved Successfully.');

            return redirect()->to(base_url('admin/roles'));
        }
        $this->getForm();
    }

    public function edit(){

        $this->template->set_meta_title(lang('Users.heading_title'));

        if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){
            $id=$this->uri->getSegment(4);
            $this->userRoleModel->update($id,$this->request->getPost());
            $this->session->setFlashdata('message', 'Role Updated Successfully.');

            return redirect()->to(base_url('admin/roles'));
        }
        $this->getForm();
    }

    public function delete(){
        if ($this->request->getPost('selected')){
            $selected = $this->request->getPost('selected');
        }else{
            $selected = (array) $this->uri->getSegment(4);
        }
        $this->userRoleModel->delete($selected);
        $this->session->setFlashdata('message', 'UserRole deleted Successfully.');
        return redirect()->to(base_url('admin/roles'));
    }

    protected function getList() {

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => lang('UserRole.heading_title'),
            'href' => admin_url('roles')
        );

        $this->template->add_package(array('datatable'),true);

        $data['add'] = admin_url('roles/add');
        $data['delete'] = admin_url('roles/delete');
        $data['datatable_url'] = admin_url('roles/search');

        $data['heading_title'] = lang('UserRole.heading_title');

        $data['text_list'] = lang('UserRole.text_list');
        $data['text_no_results'] = lang('UserRole.text_no_results');
        $data['text_confirm'] = lang('UserRole.text_confirm');

        $data['button_add'] = lang('UserRole.button_add');
        $data['button_edit'] = lang('UserRole.button_edit');
        $data['button_delete'] = lang('UserRole.button_delete');

        if(isset($this->error['warning'])){
            $data['error'] 	= $this->error['warning'];
        }

        if ($this->request->getPost('selected')) {
            $data['selected'] = (array)$this->request->getPost('selected');
        } else {
            $data['selected'] = array();
        }

        return $this->template->view('Admin\Users\Views\userRole', $data);
    }

    public function search() {
        $requestData= $_REQUEST;
        $totalData = $this->userRoleModel->getTotal();
        $totalFiltered = $totalData;

        $filter_data = array(

            'filter_search' => $requestData['search']['value'],
            'order'  		 => $requestData['order'][0]['dir'],
            'sort' 			 => $requestData['order'][0]['column'],
            'start' 			 => $requestData['start'],
            'limit' 			 => $requestData['length']
        );
        $totalFiltered = $this->userRoleModel->getTotal($filter_data);

        $filteredData = $this->userRoleModel->getAll($filter_data);
        //printr($filteredData);
        $datatable=array();
        foreach($filteredData as $key=>$result) {

            $action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('permission/assign/'.$result->id).'"><i class="fa fa-list-alt"></i></a>';
			$action .= 		'<a class="btn btn-sm btn-info" href="'.admin_url('roles/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('roles/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
			$action .= '</div>';


            $datatable[]=array(
                '<div class="checkbox checkbox-primary checkbox-single">
					<input type="checkbox" name="selected[]" value="'.$result->id.'" />
					<label></label>
				</div>',
                $key+1,
                $result->name,
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
            'text' => lang('UserRole.heading_title'),
            'href' => admin_url('roles')
        );


        $data['heading_title'] 	= lang('UserRole.heading_title');
        $data['text_form'] = $this->uri->getSegment(4) ? "UserRole Edit" : "UserRole Add";
        $data['cancel'] = admin_url('roles');

        if(isset($this->error['warning'])){
            $data['error'] 	= $this->error['warning'];
        }

        if ($this->uri->getSegment(4) && ($this->request->getMethod(true) != 'POST')) {
            $userrole_info = $this->userRoleModel->find($this->uri->getSegment(4));
        }
        //printr($data['permissions']);
        foreach($this->userRoleModel->getFieldNames('user_role') as $field) {
            if($this->request->getPost($field)) {
                $data[$field] = $this->request->getPost($field);
            } else if(isset($userrole_info->{$field})) {
                $data[$field] = $userrole_info->{$field};
            } else {
                $data[$field] = '';
            }
        }

        $data['permission_accesses']=[
            ''=>'Select Permission Access',
            'state'=>'State',
            'district'=>'District',
            'block'=>'Block'
        ];
        $data['permission_levels']=[
            ''=>'Select Permission Level',
            'monitor'=>'Only Monitor',
            'approval'=>'Monitor/Approval'
        ];

        echo $this->template->view('Admin\Users\Views\userRoleForm',$data);
    }

    public function permission(){
        $id = $this->uri->getSegment(4);
        $data['user_role_id']=$id;

        if ($this->request->getMethod(1) === 'POST'){

            $this->userRoleModel->addUserRolePermission($id,$this->request->getPost());
            $this->session->setFlashdata('message', 'Permission Updated Successfully.');

            return redirect()->to(base_url('admin/roles'));
        }
        if((int)$id) {
            $userrole_info = $this->userRoleModel->find($id);
            $data['text_form'] = $userrole_info->name ." Permission";
            $data['cancel'] = admin_url('roles');
            $data['id']=$id;
            $data['gpermissions'] = (array)$this->permissionModel->get_modules_with_permission($id);
            if(empty($data['gpermissions'])) {
                $data['gpermissions'] = NULL;
            }
            //printr($data['gpermissions']);
            //exit;
            echo $this->template->view('Admin\Users\Views\userRolePermissionForm',$data);
        }else {

        }
    }

    protected function getFormOld(){

        $this->template->add_package(array('select2'),true);

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => lang('UserRole.heading_title'),
            'href' => admin_url('roles')
        );


        $data['heading_title'] 	= lang('UserRole.heading_title');
        $data['text_form'] = $this->uri->getSegment(4) ? "UserRole Edit" : "UserRole Add";
        $data['cancel'] = admin_url('roles');

        if(isset($this->error['warning'])){
            $data['error'] 	= $this->error['warning'];
        }

        if ($this->uri->getSegment(4) && ($this->request->getMethod(true) != 'POST')) {
            $userrole_info = $this->userRoleModel->find($this->uri->getSegment(4));
        }

        foreach($this->userRoleModel->getFieldNames('user_role') as $field) {
            if($this->request->getPost($field)) {
                $data[$field] = $this->request->getPost($field);
            } else if(isset($userrole_info->{$field}) && $userrole_info->{$field}) {
                $data[$field] = html_entity_decode($userrole_info->{$field},ENT_QUOTES, 'UTF-8');
            } else {
                $data[$field] = '';
            }
        }
        helper('filesystem');
		$ignore = array(
			'Login::index',
			'logout/index',
			'forgot-password',
			'Leftbar::index',
			'column_top/index',
			'errors/index',
			'Footer::index',
			'Header::index'
		);

		//$files = glob(DIR_ADMIN_MODULE . '*/controllers/*.php');
        $modules = directory_map(DIR_ADMIN_MODULE);

        $controllers=array();
        foreach($modules as $key=>$module){
            $modulename=stripslashes($key);
            $controller_info=[];
		    foreach($module as $ckey=>$contrs) {

                if ($ckey == "Controllers\\") {
                    foreach ($contrs as $cont) {
                        $controller = pathinfo($cont, PATHINFO_FILENAME);

                        $namespace = "Admin\\$key$ckey$controller";
                        $class = new $namespace();
                        $methods = $this->get_access_methods($class);

                        $con_method = [];
                        foreach ($methods as $cmethod) {
                            if (!in_array($controller . '::' . $cmethod, $ignore)) {
                                $con_method[] = $namespace . '::' . $cmethod;
                            }
                        }
                        if($con_method) {
                            $controller_info[] = array(
                                'controller' => $controller,
                                'methods' => $con_method
                            );
                        }

                    }
                }

            }
            $controllers[$modulename]=$controller_info;



        }
        $data['permissions']=$controllers;

        if ($this->request->getPost('permissions')) {
            $data['access'] = $this->request->getPost('permissions');
        } elseif ($userrole_info->permissions) {
            $data['access'] = json_decode($userrole_info->permissions, true);
        } else {
            $data['access'] = array();
        }
        echo $this->template->view('Admin\Users\Views\userRoleForm',$data);
    }

    protected function get_access_methods($class){

        $returnArray = array();
        //echo $class;
        foreach(get_class_methods($class) as $method){
            //echo $class;
            $reflect = new \ReflectionMethod($class, $method);
            if($reflect->isPublic() && !$reflect->isConstructor())
            {
                array_push($returnArray,$method);
            }
        }
        if($parent_class = get_parent_class($class)){
            $thismethod = get_class_methods($parent_class);
            $resthis_method = array_diff($returnArray, $thismethod);

        }
        else{
            $resthis_method = $returnArray;
        }

        return $resthis_method;
    }

    protected function validateForm() {
        //printr($_POST);
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
        $regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
        $regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor

        $rules = $this->userRoleModel->validationRules;

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