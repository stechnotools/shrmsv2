<?php
namespace Admin\MisPunch\Controllers;

use Admin\Bank\Models\BankModel;
use Admin\Branch\Models\BranchModel;
use Admin\Category\Models\CategoryModel;
use Admin\Department\Models\DepartmentModel;
use Admin\Designation\Models\DesignationModel;
use Admin\Employee\Controllers\Employee;
use Admin\Employee\Models\EmployeeModel;
use Admin\Grade\Models\GradeModel;
use Admin\Hod\Models\HodModel;
use Admin\MainPunch\Models\MainPunchHistoryModel;
use Admin\MainPunch\Models\MainPunchModel;
use Admin\MainPunch\Models\MainRawPunchModel;
use Admin\MisPunch\Models\MisPunchHistoryModel;
use Admin\MisPunch\Models\MisPunchModel;
use Admin\Section\Controllers\Section;
use Admin\Section\Models\SectionModel;
use Admin\Shift\Controllers\Shift;
use Admin\Shift\Models\ShiftModel;
use App\Controllers\AdminController;
use DateTime;

class MisPunch extends AdminController {
	private $error = array();
	private $misPunchModel;
	private $employeeModel;
	public function __construct(){
        $this->misPunchModel=new MisPunchModel();
		$this->employeeModel=new EmployeeModel();
	}


	public function index(){
		$this->template->set_meta_title(lang('MisPunch.heading_title'));
		$this->template->add_package(array('datatable','select2','daterangepicker'),true);

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('MisPunch.heading_title'),
			'href' => admin_url('mispunch')
		);
		$data['heading_title'] = lang('MisPunch.heading_title');



		if($this->request->getGet('daterange')){
			$daterange=explode("-",(string)$this->request->getGet('daterange'));
			$data['fromdate'] = date("Y-m-d",strtotime($daterange[0]));
			$data['todate'] = date("Y-m-d",strtotime($daterange[1]));
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

		$data['mispunches']=$this->misPunchModel->getCLMAttendance($filter_data);

		$data['branches']=(new BranchModel())->getAll();
		$data['departments']=(new DepartmentModel())->getAll();
		$data['designations']=(new DesignationModel())->getAll();

		return $this->template->view('Admin\MisPunch\Views\misPunch', $data);

	}

