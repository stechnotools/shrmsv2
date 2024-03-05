<?php
namespace Admin\Report\Controllers;

use Admin\Branch\Models\BranchModel;
use Admin\Employee\Models\EmployeeModel;
use Admin\Report\Models\AttendanceReportModel;
use Admin\Shift\Models\ShiftModel;
use App\Controllers\AdminController;
use DateTime;

class Attendance extends AdminController{
	private $error = array();
    private $attendanceReportModel;
    function __construct(){
        $this->attendanceReportModel=new AttendanceReportModel();
    }

	public function index() {
        $data['title']="Attendance";
        return $this->template->view('Admin\Report\Views\attendance',$data);
	}

    public function earlyarrival(){
        $this->template->add_package(array('datatable','datepicker','select2','daterangepicker'),true);
        $data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Attendance.heading_title'),
			'href' => admin_url('report/attendance')
		);
        $data['heading_title'] = lang('Attendance.heading_title');

        if($this->request->getGet('fromdate')){
            $data['fromdate'] = $data['todate'] = $this->request->getGet('fromdate');
        }else{
            $data['fromdate'] = $data['todate'] = date('Y-m-d');
        }

        if($this->request->getGet('branch_id')){
            $data['branch_id'] = $this->request->getGet('branch_id');
        }else{
            $data['branch_id'] = '';
        }

        if($this->request->getGet('user_id')){
            $data['user_id'] = $this->request->getGet('user_id');
        }else{
            $data['user_id'] = '';
        }

        $filter_data=[
            'fromdate'=>$data['fromdate'],
            'todate'=>$data['todate'],
            'branch_id'=>$data['branch_id'],
            'user_id'=>$data['user_id']
        ];


		$attendancedata=$this->attendanceReportModel->getMonthAttendance($filter_data);

        $attendances=$this->getResultData($attendancedata);
        $data['earlyarrival']=[];
        foreach($attendances as $key=>$attendance){
			foreach($attendance['dates'] as $earlydata){
				$early_arrival='';
				if (!empty($earlydata['startin']) && strtotime($earlydata['startin']) < strtotime($earlydata['start'])) {
					$datetime1 = date_create($earlydata['startin']);
					$datetime2 = date_create($earlydata['start']);
					$interval = date_diff($datetime1, $datetime2);
					$early_arrival=$interval->format('%H:%I:%S');
					//$late_arrrival=date('H:i:s',(strtotime($result->intime)-strtotime($shift_start_time)));
				}
				$one=$thirty=$ten="";
				if(strtotime($early_arrival)>strtotime("00:01:00")){
					$one="**" ;
				}else if(strtotime($early_arrival)>strtotime("00:00:30")){
					$thirty="**";
				}else if(strtotime($early_arrival)>strtotime("00:00:10")){
					$ten="**";
				}
				$data['earlyarrival'][]=array(
					'slno'=>$key+1,
					'paycode'=>$earlydata['paycode'],
					'card_no'=>$earlydata['card_no'],
					'employee_name'=>$earlydata['employee_name'],
					'shift'=>$earlydata['shift_name'],
					'start'=>$earlydata['start'],
					'startin'=>$earlydata['startin'],
					'early_arrival'=>$early_arrival,
					'one'=>$one,
					'ten'=>$ten,
					'thirty'=>$thirty
				);
			}
		}

        $data['branches'] = (new BranchModel())->getAll();

       // $data['employees'] = (new EmployeeModel())->getAll();

