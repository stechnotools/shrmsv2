<?php
namespace Admin\Common\Controllers;
use App\Controllers\AdminController;

class Footer extends AdminController
{
	public function index()
	{
		return view('Admin\Common\Views\footer');
		
	}
}

return  __NAMESPACE__ ."\Footer";
?>