<?php
namespace Admin\Report\Controllers;
use Admin\Forms\Controllers\Livestock;
use Admin\Forms\Models\AggricultureModel;
use Admin\Forms\Models\FisheryModel;
use Admin\Forms\Models\HorticultureModel;
use Admin\Forms\Models\HouseholdModel;
use Admin\Forms\Models\LivestockModel;
use App\Controllers\AdminController;

class MComponent extends AdminController{
	private $error = array();

    function __construct(){
        //$this->componentModel=new ComponentModel();
    }

	public function index() {
        $householdModel=new HouseholdModel();
        $data['household']=$householdModel->countAll();
        $aggricultureModel=new AggricultureModel();
        $data['aggriculture']=$aggricultureModel->countAll();
        $horticultureModel=new HorticultureModel();
        $data['horticulture']=$horticultureModel->countAll();
        $fisheryModel=new FisheryModel();
        $data['fishery']=$fisheryModel->countAll();
        $livestockModel=new LivestockModel();
        $data['livestock']=$livestockModel->countAll();

        return $this->template->view('Admin\Report\Views\component_total',$data,true);
	}
}

return  __NAMESPACE__ ."\\ComponentTotal";
