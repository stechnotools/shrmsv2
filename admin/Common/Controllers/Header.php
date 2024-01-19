<?php
namespace Admin\Common\Controllers;
use App\Controllers\AdminController;
use Admin\Common\Controllers\Leftbar;

class Header extends AdminController
{
	public function __construct()
    {
		$this->settings = new \Config\Settings();
	}
	public function index()
	{
		$data=array();

		$data['logout'] = admin_url('logout');
        $data['profile_img'] = $this->user->getImage();
        $data['profile'] = admin_url('common/profile');
        $data['settings'] = admin_url('setting');
        $data['lock'] = admin_url('common/lock');


        if($this->session->get('temp_user')){
		    $data['relogin']=true;
        }else {
            $data['relogin']=false;
        }
		$data['name']=$this->user->getFullName();
		if($this->user->isLogged()){
			$leftbar = new Leftbar(); // Create an instance
			$data['menu']=$leftbar->index();
		}

        if (($this->uri->getSegment(1)=='admin' || $this->uri->getSegment(1)=='') && !$this->user->isLogged()) {
			$data['class'] = 'login-page';
		} else {
			$data['class'] = 'admin';
		}

		$data['bootstrapStylesheet']=theme_url('assets/css/bootstrap-dark.min.css');
		$data['appStylesheet']=theme_url('assets/css/app-dark.css');

		if($this->settings->config_admin_theme_mode=="light"){
			$data['bootstrapStylesheet']=theme_url('assets/css/bootstrap.min.css');
			$data['appStylesheet']=theme_url('assets/css/app.min.css');
		}


		return view('Admin\Common\Views\header',$data);

	}
}
