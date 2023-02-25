<?php
namespace Admin\Setting\Controllers;
use Admin\Banner\Models\BannerModel;
use Admin\Pages\Models\PagesModel;
use Admin\Setting\Models\SettingModel;
use Admin\Users\Models\UserGroupModel;
use Admin\Users\Models\UserModel;
use App\Controllers\AdminController;

class Msubmission extends AdminController {
	private $error = array();
	
	function __construct(){
        $this->odk = service('odkcentral');
	}
	
	public function index(){
		// Init
      	$data = array();
        $this->template->set_meta_title(lang('Setting.heading_title'));
        $this->template->add_package(array('ckfinder','colorbox','select2'),true);

        $data['heading_title'] 	= lang('Setting.heading_title');
        $data['button_save'] = lang('Setting.button_save');
        $data['button_cancel'] = lang('Setting.button_cancel');


        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => lang('banner.heading_title'),
            'href' => admin_url('banner')
        );
		
		
		if ($this->request->getMethod(1) === 'POST'){

            $projectId=(int)$this->request->getPost('projectId');
            $xmlFormId=$this->request->getPost('formId');

            $file = $this->request->getFile('submissiondata');

            if($file->isValid()){
                $path=$file->getRealPath();
                //echo $projectId;
                $submissiondata= file_get_contents($path);
                $submissions = $this->odk->projects($projectId)->forms($xmlFormId)->submissions()->import($submissiondata);
                //printr($submissions);
                //exit;
                if($submissions){
                    if(isset($submissions['message'])){
                        $message=$submissions['message'];
                    }else{
                        $message="submitted successfully";
                    }
                    $this->session->setFlashdata('message', $message);

                    return redirect()->to(admin_url('msubmission'));
                }
            }

		}
		
		
		$data['action'] = admin_url('setting');
		$data['cancel'] = admin_url('setting');
        
		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}

		$data['projects']=$this->odk->projects()->get();

        echo $this->template->view('Admin\Setting\Views\msubmission',$data);

	}

	public function getForms($projectId){

        $json=[];
        $projectId=(int)$projectId;
        $forms = $this->odk->projects($projectId)->forms()->get();
        //printr($forms);
        if($forms){
            $json['form']=$forms;
        }
        echo json_encode($json);
    }

    public function validateForms(){
        $validation =  \Config\Services::validation();

         $rules = [
             'projectId' => array(
                 'label' => 'Project Name',
                 'rules' => 'trim|required'
             ),
             'formId' => array(
                 'label' => 'Form name',
                 'rules' => "trim|required"
             ),
            'submissiondata' => [
                'label' => 'Submission File',
                'rules' => 'uploaded[submissiondata]'
                    . '|mime_in[submissiondata,text/xml,application/xml,text/plain]',
            ],
        ];
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