<?php
namespace Admin\Common\Controllers;
use App\Controllers\AdminController;

class Leftbar extends AdminController{

	public function __construct()
	{
		$this->user=service('user');
	}
	public function index()
	{

		$data=array();

        $data['menus'][] = array(
            'id'       => 'menu-dashboard',
            'icon'	  => 'mdi-home',
            'name'	  => lang('Leftbar.text_dashboard'),
            'href'     => admin_url('/'),
            'children' => array()
        );


        // Pages
        $masters = array();

        if ($this->user->hasPermission('branch')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_branch'),
                'href'     => admin_url('branch'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('hod')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_hod'),
                'href'     => admin_url('hod'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('department')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_department'),
                'href'     => admin_url('department'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('section')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_section'),
                'href'     => admin_url('section'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('category')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_category'),
                'href'     => admin_url('category'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('shift')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_shift'),
                'href'     => admin_url('shift'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('grade')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_grade'),
                'href'     => admin_url('grade'),
                'children' => array()
            );
        }



        if ($this->user->hasPermission('designation')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_designation'),
                'href'     => admin_url('designation'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('location')) {
			$masters[] = array(
				'name'	  => lang('Leftbar.text_location'),
				'href'     => admin_url('location'),
				'children' => array()
			);
		}

		if ($this->user->hasPermission('workorder')) {
			$masters[] = array(
				'name'	  => lang('Leftbar.text_workorder'),
				'href'     => admin_url('workorder'),
				'children' => array()
			);
		}


        if ($this->user->hasPermission('bank')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_bank'),
                'href'     => admin_url('bank'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('reason')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_reason'),
                'href'     => admin_url('reason'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('machine')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_machine'),
                'href'     => admin_url('machine'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('employee')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_employee'),
                'href'     => admin_url('employee'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('leave')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_leave'),
                'href'     => admin_url('leave'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('leaveopening')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_leaveopening'),
                'href'     => admin_url('leaveopening'),
                'children' => array()
            );
        }
        if ($this->user->hasPermission('site')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_site'),
                'href'     => admin_url('site'),
                'children' => array()
            );
        }
        if ($this->user->hasPermission('canteen/setup')) {
            $masters[] = array(
                'name'	  => 'Canteen Timing',
                'href'     => admin_url('canteen/setup'),
                'children' => array()
            );
        }
        if ($this->user->hasPermission('formula/field')) {
            $masters[] = array(
                'name'	  => 'Formula Field',
                'href'     => admin_url('formula/field'),
                'children' => array()
            );
        }


        if ($masters) {
            $data['menus'][] = array(
                'id'       => 'menu-masters',
                'icon'	   => 'mdi-warehouse ',
                'name'	   => lang('Leftbar.text_master'),
                'href'     => '',
                'children' => $masters
            );
        }


        // Pages
        $operations = array();

        if ($this->user->hasPermission('mainpunch')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_mainpunch'),
                'href'     => admin_url('mainpunch'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('punch')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_punch'),
                'href'     => admin_url('punch'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('shift/override')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_override'),
                'href'     => admin_url('shift/override'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('shift/roster')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_shiftbyroster'),
                'href'     => admin_url('shift/roster'),
                'children' => array()
            );
        }



        if ($this->user->hasPermission('leaveapplication')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_tourapplication'),
                'href'     => admin_url('leaveapplication'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('holiday')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_holiday'),
                'href'     => admin_url('holiday'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('leave/approval')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_leaveapproval'),
                'href'     => admin_url('leave/approval'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('leave/decline')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_leavedecline'),
                'href'     => admin_url('leave/decline'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('leave/encashment')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_leaveencashment'),
                'href'     => admin_url('leave/encashment'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('mispunch/request')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_mispunchrequest'),
                'href'     => admin_url('mispunch/request'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('mispunch/approval')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_mispunchapproval'),
                'href'     => admin_url('mispunch/approval'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('otapproval')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_otapproval'),
                'href'     => admin_url('otapproval'),
                'children' => array()
            );
        }

        if ($operations) {
            $data['menus'][] = array(
                'id'       => 'menu-operations',
                'icon'	   => 'mdi-language-swift ',
                'name'	   => lang('Leftbar.text_operation'),
                'href'     => '',
                'children' => $operations
            );
        }


        $punchroster = array();

        if ($this->user->hasPermission('attendance')) {
            $punchroster[] = array(
                'name'	  => lang('Leftbar.text_attendance'),
                'href'     => admin_url('attendance'),
                'children' => array()
            );
        }


        if ($punchroster) {
            $data['menus'][] = array(
                'id'       => 'menu-punchroster',
                'icon'	   => 'mdi-folder-key-outline',
                'name'	   => lang('Leftbar.text_punchroster'),
                'href'     => '',
                'children' => $punchroster
            );
        }


        $payroll = array();


        if ($this->user->hasPermission('salary/field')) {
            $payroll[] = array(
                'name'	  => 'Salary Field',
                'href'     => admin_url('salary/field'),
                'children' => array()
            );
        }
        if ($this->user->hasPermission('salary')) {
            $payroll[] = array(
                'name'	  => 'Salary Setup',
                'href'     => admin_url('salary'),
                'children' => array()
            );
        }



        if ($this->user->hasPermission('salary/process')) {
            $payroll[] = array(
                'name'	  => 'Salary Payment Process',
                'href'     => admin_url('salary/process'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('advance')) {
            $payroll[] = array(
                'name'	  => 'Advance Master',
                'href'     => admin_url('advance'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('loan')) {
            $payroll[] = array(
                'name'	  => 'Loan',
                'href'     => admin_url('loan'),
                'children' => array()
            );
        }
        if ($this->user->hasPermission('formula')) {
            $payroll[] = array(
                'name'	  => 'Formula Setting',
                'href'     => admin_url('formula'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('tax')) {
            $payroll[] = array(
                'name'	  => 'Tax class Setting',
                'href'     => admin_url('tax'),
                'children' => array()
            );
        }

        if ($payroll) {
            $data['menus'][] = array(
                'id'       => 'menu-payroll',
                'icon'	   => 'mdi-account-cash',
                'name'	   => lang('Leftbar.text_payroll'),
                'href'     => '',
                'children' => $payroll
            );
        }

        if ($this->user->hasPermission('canteen')) {
            $data['menus'][] = array(
                'id'       => 'menu-canteen',
                'icon'	   => 'mdi-food',
                'name'	   => lang('Leftbar.text_canteen'),
                'href'     => admin_url('canteen'),
                'children' => array()
            );
        }
        if ($this->user->hasPermission('goldrate')) {
            $data['menus'][] = array(
                'id'       => 'menu-goldrate',
                'icon'	   => 'mdi-bitcoin',
                'name'	   => 'Gold Rate',
                'href'     => admin_url('goldrate'),
                'children' => array()
            );
        }
        if ($this->user->hasPermission('training')) {
            $data['menus'][] = array(
                'id'       => 'menu-training',
                'icon'	   => 'mdi-google-podcast ',
                'name'	   => lang('Leftbar.text_training'),
                'href'     => admin_url('training'),
                'children' => array()
            );
        }

        $reports = array();

        // Attendance
        /*$attendance = array();

        if ($this->user->hasPermission('report/spot')) {
            $attendance[] = array(
                'name'	  => lang('Leftbar.text_spotreport'),
                'href'     => admin_url('report/spot'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('report/daily')) {
            $attendance[] = array(
                'name'	  => lang('Leftbar.text_dailyreport'),
                'href'     => admin_url('report/daily'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('report/month')) {
            $attendance[] = array(
                'name'	  => lang('Leftbar.text_monthlyreport'),
                'href'     => admin_url('report/month'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('report/master')) {
            $attendance[] = array(
                'name'	  => lang('Leftbar.text_masterreport'),
                'href'     => admin_url('report/master'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('report/year')) {
            $attendance[] = array(
                'name'	  => lang('Leftbar.text_yearreport'),
                'href'     => admin_url('report/year'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('report/chart')) {
            $attendance[] = array(
                'name'	  => lang('Leftbar.text_chartreport'),
                'href'     => admin_url('report/chart'),
                'children' => array()
            );
        }

        if ($attendance) {
            $reports[] = array(
                'name'	   => 'Attendance Report',
                'href'     => '',
                'children' => $attendance
            );
        }
        */
        if ($this->user->hasPermission('report/attendance')) {
            $reports[] = array(
                'name'	  => 'Attendance Report',
                'href'     => admin_url('report/attendance'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('report/payroll')) {
            $reports[] = array(
                'name'	  => 'Payroll Report',
                'href'     => admin_url('report/payroll'),
                'children' => array()
            );
        }




        if ($this->user->hasPermission('report/canteen')) {
            $reports[] = array(
                'name'	  => lang('Leftbar.text_canteenreport'),
                'href'     => admin_url('report/canteenspot'),
                'children' => array()
            );
        }




        if ($reports) {
            $data['menus'][] = array(
                'id'       => 'menu-reports',
                'icon'	   => 'mdi-file-chart-outline ',
                'name'	   => lang('Leftbar.text_reports'),
                'href'     => '',
                'children' => $reports
            );
        }


        // System
        $system = array();

        if ($this->user->hasPermission('setting')) {
            $system[] = array(
                'name'	  => lang('Leftbar.text_setting'),
                'href'     => admin_url('setting'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('users')) {
            $system[] = array(
                'name'	  => lang('Leftbar.text_users'),
                'href'     => admin_url('users'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('roles')) {
            $system[] = array(
                'name'	  => lang('Leftbar.text_usergroup'),
                'href'     => admin_url('roles'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('permission')) {
            $system[] = array(
                'name'	  => lang('Leftbar.text_permission'),
                'href'     => admin_url('permission'),
                'children' => array()
            );
        }

        // Localisation
        $localisation = array();

        if ($this->user->hasPermission('country')) {
            $localisation[] = array(
                'name'	   => lang('Leftbar.text_country'),
                'href'     	=> admin_url('country'),
                'children' 	=> array()
            );
        }

        if ($this->user->hasPermission('state')) {
            $localisation[] = array(
                'name'	   => lang('Leftbar.text_state'),
                'href'     	=> admin_url('state'),
                'children' 	=> array()
            );
        }

        if ($this->user->hasPermission('city')) {
            $localisation[] = array(
                'name'	  => lang('Leftbar.text_city'),
                'href'     => admin_url('city'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('language')) {
            $localisation[] = array(
                'name'	   => lang('Leftbar.text_language'),
                'href'     	=> admin_url('language'),
                'children' 	=> array()
            );
        }

        if ($localisation) {
            $system[] = array(
                'name'	   => lang('Leftbar.text_localisation'),
                'href'     => '',
                'children' => $localisation
            );
        }


        if ($this->user->hasPermission('setting/serverinfo')) {
            $system[] = array(
                'name'	  => lang('Leftbar.text_serverinfo'),
                'href'     => admin_url('setting/serverinfo'),
                'children' => array()
            );
        }


        if ($system) {
            $data['menus'][] = array(
                'id'       => 'menu-system',
                'icon'	   => 'mdi-settings',
                'name'	   => lang('Leftbar.text_system'),
                'href'     => '',
                'children' => $system
            );
        }
		return view('Admin\Common\Views\leftbar',$data);
	}
}

/* End of file templates.php */
/* Location: ./application/modules/templates/controllers/templates.php */