        return $this->template->view('Admin\Report\Views\earlyarrival', $data);
    }

    public function latearrival(){
        $this->template->add_package(array('datatable','datepicker','select2','daterangepicker'),true);
        $data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Attendance.heading_title'),
			'href' => admin_url('report/attendance')
		);
        $data['heading_title'] = lang('Attendance.heading_title');

        if($this->request->getGet('fromdate')){
            $data['fromdate'] = $data['todate'] = $this->request->getGet('fromdate');
        }else{
            $data['fromdate'] = $data['todate'] = date('Y-m-d');
        }

        if($this->request->getGet('branch_id')){
            $data['branch_id'] = $this->request->getGet('branch_id');
        }else{
            $data['branch_id'] = '';
        }

        if($this->request->getGet('user_id')){
            $data['user_id'] = $this->request->getGet('user_id');
        }else{
            $data['user_id'] = '';
        }

        $filter_data=[
            'fromdate'=>$data['fromdate'],
            'todate'=>$data['todate'],
            'branch_id'=>$data['branch_id'],
            'user_id'=>$data['user_id']
        ];


		$attendancedata=$this->attendanceReportModel->getMonthAttendance($filter_data);
        $attendances=$this->getResultData($attendancedata);
        $data['latearrival']=[];
        foreach($attendances as $key=>$attendance){
			foreach($attendance['dates'] as $employee){
				$shift_late='';
				if(strtotime($employee['startin']) > strtotime($employee['start']) ){
					$datetime1 = date_create($employee['startin']);
					$datetime2 = date_create($employee['start']);
					$interval = date_diff($datetime1, $datetime2);
					$shift_late=$interval->format('%H:%I:%S');
					//$late_arrrival=date('H:i:s',(strtotime($result->intime)-strtotime($shift_start_time)));
				}
				$one=$thirty=$ten="";
				if(strtotime($shift_late)>strtotime("00:01:00")){
					$one="**" ;
				}else if(strtotime($shift_late)>strtotime("00:00:30")){
					$thirty="**";
				}else if(strtotime($shift_late)>strtotime("00:00:10")){
					$ten="**";
				}
				$data['latearrival'][]=array(
					'slno'=>$key+1,
					'paycode'=>$employee['paycode'],
					'card_no'=>$employee['card_no'],
					'employee_name'=>$employee['employee_name'],
					'shift'=>$employee['shift_name'],
					'start'=>$employee['start'],
					'startin'=>$employee['startin'],
					'shift_late'=>$shift_late,
					'one'=>$one,
					'ten'=>$ten,
					'thirty'=>$thirty
				);
			}
		}

        $data['branches'] = (new BranchModel())->getAll();

       // $data['employees'] = (new EmployeeModel())->getAll();

        return $this->template->view('Admin\Report\Views\latearrival', $data);
    }

    public function absenteesism(){
		$this->template->add_package(array('datatable','datepicker','select2','daterangepicker'),true);
        $data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Attendance.heading_title'),
			'href' => admin_url('report/attendance')
		);
        $data['heading_title'] = lang('Attendance.heading_title');

        if($this->request->getGet('fromdate')){
            $data['fromdate'] = $data['todate'] = $this->request->getGet('fromdate');
        }else{
            $data['fromdate'] = $data['todate'] = date('Y-m-d');
        }

        if($this->request->getGet('branch_id')){
            $data['branch_id'] = $this->request->getGet('branch_id');
        }else{
            $data['branch_id'] = '';
        }

        if($this->request->getGet('user_id')){
            $data['user_id'] = $this->request->getGet('user_id');
        }else{
            $data['user_id'] = '';
        }

        $filter_data=[
            'fromdate'=>$data['fromdate'],
            'todate'=>$data['todate'],
            'branch_id'=>$data['branch_id'],
            'user_id'=>$data['user_id'],
            'status'=>0
        ];
        //printr($spotdata);
        $data['absentdata']=array();
        $attendancedata=$this->attendanceReportModel->getMonthAttendance($filter_data);
        $attendances=$this->getResultData($attendancedata);

        foreach($attendances as $key=>$attendance){
			foreach($attendance['dates'] as $employee){
                $shift_late='';
				if(strtotime($employee['startin']) > strtotime($employee['start']) ){
					$datetime1 = date_create($employee['startin']);
					$datetime2 = date_create($employee['start']);
					$interval = date_diff($datetime1, $datetime2);
					$shift_late=$interval->format('%H:%I:%S');
				}
                $data['absentdata'][]=array(
                    'slno'=>$key+1,
                    'paycode'=>$employee['paycode'],
                    'card_no'=>$employee['card_no'],
                    'employee_name'=>$employee['employee_name'],
                    'designation_name'=>$employee['designation_name'],
                    'shift'=>$employee['shift'],
                    'start'=>$employee['start'],
                    'startin'=>$employee['startin'],
                    'shift_late'=>$shift_late,
                    'status'=>$employee['status']
                );

            }
        }


        $data['branches'] = (new BranchModel())->getAll();

        return $this->template->view('Admin\Report\Views\absenteesism', $data);
	}

    public function dattendance(){
		$this->template->add_package(array('datatable','datepicker','select2','daterangepicker'),true);
        $data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Attendance.heading_title'),
			'href' => admin_url('report/attendance')
		);
        $data['heading_title'] = lang('Attendance.heading_title');

        if($this->request->getGet('fromdate')){
            $data['fromdate'] = $data['todate'] = $this->request->getGet('fromdate');
        }else{
            $data['fromdate'] = $data['todate'] = date('Y-m-d');
        }

        if($this->request->getGet('branch_id')){
            $data['branch_id'] = $this->request->getGet('branch_id');
        }else{
            $data['branch_id'] = '';
        }

        if($this->request->getGet('user_id')){
            $data['user_id'] = $this->request->getGet('user_id');
        }else{
            $data['user_id'] = '';
        }

        $filter_data=[
            'fromdate'=>$data['fromdate'],
            'todate'=>$data['todate'],
            'branch_id'=>$data['branch_id'],
            'user_id'=>$data['user_id']
        ];

        $data['dattendance']=array();

		$monthattendancedata=$this->attendanceReportModel->getMonthAttendance($filter_data);
		$attendancedata=$this->getResultData($monthattendancedata);

        foreach($attendancedata as $key=>$attendance){
            foreach($attendance['dates'] as $employee){
                $late_arrrival='';
                $employee['late_arrival']=$employee['late_arrival']?:"00:00:00";
                $early_arival_format=date("i", strtotime($employee['late_arrival'])).' minutes';
                $shift_start_time=date("H:i:s", strtotime($early_arival_format, strtotime($employee['start'])));
                //echo $shift_start_time."<br>";
                if(strtotime($shift_start_time) < strtotime($employee['startin']) ){
                    $datetime1 = date_create($employee['startin']);
                    $datetime2 = date_create($employee['start']);
                    $interval = date_diff($datetime1, $datetime2);
                    $late_arrrival=$interval->format('%H:%I:%S');
                    //$late_arrrival=date('H:i:s',(strtotime($result->intime)-strtotime($shift_start_time)));
                }
                $data['dattendance'][]=array(
                    'slno'=>$key+1,
                    'paycode'=>$employee['paycode'],
                    'card_no'=>$employee['card_no'],
                    'employee_name'=>$employee['employee_name'],
                    'designation_name'=>$employee['designation_name'],
                    'shift'=>$employee['shift'],
                    'start'=>$employee['start'],
                    'startin'=>$employee['startin'],
                    'shift_late'=>$late_arrrival,
                    'status'=>$employee['astatus']
                );
            }
        }


		$data['branches'] = (new BranchModel())->getAll();

        return $this->template->view('Admin\Report\Views\dattendance', $data);

	}

    public function dperformance(){
		$this->template->add_package(array('datatable','datepicker','select2','daterangepicker'),true);
        $data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Attendance.heading_title'),
			'href' => admin_url('report/attendance')
		);
        $data['heading_title'] = lang('Attendance.heading_title');

        if($this->request->getGet('fromdate')){
            $data['fromdate'] = $data['todate'] = $this->request->getGet('fromdate');
        }else{
            $data['fromdate'] = $data['todate'] = date('Y-m-d');
        }

        if($this->request->getGet('branch_id')){
            $data['branch_id'] = $this->request->getGet('branch_id');
        }else{
            $data['branch_id'] = '';
        }

        if($this->request->getGet('user_id')){
            $data['user_id'] = $this->request->getGet('user_id');
        }else{
            $data['user_id'] = '';
        }

        $filter_data=[
            'fromdate'=>$data['fromdate'],
            'todate'=>$data['todate'],
            'branch_id'=>$data['branch_id'],
            'user_id'=>$data['user_id']
        ];

        $data['dperformance']=array();
		$monthattendancedata=$this->attendanceReportModel->getMonthAttendance($filter_data);
		$dailydata=$this->getResultData($monthattendancedata);
		foreach($dailydata as $key=>$spotdate){
			foreach($spotdate['dates'] as $spot){
				$data['dperformance'][]=array(
					'slno'=>$key+1,
					'paycode'=>$spot['paycode'],
					'card_no'=>$spot['card_no'],
					'employee_name'=>$spot['employee_name'],
					'department_name'=>$spot['department_name'],
					'designation_name'=>$spot['designation_name'],
					'shift'=>$spot['shift'],
					'start'=>$spot['start'],
					'startin'=>$spot['startin'],
					'lunch_out'=>$spot['lunch_out'],
					'lunch_in'=>$spot['lunch_in'],
					'out'=>$spot['startout'],
					'worked_hr'=>$spot['work'],
					'status'=>$spot['status'],
					'early_arrival'=>$spot['early_arrival'],
					'late_arrival'=>$spot['late_arrival'],
					'early_departure'=>$spot['early_departure'],
					'late_departure'=>$spot['late_departure'],
					'excess_lunch'=>$spot['excess_lunch'],
				);
			}
		}
		$data['branches'] = (new BranchModel())->getAll();

        return $this->template->view('Admin\Report\Views\dperformance', $data);
	}
    public function mperformance(){
		$this->template->add_package(array('datatable','datepicker','select2','daterangepicker'),true);
        $data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Attendance.heading_title'),
			'href' => admin_url('report/attendance')
		);
        $data['heading_title'] = lang('Attendance.heading_title');



        if($this->request->getGet('daterange')){
            $datarange=$this->request->getGet('daterange');
            $daterange=explode("-",(string)$datarange);
			$data['fromdate']=date('Y-m-d', strtotime(trim($daterange[0])));
			$data['todate']=date('Y-m-d', strtotime(trim($daterange[1])));
		}else{
			$data['fromdate']=date('Y-m-d', strtotime('-7 days'));
			$data['todate']=date('Y-m-d');
		}

        if($this->request->getGet('branch_id')){
            $data['branch_id'] = $this->request->getGet('branch_id');
        }else{
            $data['branch_id'] = '';
        }

        if($this->request->getGet('user_id')){
            $data['user_id'] = $this->request->getGet('user_id');
        }else{
            $data['user_id'] = '';
        }

        $filter_data=[
            'fromdate'=>$data['fromdate'],
            'todate'=>$data['todate'],
            'branch_id'=>$data['branch_id'],
            'user_id'=>$data['user_id']
        ];


		//printr($spotdata);
        $data['mperformance']=array();
		$data['months']=getMonthDays($filter_data);
		$monthattendancedata=$this->attendanceReportModel->getMonthAttendance($filter_data);

		$monthdata=$this->getResultData($monthattendancedata);

		$data['mperformance']=$monthdata;

		$data['download_excel_url'] = admin_url('report/attendance/mperformance?download=xls');
        if($this->request->getGet('download')=="xls"){
			//$this->createExcel($data,'soereport');
		}
        $data['branches'] = (new BranchModel())->getAll();

        return $this->template->view('Admin\Report\Views\mperformance', $data);

	}

    public function musterroll(){
		$this->template->add_package(array('datatable','datepicker','select2','daterangepicker'),true);
        $data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Attendance.heading_title'),
			'href' => admin_url('report/attendance')
		);
        $data['heading_title'] = lang('Attendance.heading_title');


        if($this->request->getGet('daterange')){
            $datarange=$this->request->getGet('daterange');
            $daterange=explode("-",(string)$datarange);
			$data['fromdate']=date('Y-m-d', strtotime(trim($daterange[0])));
			$data['todate']=date('Y-m-d', strtotime(trim($daterange[1])));
		}else{
			$data['fromdate']=date('Y-m-d', strtotime('-7 days'));
			$data['todate']=date('Y-m-d');
		}

        if($this->request->getGet('branch_id')){
            $data['branch_id'] = $this->request->getGet('branch_id');
        }else{
            $data['branch_id'] = '';
        }

        if($this->request->getGet('user_id')){
            $data['user_id'] = $this->request->getGet('user_id');
        }else{
            $data['user_id'] = '';
        }

        $filter_data=[
            'fromdate'=>$data['fromdate'],
            'todate'=>$data['todate'],
            'branch_id'=>$data['branch_id'],
            'user_id'=>$data['user_id']
        ];
        //printr($spotdata);
        $data['musterroll']=array();
        $data['months']=$months=getMonthDays($filter_data);
		$sundayCount = $satardayCount= 0;
		foreach ($months as $date) {
			if (date('N', strtotime($date['date'])) == 7) {
				$sundayCount++;
			}
			if (date('N', strtotime($date['date'])) == 6) {
				$satardayCount++;
			}
		}
		$data['total_sun']=$sundayCount;
		$data['total_sat']=$satardayCount;
		$monthattendancedata=$this->attendanceReportModel->getMonthAttendance($filter_data);
		$monthdata=$this->getResultData($monthattendancedata);
		$data['musterroll']=$monthdata;
        $data['branches'] = (new BranchModel())->getAll();

        return $this->template->view('Admin\Report\Views\musterroll', $data);

	}

    public function mearlyarrival(){
        $this->template->add_package(array('datatable','datepicker','select2','daterangepicker'),true);
        $data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Attendance.heading_title'),
			'href' => admin_url('report/attendance')
		);
        $data['heading_title'] = lang('Attendance.heading_title');
        if($this->request->getGet('daterange')){
            $datarange=$this->request->getGet('daterange');
            $daterange=explode("-",(string)$datarange);
			$data['fromdate']=date('Y-m-d', strtotime(trim($daterange[0])));
			$data['todate']=date('Y-m-d', strtotime(trim($daterange[1])));
		}else{
			$data['fromdate']=date('Y-m-d', strtotime('-7 days'));
			$data['todate']=date('Y-m-d');
		}

        if($this->request->getGet('branch_id')){
            $data['branch_id'] = $this->request->getGet('branch_id');
        }else{
            $data['branch_id'] = '';
        }

        if($this->request->getGet('user_id')){
            $data['user_id'] = $this->request->getGet('user_id');
        }else{
            $data['user_id'] = '';
        }

        $filter_data=[
            'fromdate'=>$data['fromdate'],
            'todate'=>$data['todate'],
            'branch_id'=>$data['branch_id'],
            'user_id'=>$data['user_id']
        ];

		$data['mearlydata']=array();
		$data['months']=$months=getMonthDays($filter_data);
		$monthattendancedata=$this->attendanceReportModel->getMonthAttendance($filter_data);
		$monthdata=$this->getResultData($monthattendancedata);
        $data['mearlydata']=$monthdata;
        $data['branches'] = (new BranchModel())->getAll();

        return $this->template->view('Admin\Report\Views\mearlyarrival', $data);

    }

    public function mlatearrival(){
        $this->template->add_package(array('datatable','datepicker','select2','daterangepicker'),true);
        $data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Attendance.heading_title'),
			'href' => admin_url('report/attendance')
		);
        $data['heading_title'] = lang('Attendance.heading_title');
        if($this->request->getGet('daterange')){
            $datarange=$this->request->getGet('daterange');
            $daterange=explode("-",(string)$datarange);
			$data['fromdate']=date('Y-m-d', strtotime(trim($daterange[0])));
			$data['todate']=date('Y-m-d', strtotime(trim($daterange[1])));
		}else{
			$data['fromdate']=date('Y-m-d', strtotime('-7 days'));
			$data['todate']=date('Y-m-d');
		}

        if($this->request->getGet('branch_id')){
            $data['branch_id'] = $this->request->getGet('branch_id');
        }else{
            $data['branch_id'] = '';
        }

        if($this->request->getGet('user_id')){
            $data['user_id'] = $this->request->getGet('user_id');
        }else{
            $data['user_id'] = '';
        }

        $filter_data=[
            'fromdate'=>$data['fromdate'],
            'todate'=>$data['todate'],
            'branch_id'=>$data['branch_id'],
            'user_id'=>$data['user_id']
        ];

		$data['mearlydata']=array();
		$data['months']=$months=getMonthDays($filter_data);
		$monthattendancedata=$this->attendanceReportModel->getMonthAttendance($filter_data);
		$monthdata=$this->getResultData($monthattendancedata);
        $data['mlatedata']=$monthdata;
        $data['branches'] = (new BranchModel())->getAll();

        return $this->template->view('Admin\Report\Views\mlatearrival', $data);

    }

    public function roster(){
		$this->template->add_package(array('datatable','datepicker','select2'),true);
        $data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Attendance.heading_title'),
			'href' => admin_url('report/attendance')
		);
        $data['heading_title'] = lang('Attendance.heading_title');
        if($this->request->getGet('month')){
            $month=$data['month']=$this->request->getGet('month');
            $date = DateTime::createFromFormat('m/Y', (string)$month);
			// Get the start of the month
			$startOfMonth = clone $date;
			$startOfMonth->modify('first day of this month');
			// Get the end of the month
			$endOfMonth = clone $date;
			$endOfMonth->modify('last day of this month');
			// Calculate the start and end dates of the month
			$data['fromdate'] = $startOfMonth->format('Y-m-d');
			$data['todate'] = $endOfMonth->format('Y-m-d');
		}else{
			$data['fromdate']=date('Y-m-d', strtotime('-7 days'));
			$data['todate']=date('Y-m-d');
            $data['month']='';
		}


        if($this->request->getGet('branch_id')){
            $data['branch_id'] = $this->request->getGet('branch_id');
        }else{
            $data['branch_id'] = '';
        }

        if($this->request->getGet('user_id')){
            $data['user_id'] = $this->request->getGet('user_id');
        }else{
            $data['user_id'] = '';
        }
		//printr($data);

        $filter_data=[
            'fromdate'=>$data['fromdate'],
            'todate'=>$data['todate'],
            'branch_id'=>$data['branch_id'],
            'user_id'=>$data['user_id']
        ];

        $data['rosterdata']=array();
        $data['months']=$months=getMonthDays($filter_data);
		$monthattendancedata=$this->attendanceReportModel->getMonthAttendance($filter_data);
		//printr($monthattendancedata);
		//exit;
		$monthdata=$this->getResultData($monthattendancedata);
		$data['rosterdata']=$monthdata;
		$data['shifts']=(new ShiftModel())->getAll();
        $data['branches'] = (new BranchModel())->getAll();

        return $this->template->view('Admin\Report\Views\roster', $data);

	}

	public function clmattendance(){
		$this->template->add_package(array('datatable','datepicker','select2','daterangepicker'),true);
        $data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Attendance.heading_title'),
			'href' => admin_url('report/attendance')
		);
        $data['heading_title'] = lang('Attendance.heading_title');

        if($this->request->getGet('fromdate')){
            $data['fromdate'] = $data['todate'] = $this->request->getGet('fromdate');
        }else{
            $data['fromdate'] = $data['todate'] = date('Y-m-d');
        }

        if($this->request->getGet('branch_id')){
            $data['branch_id'] = $this->request->getGet('branch_id');
        }else{
            $data['branch_id'] = '';
        }

        if($this->request->getGet('user_id')){
            $data['user_id'] = $this->request->getGet('user_id');
        }else{
            $data['user_id'] = '';
        }

        $filter_data=[
            'fromdate'=>$data['fromdate'],
            'todate'=>$data['todate'],
            'branch_id'=>$data['branch_id'],
            'user_id'=>$data['user_id']
        ];

        $data['clmattendance']=array();
		$dailydata=$this->attendanceReportModel->getCLMAttendance($filter_data);
		foreach($dailydata as $key=>$spot){
			$clmstarttime = date_create($spot['clm_in']);
			$clmendtime = date_create($spot['clm_out']);
			$clminterval = date_diff($clmstarttime, $clmendtime);
			$clm_whr=$clminterval->format('%H:%I:%S');

			$saviorstarttime = date_create($spot['savior_in']);
			$saviorendtime = date_create($spot['savior_out']);
			$saviorinterval = date_diff($saviorstarttime, $saviorendtime);
			$savior_whr=$saviorinterval->format('%H:%I:%S');

			$data['clmattendance'][]=array(
				'slno'=>$key+1,
				'card_no'=>$spot['card_no'],
				'safety_pass_no'=>$spot['safety_pass_no'],
				'employee_name'=>$spot['employee_name'],
				'department_name'=>$spot['department_name'],
				'designation_name'=>$spot['designation_name'],
				'branch_name'=>$spot['branch_name'],
				'clm_in'=>$spot['clm_in'],
				'clm_out'=>$spot['clm_out'],
				'clm_working_hr'=>$clm_whr,
				'savior_in'=>$spot['savior_in'],
				'savior_out'=>$spot['savior_out'],
				'savior_working_hr'=>$savior_whr,
				'status'=>$spot['clm_in']?'P':'A',
			);

		}
		$data['branches'] = (new BranchModel())->getAll();

		return $this->template->view('Admin\Report\Views\clmattendance', $data);
	}


    public function getResultData($attendancedata){
        $resultData = [];
        foreach ($attendancedata as $employee) {
            $userId = $employee['user_id'];
            $punchDate = $employee['punch_date'];

            if (!isset($resultData[$userId])) {
                $resultData[$userId] = [
                    'details' => [
                        'user_id' => $userId,
                        'paycode' => $employee['paycode'],
                        'card_no' => $employee['card_no'],
                        'employee_name' => $employee['employee_name'],
                        'designation_name' => $employee['designation_name'],
                        'department_name' => $employee['department_name'],
                        'shift_name' => $employee['shift_name'],
                        'present' => 0,
                        'absent' => 0,
                        'miss' => 0,
                        'holiday' => 0,
                        'weekly_off' => 0,
                        'leave' => 0,
                        'worked_hr' => '',
                        'overtime' => '',
                        'ot_amount' => '',
                    ],
                    'dates' => [],
                ];
            }

            $resultData[$userId]['dates'][$punchDate] = $employee;
        }

        foreach ($resultData as &$employee) {
            $present=$weekly_off=0;
			$total_present=$total_absent=$total_hlf=$total_mis=0;
			$total_sunpresent=$total_sunhlf=0;
			$total_satpresent=$total_sathlf=0;
			$total_morning=$total_lunch=0;
			$total_morning_day=$total_lunch_day=0;

            foreach ($employee['dates'] as $date => &$daily) {
                $lunch_out=$lunch_in=$excess_lunch=$less_lunch=$startout="";
				$less_lunch_day=$early_arrival_day=0;

				$early_arrival=$late_arrival=$early_departure=$late_departure='00:00:00';
				$early_departure_format=date("-i", strtotime($daily['early_departure'])).' minutes';
				$shift_end_time=date("H:i:s", strtotime($early_departure_format, strtotime($daily['end'])));

                // Simplify status determination
                $status = ($daily['no_of_punch'] > 0) ? 'P' : 'A';
                $daily['astatus'] = $status;



                if($daily['punches']==-1){
					$startout=$daily['startout'];

				}else if($daily['punches']==2){
					$startout=$daily['startout'];
					if($daily['no_of_punch']==0 || $daily['no_of_punch']==""){
						$status="A";
					}else if($daily['no_of_punch']==1){
						$status="MIS";
					}else if($daily['no_of_punch']>=2){
						$status="P";
					}
				}else if($daily['punches']==4){
					if($daily['no_of_punch']==2){
						$startout=$daily['two_out'];
						$status="P";
					}else if($daily['no_of_punch']==4) {
						$startout=$daily['four_out'];
						$status="P";
					}else if($daily['no_of_punch']>4) {
						$startout=$daily['startout'];
						$status="P";
					}else if($daily['no_of_punch']==0 || $daily['no_of_punch']==""){
						$status="A";
					}else{
						$status="MIS";
					}
					if($daily['no_of_punch']>2 ){
						$lunch_out=$daily['lunch_out'];
						$lunch_in=$daily['lunch_in'];
						$lunch_duration=$daily['lunch_duration'];
						$lunch_in_unix = strtotime($lunch_in);
						$lunch_out_unix = strtotime($lunch_out);
						$lunch_duration_seconds = strtotime($lunch_duration) - strtotime('00:00:00'); // Convert to seconds
						$actual_lunch_duration_seconds = $lunch_in_unix - $lunch_out_unix;
						$allowed_lunch_duration_seconds = $lunch_duration_seconds;
						$less_lunch=$excess_lunch="00:00:00";
						if ($actual_lunch_duration_seconds > $allowed_lunch_duration_seconds) {
							$excess_lunch_seconds = $actual_lunch_duration_seconds - $allowed_lunch_duration_seconds;
							$excess_lunch = gmdate("H:i:s", $excess_lunch_seconds); // Format as time string
						} elseif ($actual_lunch_duration_seconds < $allowed_lunch_duration_seconds) {
							$less_lunch_seconds = $allowed_lunch_duration_seconds - $actual_lunch_duration_seconds;
							$less_lunch = gmdate("H:i:s", $less_lunch_seconds); // Format as time string
							$less_lunch_day=1;
						}
					}
				}
				$lastpunch=$daily['startout'];
				$shift=$daily['shift_name'];
				if($daily['first_week']!="none"){
					if($daily['first_week']==strtolower(date("D",strtotime($daily['punch_date'])))){
						$shift="OFF";
					}
				}else{
					$shift=$daily['shift_name'];
				}
				//shift override
				/*if($daily['shift_override']){
					$shift=$daily['shift_override'];
				}*/
				$early_arival_format=date("+i", strtotime($daily['late_arrival'])).' minutes';
				$shift_start_time=date("H:i:s", strtotime($early_arival_format, strtotime($daily['start'])));
				if( strtotime($shift_start_time) > strtotime($daily['startin']) && $status!="A" ){
					$datetime1 = date_create($daily['startin']);
					$datetime2 = date_create($daily['start']);
					$interval = date_diff($datetime1, $datetime2);
					$early_arrival=$interval->format('%H:%I:%S');
					$early_arrival_day=1;
				}else if(strtotime($shift_start_time) < strtotime($daily['startin']) && $status!="A" ){
					$datetime1 = date_create($daily['startin']);
					$datetime2 = date_create($daily['start']);
					$interval = date_diff($datetime1, $datetime2);
					$late_arrival=$interval->format('%H:%I:%S');
				}
				//$early_departure_format=date("-i", strtotime($daily['early_departure'])).' minutes';
				//$shift_end_time=date("H:i:s", strtotime($early_departure_format, strtotime($daily['end'])));
				if (!empty($daily['startout']) && strtotime($shift_end_time) > strtotime($daily['startout']) && $status != "A") {
					$datetime1 = date_create($daily['startout']);
					$datetime2 = date_create($daily['end']);
					$interval = date_diff($datetime1, $datetime2);
					$early_departure=$interval->format('%H:%I:%S');
				}else if(!empty($daily['startout']) && strtotime($shift_end_time) < strtotime($daily['startout']) && $status!="A" ){
					$datetime1 = date_create($daily['startout']);
					$datetime2 = date_create($daily['end']);
					$interval = date_diff($datetime1, $datetime2);
					$late_departure=$interval->format('%H:%I:%S');
				}
				if($lastpunch){
					$starttime = date_create($daily['startin']);
					$endtime = date_create($lastpunch);
					$interval = date_diff($starttime, $endtime);
					$whr=$interval->format('%H:%I:%S');
					if($daily['lunch_deduction']){
						$datetime3 = date_create($daily['lunch_deduction']);
						$datetime4 = date_create($whr);
						$interval1 = date_diff($datetime3, $datetime4);
						$whr=$interval1->format('%H:%I:%S');
					}
					if($daily['min_absent_hrs_halfday'] && (strtotime($daily['min_absent_hrs_halfday'])>strtotime($whr))){
						$status="HLF";
					}
				}else{
					$whr="";
				}
				if($daily['first_week'] !="none" && ($daily['first_week']==strtolower(date("D",strtotime($daily['punch_date']))))){
					if($status !="A"){
						$status="POW";
					}else{
						$status="WO";
					}
					//echo $status;
				}
				$daily['status']=$status;
				$dayname= date('D', strtotime($date));
				if($status=="P"){
					$total_present++;
				}
				if($status=="A"){
					$total_absent++;
				}
				if($status=="HLF"){
					$total_hlf++;
				}
				if($status=="MIS"){
					$total_mis++;
				}
				if($dayname=="Sun" && $status=="P"){
					$total_sunpresent++;
				}
				if($dayname=="Sun" && $status=="HLF"){
					$total_sunhlf++;
				}
				if($dayname=="Sat" && $status=="P"){
					$total_satpresent++;
				}
				if($dayname=="Sat" && $status=="HLF"){
					$total_sathlf++;
				}
				if($status=="WO"){
					$weekly_off++;
				}
				if($daily['startin']){
					$present++;
				}
				$total_morning+=strtotime($early_arrival);
				$total_lunch+=strtotime($less_lunch);
				$total_morning_day+=$early_arrival_day;
				$total_lunch_day+=$less_lunch_day;
				$daily['in1']=$daily['startin'];
				$daily['lunch_out']=$lunch_out;
				$daily['lunch_in']=$lunch_in;
				$daily['out1']=$daily['four_out'];
				$daily['status']=$status;
				$daily['out']=$startout;
				$daily['work']=$whr;
				$daily['overtime']='';
				$daily['shift']=$shift;
				$daily['early_arrival']=$early_arrival;
				$daily['late_arrival']=$late_arrival;
				$daily['early_departure']=$early_departure;
				$daily['late_departure']=$late_departure;
				$daily['excess_lunch']=$excess_lunch;
				$daily['less_lunch']=$less_lunch;


            }

            if($total_hlf){
				$total_present=$total_present+($total_hlf/2);
			}
			if($total_hlf){
				$total_absent=$total_absent+($total_hlf/2);
			}
			if($total_sunhlf){
				$total_sunpresent=$total_sunpresent+($total_sunhlf/2);
			}
			if($total_sathlf){
				$total_satpresent=$total_satpresent+($total_sathlf/2);
			}
			$employee['details']['total_present']=$total_present;
			$employee['details']['total_absent']=$total_absent;
			$employee['details']['total_mis']=$total_mis;
			$employee['details']['total_sunpresent']=$total_sunpresent;
			$employee['details']['total_satpresent']=$total_satpresent;
			$employee['details']['present']=$present;
			$employee['details']['weekly_off']=$weekly_off;
			$employee['details']['total_morning']=date("H:i:s", $total_morning);
			$employee['details']['total_lunch']=date("H:i:s", $total_lunch);
			$employee['details']['total_morning_day']=$total_morning_day;
			$employee['details']['total_lunch_day']=$total_lunch_day;

            // Update other details here...
        }

        return $resultData;

    }




}

return  __NAMESPACE__ ."\\Attendance";
