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

        if ($this->user->hasPermission('access', 'hod/index')) {
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

        if ($this->user->hasPermission('access', 'category/index')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_category'),
                'href'     => admin_url('category'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'shift/index')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_shift'),
                'href'     => admin_url('shift'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'grade/index')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_grade'),
                'href'     => admin_url('grade'),
                'children' => array()
            );
        }



        if ($this->user->hasPermission('access', 'designation/index')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_designation'),
                'href'     => admin_url('designation'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'location/index')) {
			$masters[] = array(
				'name'	  => lang('Leftbar.text_location'),
				'href'     => admin_url('location'),
				'children' => array()
			);
		}

		if ($this->user->hasPermission('access', 'workorder/index')) {
			$masters[] = array(
				'name'	  => lang('Leftbar.text_workorder'),
				'href'     => admin_url('workorder'),
				'children' => array()
			);
		}


        if ($this->user->hasPermission('access', 'bank/index')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_bank'),
                'href'     => admin_url('bank'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'reason/index')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_reason'),
                'href'     => admin_url('reason'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'machine/index')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_machine'),
                'href'     => admin_url('machine'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'employee/index')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_employee'),
                'href'     => admin_url('employee'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'leave/index')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_leave'),
                'href'     => admin_url('leave'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'leaveopening')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_leaveopening'),
                'href'     => admin_url('leaveopening'),
                'children' => array()
            );
        }
        if ($this->user->hasPermission('access', 'site/index')) {
            $masters[] = array(
                'name'	  => lang('Leftbar.text_site'),
                'href'     => admin_url('site'),
                'children' => array()
            );
        }
        if ($this->user->hasPermission('access', 'canteen/setup')) {
            $masters[] = array(
                'name'	  => 'Canteen Timing',
                'href'     => admin_url('canteen/setup'),
                'children' => array()
            );
        }
        if ($this->user->hasPermission('access', 'formula/field')) {
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

        if ($this->user->hasPermission('punch')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_mpunch'),
                'href'     => admin_url('punch'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'shift/range')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_shiftbyrange'),
                'href'     => admin_url('shift/range'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'shift/month')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_shiftbymonth'),
                'href'     => admin_url('shift/month'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'shift/dept')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_shiftbydept'),
                'href'     => admin_url('shift/dept'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'shift/csv')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_shiftbycsv'),
                'href'     => admin_url('shift/csv'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'cardreplacement/index')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_cardreplacement'),
                'href'     => admin_url('cardreplacement'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'leave/application')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_tourapplication'),
                'href'     => admin_url('leave/application'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'holiday/index')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_holiday'),
                'href'     => admin_url('holiday'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'leave/approval')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_leaveapproval'),
                'href'     => admin_url('leave/approval'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'leave/decline')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_leavedecline'),
                'href'     => admin_url('leave/decline'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'leave/encashment')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_leaveencashment'),
                'href'     => admin_url('leave/encashment'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'mispunch/request')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_mispunchrequest'),
                'href'     => admin_url('mispunch/request'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'mispunch/approval')) {
            $operations[] = array(
                'name'	  => lang('Leftbar.text_mispunchapproval'),
                'href'     => admin_url('mispunch/approval'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'otapproval/index')) {
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


        $dutyroster = array();

        if ($this->user->hasPermission('access', 'duty/creation')) {
            $dutyroster[] = array(
                'name'	  => lang('Leftbar.text_duty_creation'),
                'href'     => admin_url('duty/creation'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'duty/updation')) {
            $dutyroster[] = array(
                'name'	  => lang('Leftbar.text_duty_updation'),
                'href'     => admin_url('duty/updation'),
                'children' => array()
            );
        }



        if ($dutyroster) {
            $data['menus'][] = array(
                'id'       => 'menu-dutyroster',
                'icon'	   => 'mdi-folder-key-outline',
                'name'	   => lang('Leftbar.text_dutyroster'),
                'href'     => '',
                'children' => $dutyroster
            );
        }


        $payroll = array();

        if ($this->user->hasPermission('access', 'attendance')) {
            $payroll[] = array(
                'name'	  => 'Month Attendance',
                'href'     => admin_url('attendance'),
                'children' => array()
            );
        }
        if ($this->user->hasPermission('access', 'salary/field')) {
            $payroll[] = array(
                'name'	  => 'Salary Field',
                'href'     => admin_url('salary/field'),
                'children' => array()
            );
        }
        if ($this->user->hasPermission('access', 'salary/index')) {
            $payroll[] = array(
                'name'	  => 'Salary Setup',
                'href'     => admin_url('salary'),
                'children' => array()
            );
        }



        if ($this->user->hasPermission('access', 'salary/process')) {
            $payroll[] = array(
                'name'	  => 'Salary Payment Process',
                'href'     => admin_url('salary/process'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'advance/index')) {
            $payroll[] = array(
                'name'	  => 'Advance Master',
                'href'     => admin_url('advance'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'loan/index')) {
            $payroll[] = array(
                'name'	  => 'Loan',
                'href'     => admin_url('loan'),
                'children' => array()
            );
        }
        if ($this->user->hasPermission('access', 'formula/index')) {
            $payroll[] = array(
                'name'	  => 'Formula Setting',
                'href'     => admin_url('formula'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'tax/index')) {
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

        if ($this->user->hasPermission('access', 'canteen/index')) {
            $data['menus'][] = array(
                'id'       => 'menu-canteen',
                'icon'	   => 'mdi-food',
                'name'	   => lang('Leftbar.text_canteen'),
                'href'     => admin_url('canteen'),
                'children' => array()
            );
        }
        if ($this->user->hasPermission('access', 'goldrate/index')) {
            $data['menus'][] = array(
                'id'       => 'menu-goldrate',
                'icon'	   => 'mdi-bitcoin',
                'name'	   => 'Gold Rate',
                'href'     => admin_url('goldrate'),
                'children' => array()
            );
        }
        if ($this->user->hasPermission('access', 'training/index')) {
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

        if ($this->user->hasPermission('access', 'report/spot')) {
            $attendance[] = array(
                'name'	  => lang('Leftbar.text_spotreport'),
                'href'     => admin_url('report/spot'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'report/daily')) {
            $attendance[] = array(
                'name'	  => lang('Leftbar.text_dailyreport'),
                'href'     => admin_url('report/daily'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'report/month')) {
            $attendance[] = array(
                'name'	  => lang('Leftbar.text_monthlyreport'),
                'href'     => admin_url('report/month'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'report/master')) {
            $attendance[] = array(
                'name'	  => lang('Leftbar.text_masterreport'),
                'href'     => admin_url('report/master'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'report/year')) {
            $attendance[] = array(
                'name'	  => lang('Leftbar.text_yearreport'),
                'href'     => admin_url('report/year'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'report/chart')) {
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
        if ($this->user->hasPermission('access', 'report/attendancereport/index')) {
            $reports[] = array(
                'name'	  => 'Attendance Report',
                'href'     => admin_url('report/attendancereport'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'report/payrollreport/index')) {
            $reports[] = array(
                'name'	  => 'Payroll Report',
                'href'     => admin_url('report/payrollreport'),
                'children' => array()
            );
        }




        if ($this->user->hasPermission('access', 'report/canteenspot/index')) {
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

        if ($this->user->hasPermission('access', 'setting/index')) {
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

        if ($this->user->hasPermission('access', 'permission')) {
            $system[] = array(
                'name'	  => lang('Leftbar.text_permission'),
                'href'     => admin_url('permission'),
                'children' => array()
            );
        }

        // Localisation
        $localisation = array();

        if ($this->user->hasPermission('access', 'country/index')) {
            $localisation[] = array(
                'name'	   => lang('Leftbar.text_country'),
                'href'     	=> admin_url('country'),
                'children' 	=> array()
            );
        }

        if ($this->user->hasPermission('access', 'state/index')) {
            $localisation[] = array(
                'name'	   => lang('Leftbar.text_state'),
                'href'     	=> admin_url('state'),
                'children' 	=> array()
            );
        }

        if ($this->user->hasPermission('access', 'city/index')) {
            $localisation[] = array(
                'name'	  => lang('Leftbar.text_city'),
                'href'     => admin_url('city'),
                'children' => array()
            );
        }

        if ($this->user->hasPermission('access', 'language/index')) {
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


        if ($this->user->hasPermission('access', 'setting/serverinfo/index')) {
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