	public function request(){
		$data=[];
		$this->template->add_package(array('datepicker','timepicker'),true);

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST'){

			$user_id=$this->request->getGet('user_id');
			$punchdate=$this->request->getGet('punch_date');
			$clm_in=$this->request->getPost('clm_in');
			$clm_out=$this->request->getPost('clm_out');
			$savior_in=$this->request->getPost('savior_in');
			$savior_out=$this->request->getPost('savior_out');
			$empdata=(new EmployeeModel())->getEmployee($user_id);
			$shiftdata=(new EmployeeModel())->getEmployeeShift($user_id);
			$timedata=(new EmployeeModel())->getEmployeeTime($user_id);
			

			$checkclm_in=(new MainRawPunchModel())->where('user_id',$user_id)
											->where('punch_date',$punchdate)
											->where('punch_time',$clm_in)
											->where('flag','IN')
											->get()->getRow();

			$mainrawpunchdata=[
				'user_id'=>$user_id,
				'branch_id'=>$empdata->branch_id,
				'safety_pass_no'=>$empdata->safety_pass_no,
				'punch_date'=>$punchdate,
				'shift'=>$empdata->shift_id,
				'department_id'=>$empdata->department_id,
				'location'=>'',
				'created_at'=>date('Y-m-d H:i:s'),
				'updated_at'=>date('Y-m-d H:i:s'),
			];
			if(!$checkclm_in){
				$mainrawpunchdata['punch_time']=$clm_in;
				$mainrawpunchdata['flag']='IN';
				$mainrawpunchdata['rstatus']=1;
				$this->misPunchModel->insert($mainrawpunchdata);

				//add main punch
				$mainpunchdata=[
					'user_id'=>$user_id,
					'employee_name'=>$empdata->employee_name,
					'paycode'=>$empdata->paycode,
					'card_no'=>$empdata->card_no,
					'branch_id'=>$empdata->branch_id,
					'branch_name'=>$empdata->branch_name,
					'department_id'=>$empdata->department_id,
					'department_name'=>$empdata->department_name,
					'category_id'=>$empdata->category_id,
					'category_name'=>$empdata->category_name,
					'section_id'=>$empdata->section_id,
					'section_name'=>$empdata->section_name,
					'grade_id'=>$empdata->grade_id,
					'grade_name'=>$empdata->grade_name,
					'designation_id'=>$empdata->designation_id,
					'designation_name'=>$empdata->designation_name,
					'shift_type'=>$shiftdata->shift_type,
					'shift_id'=>$empdata->shift_id,
					'shift_name'=>$shiftdata->shift_name,
					'shift_pattern'=>$shiftdata->shift_pattern,
					'shift_start_time'=>$shiftdata->shift_start_time,
					'shift_end_time'=>$shiftdata->shift_end_time,
					'auto_shift'=>$shiftdata->run_auto_shift,
					'first_week'=>$shiftdata->first_week,
					'second_week'=>$shiftdata->second_week,
					'late_arrival'=>$timedata->perm_late,
					'early_departure'=>$timedata->perm_early,
					'total_punch'=>$timedata->punches,
					'punch_date'=>$punchdate,
					'rstatus'=>1
				];
				$singledata =(new mainPunchModel())->where(['user_id' => $empdata->user_id, 'punch_date' => $punchdate, 'branch_id' => $empdata->branch_id])->first();
				if (empty($singledata)) {
					$punch_id=(new mainPunchModel())->skipValidation()->insert($mainpunchdata);
				}else{
					$punch_id=$singledata->punch_id;
				}
				if($punch_id){
					$punch_history=array(
						'punch_id'=>$punch_id,
						'user_id'=>$empdata->user_id,
						'card_no'=>$empdata->card_no,
						'paycode'=>$empdata->paycode,
						'punch_date'=>$punchdate,
						'punch_time'=>$clm_in,
						'punch_type'=>'M',
						'branch_id'=>$empdata->branch_id,
						'punch_status'=>1,
						'rstatus'=>1
					);
					$singlepdata = (new MainPunchHistoryModel())->where(['punch_id' => $punch_id, 'punch_date' => $punchdate, 'punch_time' => $clm_in, 'branch_id' => $empdata->branch_id])->first();
					if (empty($singlepdata)) {
						$punch_histrory_id=(new MainPunchHistoryModel())->insert($punch_history);
					}

				}

			}


			$checkclm_out=(new MainRawPunchModel())->where('user_id',$user_id)
											->where('punch_date',$punchdate)
											->where('punch_time',$clm_in)
											->where('flag','OUT')
											->get()->getRow();

			if(!$checkclm_out){
				$mainrawpunchdata['punch_time']=$clm_out;
				$mainrawpunchdata['flag']='OUT';
				$mainrawpunchdata['rstatus']=1;
				$this->misPunchModel->insert($mainrawpunchdata);

				//add main punch
				$mainpunchdata=[
					'user_id'=>$user_id,
					'employee_name'=>$empdata->employee_name,
					'paycode'=>$empdata->paycode,
					'card_no'=>$empdata->card_no,
					'branch_id'=>$empdata->branch_id,
					'branch_name'=>$empdata->branch_name,
					'department_id'=>$empdata->department_id,
					'department_name'=>$empdata->department_name,
					'category_id'=>$empdata->category_id,
					'category_name'=>$empdata->category_name,
					'section_id'=>$empdata->section_id,
					'section_name'=>$empdata->section_name,
					'grade_id'=>$empdata->grade_id,
					'grade_name'=>$empdata->grade_name,
					'designation_id'=>$empdata->designation_id,
					'designation_name'=>$empdata->designation_name,
					'shift_type'=>$shiftdata->shift_type,
					'shift_id'=>$empdata->shift_id,
					'shift_name'=>$shiftdata->shift_name,
					'shift_pattern'=>$shiftdata->shift_pattern,
					'shift_start_time'=>$shiftdata->shift_start_time,
					'shift_end_time'=>$shiftdata->shift_end_time,
					'auto_shift'=>$shiftdata->run_auto_shift,
					'first_week'=>$shiftdata->first_week,
					'second_week'=>$shiftdata->second_week,
					'late_arrival'=>$timedata->perm_late,
					'early_departure'=>$timedata->perm_early,
					'total_punch'=>$timedata->punches,
					'punch_date'=>$punchdate,
					'rstatus'=>1
				];
				$singledata =(new mainPunchModel())->where(['user_id' => $empdata->user_id, 'punch_date' => $punchdate, 'branch_id' => $empdata->branch_id])->first();
				if (empty($singledata)) {
					$punch_id=(new mainPunchModel())->skipValidation()->insert($mainpunchdata);
				}else{
					$punch_id=$singledata->punch_id;
				}
				if($punch_id){
					$punch_history=array(
						'punch_id'=>$punch_id,
						'user_id'=>$empdata->user_id,
						'card_no'=>$empdata->card_no,
						'paycode'=>$empdata->paycode,
						'punch_date'=>$punchdate,
						'punch_time'=>$clm_out,
						'punch_type'=>'M',
						'branch_id'=>$empdata->branch_id,
						'punch_status'=>1,
						'rstatus'=>1
					);
					$singlepdata = (new MainPunchHistoryModel())->where(['punch_id' => $punch_id, 'punch_date' => $punchdate, 'punch_time' => $clm_out, 'branch_id' => $empdata->branch_id])->first();
					if (empty($singlepdata)) {
						$punch_histrory_id=(new MainPunchHistoryModel())->insert($punch_history);
					}

				}

			}

			$checksavior_in=(new MainPunchModel())->where('user_id',$user_id)
											->where('punch_date',$punchdate)
											->where('punch_time',$clm_in)
											->where('flag','IN')
											->get()->getRow();

			$this->session->setFlashdata('message', 'Punch Request Saved Successfully.');


		}

