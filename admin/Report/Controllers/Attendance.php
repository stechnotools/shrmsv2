<?php
namespace Admin\Report\Controllers;
use App\Controllers\AdminController;

class Attendance extends AdminController{
	private $error = array();

    function __construct(){
        //$this->attendanceModel=new AttendanceModel();
    }

	public function index() {
        $data['title']="Attendance";
        return $this->template->view('Admin\Report\Views\attendance',$data);
	}
}

return  __NAMESPACE__ ."\\Attendance";
