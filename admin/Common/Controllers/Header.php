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
		$data['name']=$this->user->getFirstName();
		if($this->user->isLogged()){
			$leftbar = new Leftbar(); // Create an instance
			$data['menu']=$leftbar->index();
		}

        if ($this->uri->getSegment(1)) {
            $data['class'] = $this->uri->getSegment(1);
        } else if(!$this->user->isLogged()){
            $data['class'] = 'login-page';
        }else{
            $data['class']="admin";
        }


		return view('Admin\Common\Views\header',$data);
		
	}
}
