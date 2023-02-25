<?php
namespace Admin\Report\Controllers;
use Admin\Report\Models\ReportModel;
use App\Controllers\AdminController;

class MActivity extends AdminController{
	private $error = array();

    function __construct(){
        $this->reportModel=new ReportModel();
    }

	public function index() {
        $this->template->add_package(array('chartjs'),true);
        $year = date("Y");
        $hh_current_year_count = $this->reportModel->get_yearly_count('hh_basic_details', $year);
        $agri_current_year_count = $this->reportModel->get_yearly_count('hh_agri_season', $year);
        $hort_current_year_count = $this->reportModel->get_yearly_count('hh_hort_season', $year);
        $live_current_year_count = $this->reportModel->get_yearly_count('livestock_basic', $year);
        $fish_current_year_count = $this->reportModel->get_yearly_count('hh_fisheries_basics', $year);
        $critirri_current_year_count = $this->reportModel->get_yearly_count('ci_farmer_data', $year);
        $data['current_year_count'] = "label : '$year',backgroundColor:'pink', borderColor:'red',borderWidth:1,data:[" . $hh_current_year_count['count'] . ", " . $agri_current_year_count['count'] . ", " . $hort_current_year_count['count'] . ", " . $live_current_year_count['count'] . ", " . $fish_current_year_count['count'] . ", " . $critirri_current_year_count['count'] . "]";

        $prev_year = $year - 1;
        $hh_prev_year_count = $this->reportModel->get_yearly_count('hh_basic_details', $prev_year);
        $agri_prev_year_count = $this->reportModel->get_yearly_count('hh_agri_season', $prev_year);
        $hort_prev_year_count = $this->reportModel->get_yearly_count('hh_hort_season', $prev_year);
        $live_prev_year_count = $this->reportModel->get_yearly_count('livestock_basic', $prev_year);
        $fish_prev_year_count = $this->reportModel->get_yearly_count('hh_fisheries_basics', $prev_year);
        $critirri_prev_year_count = $this->reportModel->get_yearly_count('ci_farmer_data', $prev_year);
        $data['prev_year_count'] = "label : '$prev_year',backgroundColor:'lightblue', borderColor:'blue',borderWidth:1,data:[" . $hh_prev_year_count['count'] . ", " . $agri_prev_year_count['count'] . ", " . $hort_prev_year_count['count'] . ", " . $live_prev_year_count['count'] . ", " . $fish_prev_year_count['count'] . ", " . $critirri_prev_year_count['count'] . "]";

        $prev_prev_year = $year - 2;
        $hh_prev_prev_year_count = $this->reportModel->get_yearly_count('hh_basic_details', $prev_prev_year);
        $agri_prev_prev_year_count = $this->reportModel->get_yearly_count('hh_agri_season', $prev_prev_year);
        $hort_prev_prev_year_count = $this->reportModel->get_yearly_count('hh_hort_season', $prev_prev_year);
        $live_prev_prev_year_count = $this->reportModel->get_yearly_count('livestock_basic', $prev_prev_year);
        $fish_prev_prev_year_count = $this->reportModel->get_yearly_count('hh_fisheries_basics', $prev_prev_year);
        $critirri_prev_prev_year_count = $this->reportModel->get_yearly_count('ci_farmer_data', $prev_prev_year);
        $data['prev_prev_year_count'] = "label : '$prev_prev_year',backgroundColor:'lightgreen', borderColor:'green',borderWidth:1,data:[" . $hh_prev_prev_year_count['count'] . ", " . $agri_prev_prev_year_count['count'] . ", " . $hort_prev_prev_year_count['count'] . ", " . $live_prev_prev_year_count['count'] . ", " . $fish_prev_prev_year_count['count'] . ", " . $critirri_prev_prev_year_count['count'] . "]";

        return $this->template->view('Admin\Report\Views\mactivityyear',$data,true);
	}

    public function mvcluster(){
        $data['cluster1_count'] = $this->reportModel->get_cluster_count('CLUSTER1');
        $data['cluster2_count'] = $this->reportModel->get_cluster_count('CLUSTER2');
        $data['cluster3_count'] = $this->reportModel->get_cluster_count('CLUSTER3');
        $data['cluster4_count'] = $this->reportModel->get_cluster_count('CLUSTER4');
        return $this->template->view('Admin\Report\Views\mvcluster',$data,true);
    }

    public function mactivitycount(){
        $data['hh_last_count'] = $this->reportModel->get_activity_count("hh_basic_details");

        $data['agri_last_count'] = $this->reportModel->get_activity_count("hh_agri_season");
        $data['hort_last_count'] = $this->reportModel->get_activity_count("hh_hort_season");
        $data['fish_last_count'] = $this->reportModel->get_activity_count("hh_fisheries_basics");
        $data['live_last_count'] = $this->reportModel->get_activity_count("livestock_basic");
        $data['criirri_last_count'] = $this->reportModel->get_activity_count("ci_farmer_data");
        //printr($data);
        return $this->template->view('Admin\Report\Views\mactivitycount',$data,true);
    }
}

return  __NAMESPACE__ ."\\MActivity";
