<?php
namespace Admin\Localisation\Controllers;
use App\Controllers\AdminController;
use Admin\Localisation\Models\BlockModel;
use Admin\Localisation\Models\CountryModel;

class Country extends AdminController{
    private $error = array();
    private $countryModel;

    public function __construct(){
        $this->countryModel=new CountryModel();
    }

    public function index(){
        $this->template->set_meta_title(lang('Country.heading_title'));
        return $this->getList();
    }

    public function add(){

        $this->template->set_meta_title(lang('Country.heading_title'));

        if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){

            $id=$this->countryModel->insert($this->request->getPost());

            $this->session->setFlashdata('message', 'Country Saved Successfully.');

            return redirect()->to(base_url('admin/country'));
        }
        $this->getForm();
    }

    public function edit(){

        $this->template->set_meta_title(lang('Country.heading_title'));

        if ($this->request->getMethod(1) === 'POST' && $this->validateForm()){
            $id=$this->uri->getSegment(4);

            $this->countryModel->update($id,$this->request->getPost());

            $this->session->setFlashdata('message', 'Country Updated Successfully.');

            if($this->request->isAJAX()){
                echo 1;
                exit;
            }else{
                return redirect()->to(base_url('admin/country'));
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
        $this->countryModel->delete($selected);

        $this->session->setFlashdata('message', 'Country deleted Successfully.');
        return redirect()->to(base_url('admin/country'));
    }

    protected function getList() {

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => lang('Country.heading_title'),
            'href' => admin_url('country')
        );

        $this->template->add_package(array('datatable'),true);

        $data['add'] = admin_url('country/add');
        $data['delete'] = admin_url('country/delete');
        $data['datatable_url'] = admin_url('country/search');

        $data['heading_title'] = lang('Country.heading_title');

        $data['text_list'] = lang('Country.text_list');
        $data['text_no_results'] = lang('Country.text_no_results');
        $data['text_confirm'] = lang('Country.text_confirm');

        $data['button_add'] = lang('Country.button_add');
        $data['button_edit'] = lang('Country.button_edit');
        $data['button_delete'] = lang('Country.button_delete');

        if(isset($this->error['warning'])){
            $data['error'] 	= $this->error['warning'];
        }

        if ($this->request->getPost('selected')) {
            $data['selected'] = (array)$this->request->getPost('selected');
        } else {
            $data['selected'] = array();
        }

        return $this->template->view('Admin\Localisation\Views\country', $data);
    }

    public function search() {
        $requestData= $_REQUEST;
        $totalData = $this->countryModel->getTotals();
        $totalFiltered = $totalData;

        $filter_data = array(
            'filter_search' => $requestData['search']['value'],
            'order'  		 => $requestData['order'][0]['dir'],
            'sort' 			 => $requestData['order'][0]['column'],
            'start' 			 => $requestData['start'],
            'limit' 			 => $requestData['length']
        );
        $totalFiltered = $this->countryModel->getTotals($filter_data);

        $filteredData = $this->countryModel->getAll($filter_data);

        $datatable=array();
        foreach($filteredData as $result) {

            $action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('country/edit/'.$result->id).'"><i class="fas fa-pencil-alt"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('country/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
			$action .= '</div>';

            $datatable[]=array(
                '<div class="checkbox checkbox-primary checkbox-single">
                    <input type="checkbox" name="selected[]" value="'.$result->id.'" />
                    <label></label>
                </div>', 
                $result->name,
                $result->iso_code_2,
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

        $this->template->add_package(array('colorbox'),true);

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => lang('Country.heading_title'),
            'href' => admin_url('country')
        );

        //printr($_SESSION);
        $_SESSION['isLoggedIn'] = true;

        $data['heading_title'] 	= lang('Country.heading_title');
        $data['text_form'] = $this->uri->getSegment(3) ? "Country Edit" : "Country Add";
        $data['cancel'] = admin_url('country');
        $data['button_save'] = lang('Country.button_save');
		$data['button_cancel'] = lang('Country.button_cancel');

        if(isset($this->error['warning'])){
            $data['error'] 	= $this->error['warning'];
        }

        if ($this->uri->getSegment(3) && ($this->request->getMethod(true) != 'POST')) {
            $country_info = $this->countryModel->find($this->uri->getSegment(3));
        }

        foreach($this->countryModel->getFieldNames('country') as $field) {
            if($this->request->getPost($field)) {
                $data[$field] = $this->request->getPost($field);
            } else if(isset($country_info->{$field}) && $country_info->{$field}) {
                $data[$field] = html_entity_decode($country_info->{$field},ENT_QUOTES, 'UTF-8');
            } else {
                $data[$field] = '';
            }
        }
        if($this->request->isAJAX()){
            echo  $this->template->view('Admin\Localisation\Views\countryForm', $data,true);
        }else {
            echo $this->template->view('Admin\Localisation\Views\countryForm', $data);
        }
    }

    protected function validateForm() {
        //printr($_POST);
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
        $regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
        $regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor 

        $rules = $this->countryModel->validationRules;

        /*$rules=array(
            
            'title' => array(
                'label' => 'Title', 
                'rules' => 'trim|required|max_length[100]'
            ),
            
            'slug' => array(
                'field' => 'slug', 
                'label' => 'Slug', 
                'rules' => "trim|required|max_length[255]|regex_match[/^$regex$/]|is_unique[seo_url.slug,route_id,{id}]"
            ),
            'meta_title' => array(
                'field' => 'meta_title', 
                'label' => 'Meta Title', 
                'rules' => 'trim'
            ), 
            'meta_description' => array(
                'field' => 'meta_description', 
                'label' => 'Meta Description', 
                'rules' => 'trim'
            ),
            'meta_keywords' => array(
                'field' => 'meta_keywords', 
                'label' => 'Meta Keywords', 
                'rules' => 'trim'
            ),
            'status' => array(
                'field' => 'status', 
                'label' => 'Status', 
                'rules' => 'trim|required'
            ),
            
        );*/

        //$validation->setRules($rules);

        if ($this->validate($rules)){
            return true;
        }
        else{
            //printr($validation->getErrors());
            $this->error['warning']="Warning: Please check the form carefully for errors!";
            return false;
        }
        //return !$this->error;
    }

    public function block($country=''){
        if (is_ajax()){
            $blockModel=new BlockModel();
            if(!is_numeric($country)){
                $countryrow=$this->countryModel->where('code', $country)->first();

                $country=$countryrow->id;
            }
            $json = array(
                'country'  	=> $country,
                'block'        => $blockModel->getBlocksByCountry($country)
            );
            echo json_encode($json);
        }else{
            return show_404();
        }
    }
}
