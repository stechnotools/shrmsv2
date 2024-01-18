<?php
namespace Admin\Report\Controllers;
use Admin\Forms\Models\AggricultureModel;
use Admin\Forms\Models\HorticultureModel;
use Admin\Forms\Models\HouseholdModel;
use App\Controllers\AdminController;

class Component extends AdminController{
	private $error = array();

    function __construct(){
        //$this->componentModel=new ComponentModel();
    }

	public function index() {
        $householdModel=new HouseholdModel();
        $data['household']=$householdModel->getTotal();

        $aggricultureModel=new AggricultureModel();
        $data['aggriculture']=$aggricultureModel->getTotal();

        $horticultureModel=new HorticultureModel();
        $data['horticulture']=$horticultureModel->getTotal();

        return $this->template->view('Admin\Report\Views\component',$data,true);
	}
}

return  __NAMESPACE__ ."\\Component";
