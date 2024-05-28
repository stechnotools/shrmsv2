<?php
namespace Admin\Salary\Controllers;

use Admin\Leave\Models\LeaveModel;
use Admin\Salary\Models\SFieldModel;
use App\Controllers\AdminController;

class SField extends AdminController {
	private $error = array();
	private $sfieldModel;
	public function __construct(){
        $this->sfieldModel=new SFieldModel();
	}

	public function index(){
		$this->template->set_meta_title(lang('SField.heading_title'));
        return $this->getList();
	}

	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->sfieldModel->getTotal();

		$totalFiltered = $totalData;

		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->sfieldModel->getTotal($filter_data);

		$filteredData = $this->sfieldModel->getAll($filter_data);
		//printr($filteredData);

		$datatable=array();
		foreach($filteredData as $result) {

			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('sfield/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('sfield/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
			$action .= '</div>';

			$datatable[]=array(
				'<div class="checkbox checkbox-primary checkbox-single">
					<input type="checkbox" name="selected[]" value="'.$result->id.'" />
					<label></label>
				</div>',
				$result->name,
				$result->type,
				$result->status?'Yes':'No',
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
		$this->template->set_meta_title(lang('SField.heading_title'));

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){

			$this->sfieldModel->insert($this->request->getPost());

			$this->session->setFlashdata('message', 'SField Saved Successfully.');
			if($this->request->isAJAX()){
                echo "1";
                exit;
            }else{
                return redirect()->to(admin_url('sfield'));
            }

		}
		$this->getForm();
	}

	public function edit(){

		$this->template->set_meta_title(lang('SField.heading_title'));

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){
			$sfield_id=$this->uri->getSegment(4);
			$this->sfieldModel->update($sfield_id,$this->request->getPost());

			$this->session->setFlashdata('message', 'SField Updated Successfully.');
			if($this->request->isAJAX()){
                echo "1";
                exit;
            }else{
                return redirect()->to(admin_url('sfield'));
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
		$this->sfieldModel->whereIn('id', $selected)->delete();
		$this->session->setFlashdata('message', 'SField deleted Successfully.');
		return redirect()->to(admin_url('sfield'));
	}

	protected function getList() {

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('SField.heading_title'),
			'href' => admin_url('sfield')
		);

		$this->template->add_package(array('datatable'),true);


		$data['add'] = admin_url('sfield/add');
		$data['delete'] = admin_url('sfield/delete');
		$data['datatable_url'] = admin_url('sfield/search');

		$data['heading_title'] = lang('SField.heading_title');

		$data['text_list'] = lang('SField.text_list');
		$data['text_no_results'] = lang('SField.text_no_results');
		$data['text_confirm'] = lang('SField.text_confirm');

		$data['column_sfieldname'] = lang('SField.column_sfieldname');
		$data['column_status'] = lang('SField.column_status');
		$data['column_date_added'] = lang('SField.column_date_added');
		$data['column_action'] = lang('SField.column_action');

		$data['button_add'] = lang('SField.text_add');
		$data['button_edit'] = lang('SField.text_edit');
		$data['button_delete'] = lang('SField.text_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		return $this->template->view('Admin\Salary\Views\sfield', $data);
	}

	protected function getForm(){

		$this->template->add_package(array('select2'),true);

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('SField.heading_title'),
			'href' => admin_url('sfield')
		);

		$data['breadcrumbs'][] = array(
			'text' => lang('SField.text_add'),
			'href' => admin_url('sfield/add')
		);

		$_SESSION['isLoggedIn'] = true;

		$data['heading_title'] 	= lang('SField.heading_title');

		$data['text_form'] = $this->uri->getSegment(4) ? lang('SField.text_edit') : lang('SField.text_add');
		$data['button_save'] = lang('SField.button_save');
		$data['button_cancel'] = lang('SField.button_cancel');
		$data['cancel'] = admin_url('sfield');

		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->uri->getSegment(4) && ($this->request->getMethod('REQUEST_METHOD') != 'POST')) {
			$sfield_info = $this->sfieldModel->find($this->uri->getSegment(4));
		}

		foreach($this->sfieldModel->getFieldNames($this->sfieldModel->table) as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($sfield_info->{$field}) && $sfield_info->{$field}) {
				$data[$field] = $sfield_info->{$field};
			} else {
				$data[$field] = '';
			}
		}

		
		echo $this->template->view('Admin\Salary\Views\sfieldForm',$data);
	}

	protected function validateForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $rules = $this->sfieldModel->validationRules;

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
/* SField: ./application/widgets/hmvc/controllers/hmvc.php */