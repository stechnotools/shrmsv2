<?php
namespace Admin\Common\Controllers;
use App\Controllers\AdminController;
use App\Libraries\User;

class Auth extends AdminController
{
	public function __construct()
    {
		$this->uri = service('uri');
	}
	public function login() {

        $data['login_error']='';
        if($this->user->isLogged()){
            return redirect()->to(admin_url());
        }

        if($this->request->getMethod(1)=='POST'){
            $logged_in = $this->user->login(
                $this->request->getPost('username'),
                $this->request->getPost('password')
            );
            if($logged_in){
                if ($this->request->getPost('redirect') && (strpos($this->request->getPost('redirect'), admin_url()) == 0 )) {
					return redirect()->to($this->request->getPost('redirect'));
				} else{
					return redirect()->to(admin_url());
				}
            } else {
                $data['login_error']=$this->session->getFlashdata('error');
            }
        }

        $data['action']=admin_url('login');
        $data['heading_title']				= lang('Auth.heading_title');

        $data['text_login'] 				= lang('Auth.text_login');
        $data['text_forgotten']				= lang('Auth.text_forgotten');
        $data['text_remember'] 				= lang('Auth.text_remember');
        $data['text_register'] 				= lang('Auth.text_register');

        $data['entry_username'] 			= lang('Auth.entry_username');
        $data['entry_password'] 			= lang('Auth.entry_password');
        $data['entry_confirmpassword'] 	    = lang('Auth.entry_confirmpassword');
        $data['entry_email'] 				= lang('Auth.entry_email');
        $data['entry_forgotten'] 			= lang('Auth.entry_forgotten');

        $data['button_login'] 				= lang('Auth.button_login');
        $data['button_register'] 			= lang('Auth.button_register');
        $data['button_reset'] 				= lang('Auth.button_reset');



		if($this->uri->getTotalSegments() > 0){
			$route=uri_string();
			$data['redirect'] = $route;
		} else {
			$data['redirect'] = '';
		}

		return $this->template->view('Admin\Common\Views\login',$data);
	}
	public function logout() {
        $this->user->logout();
        $this->session->remove('redirect');
        return redirect()->to(admin_url());
	}
	public function reLogin(){
        $user = $this->session->get('temp_user');
        $this->session->set('user',$user);
        $this->user->assignUserAttr($user);
        $this->session->remove('temp_user');
        return redirect()->to(admin_url());
    }
}
