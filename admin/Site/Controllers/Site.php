<?php
namespace Admin\Site\Controllers;

use Admin\Designation\Models\DesignationModel;
use Admin\Site\Models\SiteModel;
use App\Controllers\AdminController;

class Site extends AdminController {
	private $error = array();
	private $siteModel;
	public function __construct(){
        $this->siteModel=new SiteModel();
	}

	public function index(){
		$this->template->set_meta_title(lang('Site.heading_title'));
        return $this->getList();
	}

	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->siteModel->getTotal();

		$totalFiltered = $totalData;

		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->siteModel->getTotal($filter_data);

		$filteredData = $this->siteModel->getAll($filter_data);
		//printr($filteredData);

		$datatable=array();
		foreach($filteredData as $result) {
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('site/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('site/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
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
		$this->template->set_meta_title(lang('Site.heading_title'));

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){

			$this->siteModel->addSite($this->request->getPost());

			$this->session->setFlashdata('message', 'Site Saved Successfully.');
			return redirect()->to(admin_url('site'));

		}
		$this->getForm();
	}

	public function edit(){

		$this->template->set_meta_title(lang('Site.heading_title'));

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){
			$site_id=$this->uri->getSegment(4);
			$this->siteModel->editSite($site_id,$this->request->getPost());

			$this->session->setFlashdata('message', 'Site Updated Successfully.');
			return redirect()->to(admin_url('site'));
		}
		$this->getForm();
	}

	public function delete(){
		if ($this->request->getPost('selected')){
         $selected = $this->request->getPost('selected');
      }else{
         $selected = (array) $this->uri->getSegment(4);
       }
		$this->siteModel->deleteSite($selected);
		$this->session->setFlashdata('message', 'Site deleted Successfully.');
		return redirect()->to(admin_url('site'));
	}

	protected function getList() {

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Site.heading_title'),
			'href' => admin_url('site')
		);

		$this->template->add_package(array('datatable'),true);


		$data['add'] = admin_url('site/add');
		$data['delete'] = admin_url('site/delete');
		$data['datatable_url'] = admin_url('site/search');

		$data['heading_title'] = lang('Site.heading_title');

		$data['text_list'] = lang('Site.text_list');
		$data['text_no_results'] = lang('Site.text_no_results');
		$data['text_confirm'] = lang('Site.text_confirm');

		$data['column_sitename'] = lang('Site.column_sitename');
		$data['column_status'] = lang('Site.column_status');
		$data['column_date_added'] = lang('Site.column_date_added');
		$data['column_action'] = lang('Site.column_action');

		$data['button_add'] = lang('Site.button_add');
		$data['button_edit'] = lang('Site.button_edit');
		$data['button_delete'] = lang('Site.button_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Site\Views\site', $data);
	}

	protected function getForm(){

		$this->template->add_package(array('ckeditor','ckfinder','colorbox','select2'),true);

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Site.heading_title'),
			'href' => admin_url('site')
		);

		$data['breadcrumbs'][] = array(
			'text' => lang('Site.text_add'),
			'href' => admin_url('site/add')
		);

		$_SESSION['isLoggedIn'] = true;

		$data['heading_title'] 	= lang('Site.heading_title');

		$data['text_form'] = $this->uri->getSegment(4) ? lang('Site.text_edit') : lang('Site.text_add');
		$data['button_save'] = lang('Site.button_save');
		$data['button_cancel'] = lang('Site.button_cancel');
		$data['cancel'] = admin_url('site');

		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->uri->getSegment(4) && ($this->request->getMethod('REQUEST_METHOD') != 'POST')) {
			$site_info = $this->siteModel->find($this->uri->getSegment(4));
		}

		foreach($this->siteModel->getFieldNames($this->siteModel->table) as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($site_info->{$field}) && $site_info->{$field}) {
				$data[$field] = $site_info->{$field};
			} else {
				$data[$field] = '';
			}
		}

		// Salaries
		if ($this->request->getPost('site_salary')) {
			$site_salaries = $this->request->getPost('site_salary');
		} elseif ($this->uri->getSegment(4)) {
			$site_salaries = $this->siteModel->getSiteSalaries($this->uri->getSegment(4));
		} else {
			$site_salaries = array();
		}

		$data['site_salaries'] = array();

		foreach ($site_salaries as $site_salary) {
			$data['site_salaries'][] = array(
				'designation_id'=> $site_salary['designation_id'],
				'type' => $site_salary['type'],
				'salary' => $site_salary['salary']
			);
		}
		$data['designations']=(new DesignationModel())->getAll();


		echo $this->template->view('Admin\Site\Views\siteForm',$data);
	}

	protected function validateForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $rules = $this->siteModel->validationRules;

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