		$user_id=$this->request->getGet('user_id');
		$punch_date=$data['punch_date']=$this->request->getGet('punch_date');
		$punchflter=[
			'user_id'=>$user_id,
			'fromdate'=>$punch_date,
			'todate'=>$punch_date
		];
		$punchdata=$this->misPunchModel->getCLMAttendance($punchflter);
		$user=(new EmployeeModel())->getEmployee($user_id);

		$shift=(new ShiftModel())->find($user->shift_id);

		$data['heading_title']="Mis Punch Request for ".$user->employee_name;
		$data['shift_id']=$user->shift_id;
		if($punchdata[0]['clm_in']=="00:00:00" || $punchdata[0]['clm_in']==""){
			$data['clm_in']=$shift->shift_start_time;
		}else{
			$data['clm_in']=$punchdata[0]['clm_in'];
		}
		if($punchdata[0]['clm_out']=="00:00:00" || $punchdata[0]['clm_out']==""){
			$data['clm_out']=$shift->shift_end_time;
		}else{
			$data['clm_out']=$punchdata[0]['clm_out'];
		}

		if ($punchdata[0]['savior_in'] == "00:00:00" || $punchdata[0]['savior_in'] == "") {
			// Assuming $shift->shift_start_time is in HH:MM:SS format
			$savior_in_timestamp = strtotime($shift->shift_start_time) + 15*60; // Adding 15 minutes
			$data['savior_in'] = date('H:i:s', $savior_in_timestamp);
		} else {
			$data['savior_in'] = $punchdata[0]['savior_in'];
		}

		if ($punchdata[0]['savior_out'] == "00:00:00" || $punchdata[0]['savior_out'] == "") {
			// Assuming $shift->shift_end_time is in HH:MM:SS format
			$savior_out_timestamp = strtotime($shift->shift_end_time) + 15*60; // Adding 15 minutes
			$data['savior_out'] = date('H:i:s', $savior_out_timestamp);
		} else {
			$data['savior_out'] = $punchdata[0]['savior_out'];
		}

		$data['shifts']=( new ShiftModel())->getAll();

		return  $this->template->view('Admin\MisPunch\Views\misPunchRequest',$data);
	}

	public function approve(){
		$this->template->set_meta_title(lang('MisPunch.heading_title'));
	}

	protected function getList() {

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('MisPunch.heading_title'),
			'href' => admin_url('mispunch')
		);

		$this->template->add_package(array('datatable','select2','daterangepicker'),true);


		$data['add'] = admin_url('mispunch/add');
		$data['delete'] = admin_url('mispunch/delete');
		$data['datatable_url'] = admin_url('mispunch/search');

		$data['heading_title'] = lang('MisPunch.heading_title');

		$data['text_list'] = lang('MisPunch.text_list');
		$data['text_no_results'] = lang('MisPunch.text_no_results');
		$data['text_confirm'] = lang('MisPunch.text_confirm');

		$data['column_punchname'] = lang('MisPunch.column_punchname');
		$data['column_status'] = lang('MisPunch.column_status');
		$data['column_date_added'] = lang('MisPunch.column_date_added');
		$data['column_action'] = lang('MisPunch.column_action');

		$data['button_add'] = lang('MisPunch.button_add');
		$data['button_edit'] = lang('MisPunch.button_edit');
		$data['button_delete'] = lang('MisPunch.button_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		$data['download_sample']=base_url('uploads/files/samples/clm-sample.xlsx');

		$data['branches']=(new BranchModel())->getAll();
		$data['departments']=(new DepartmentModel())->getAll();
		$data['designations']=(new DesignationModel())->getAll();


		return $this->template->view('Admin\MisPunch\Views\misPunch', $data);
	}

}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */