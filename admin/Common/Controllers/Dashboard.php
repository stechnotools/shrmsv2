<?php
namespace Admin\Common\Controllers;

use Admin\Setting\Models\SettingModel;
use App\Controllers\AdminController;
use Admin\Forms\Models\HouseholdModel;
use Admin\Forms\Models\AggricultureModel;
use Admin\Forms\Models\HorticultureModel;

class Dashboard extends AdminController
{
	public function __construct(){

    }
	public function index()
	{
        $settingModel=new SettingModel();
        $dashboards = array();
        $data=[];
        /*$extensions = $settingModel->getDashboardReports();
        foreach ($extensions as $code) {
            if ($code->status) {
                /*$class = '\\Admin\\Report\\Controllers\\' . $code->name;
                $obj = new $class();
                $output=$obj->index();*/
                //$output=view_cell('Admin\Report\Controllers\\'.$code->name.'::index');


                //$dashboards[] = array(
                    /*'code'       => $code->name,
                    'width'      => $code->col,
                    'sort_order' => $code->order,
                    'url'     => admin_url($code->name)
                );

            }
        }

        $sort_order = array();

        foreach ($dashboards as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $dashboards);

        // Split the array so the columns width is not more than 12 on each row.
        $width = 0;
        $column = array();
        $data['rows'] = array();

        foreach ($dashboards as $dashboard) {
            $column[] = $dashboard;

            $width = ($width + $dashboard['width']);

            if ($width >= 12) {
                $data['rows'][] = $column;

                $width = 0;
                $column = array();
            }
        }

        if (!empty($column)) {
            $data['rows'][] = $column;
        }*/

		return $this->template->view('Admin\Common\Views\dashboard',$data);
	}

    public function mdashboard()
    {
        $this->template->add_package(array('chartjs'),true);

        //echo getenv('CI_ENVIRONMENT');
        //exit;
        $settingModel=new SettingModel();
        $dashboards = array();
        $extensions = $settingModel->getDashboardReports(2);
        foreach ($extensions as $code) {
            if ($code->status) {
                /*$class = '\\Admin\\Report\\Controllers\\' . $code->name;
                $obj = new $class();
                $output=$obj->index();*/
                //$output=view_cell('Admin\Report\Controllers\\'.$code->name.'::index');


                $dashboards[] = array(
                    'code'       => $code->name,
                    'width'      => $code->col,
                    'sort_order' => $code->order,
                    'url'     => admin_url($code->name)
                );

            }
        }

        $sort_order = array();

        foreach ($dashboards as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $dashboards);

        // Split the array so the columns width is not more than 12 on each row.
        $width = 0;
        $column = array();
        $data['rows'] = array();

        foreach ($dashboards as $dashboard) {
            $column[] = $dashboard;

            $width = ($width + $dashboard['width']);

            if ($width >= 12) {
                $data['rows'][] = $column;

                $width = 0;
                $column = array();
            }
        }

        if (!empty($column)) {
            $data['rows'][] = $column;
        }


        /*$householdModel=new HouseholdModel();
        $data['household']=$householdModel->getTotal();

        $aggricultureModel=new AggricultureModel();
        $data['aggriculture']=$aggricultureModel->getTotal();

        $horticultureModel=new HorticultureModel();
        $data['horticulture']=$horticultureModel->getTotal();*/
        //printr($data);
        return $this->template->view('Admin\Common\Views\dashboard',$data);
    }

}

//return  __NAMESPACE__ ."\Header";
?>