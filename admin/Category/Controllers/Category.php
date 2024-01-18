<?php
namespace Admin\Category\Controllers;
use Admin\Category\Models\CategoryModel;
use App\Controllers\AdminController;

class Category extends AdminController {
	private $error = array();
	private $categoryModel;
	public function __construct(){
        $this->categoryModel=new CategoryModel();
	}
	
	public function index(){
		$this->template->set_meta_title(lang('Category.heading_title'));
        return $this->getList();
	}
	
	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->categoryModel->getTotal();
		
		$totalFiltered = $totalData;
		
		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->categoryModel->getTotal($filter_data);
			
		$filteredData = $this->categoryModel->getAll($filter_data);
		//printr($filteredData);
		
		$datatable=array();
		foreach($filteredData as $result) {
			
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('category/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('category/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
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
		$this->template->set_meta_title(lang('Category.heading_title'));
		
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			
			$this->categoryModel->insert($this->request->getPost());
			
			$this->session->setFlashdata('message', 'Category Saved Successfully.');
			return redirect()->to(admin_url('category'));
		
		}
		$this->getForm();
	}
	
	public function edit(){
		
		$this->template->set_meta_title(lang('Category.heading_title'));
		
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){	
			$category_id=$this->uri->getSegment(4);
			$this->categoryModel->update($category_id,$this->request->getPost());
			
			$this->session->setFlashdata('message', 'Category Updated Successfully.');
			return redirect()->to(admin_url('category'));
		}
		$this->getForm();
	}
	
	public function delete(){
		if ($this->request->getPost('selected')){
         	$selected = $this->request->getPost('selected');
      	}else{
         	$selected = (array) $this->uri->getSegment(4);
       	}
		$this->categoryModel->whereIn("id",$selected)->delete();
		$this->session->setFlashdata('message', 'Category deleted Successfully.');
		return redirect()->to(admin_url('category'));
	}
	
	protected function getList() {
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Category.heading_title'),
			'href' => admin_url('category')
		);
		
		$this->template->add_package(array('datatable'),true);
      

		$data['add'] = admin_url('category/add');
		$data['delete'] = admin_url('category/delete');
		$data['datatable_url'] = admin_url('category/search');

		$data['heading_title'] = lang('Category.heading_title');
		
		$data['text_list'] = lang('Category.text_list');
		$data['text_no_results'] = lang('Category.text_no_results');
		$data['text_confirm'] = lang('Category.text_confirm');

		$data['column_categoryname'] = lang('Category.column_categoryname');
		$data['column_status'] = lang('Category.column_status');
		$data['column_date_added'] = lang('Category.column_date_added');
		$data['column_action'] = lang('Category.column_action');

		$data['button_add'] = lang('Category.button_add');
		$data['button_edit'] = lang('Category.button_edit');
		$data['button_delete'] = lang('Category.button_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Category\Views\category', $data);
	}
	
	protected function getForm(){
		
		$this->template->add_package(array('ckeditor','ckfinder','colorbox','select2'),true);
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Category.heading_title'),
			'href' => admin_url('category')
		);

		$data['breadcrumbs'][] = array(
			'text' => lang('Category.text_add'),
			'href' => admin_url('category/add')
		);

		$_SESSION['isLoggedIn'] = true;
		
		$data['heading_title'] 	= lang('Category.heading_title');
		
		$data['text_form'] = $this->uri->getSegment(4) ? lang('Category.text_edit') : lang('Category.text_add');
		$data['button_save'] = lang('Category.button_save');
		$data['button_cancel'] = lang('Category.button_cancel');
		$data['cancel'] = admin_url('category');
		
		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}
		
		if ($this->uri->getSegment(4) && ($this->request->getMethod('REQUEST_METHOD') != 'POST')) {
			$category_info = $this->categoryModel->find($this->uri->getSegment(4));
		}

		foreach($this->categoryModel->getFieldNames($this->categoryModel->table) as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($category_info->{$field}) && $category_info->{$field}) {
				$data[$field] = $category_info->{$field};
			} else {
				$data[$field] = '';
			}
		}
		
	
		echo $this->template->view('Admin\Category\Views\categoryForm',$data);
	}

	protected function validateForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $rules = $this->categoryModel->validationRules;

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