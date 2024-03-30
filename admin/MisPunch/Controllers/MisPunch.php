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
use Admin\Punch\Models\PunchHistoryModel;
use Admin\Punch\Models\PunchModel;
use Admin\Punch\Models\RawPunchModel;
use Admin\Reason\Models\ReasonModel;
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


	public function request(){
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

		return $this->template->view('Admin\MisPunch\Views\misPunchRequest', $data);

	}

	public function requestPop(){
		$data=[];
		$this->template->add_package(array('datepicker','timepicker'),true);

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST'){
			$punchrequest=$this->request->getPostGet();

			$punchrequest['punch_date']=date('Y-m-d',strtotime($punchrequest['punch_date']));
			$punchrequest['is_request']=0;

			$this->misPunchModel->insert($punchrequest);

			$this->session->setFlashdata('success','Request Submitted Successfully.');
			//json
			echo "1";
			exit;


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
			$data['clm_in_disable']=false;
		}else{
			$data['clm_in']=$punchdata[0]['clm_in'];
			$data['clm_in_disable']=true;
		}

		if($punchdata[0]['clm_out']=="00:00:00" || $punchdata[0]['clm_out']==""){
			$data['clm_out']=$shift->shift_end_time;
			$data['clm_out_disable']=false;
		}else{
			$data['clm_out']=$punchdata[0]['clm_out'];
			$data['clm_out_disable']=true;
		}

		if ($punchdata[0]['savior_in'] == "00:00:00" || $punchdata[0]['savior_in'] == "") {
			$savior_in_timestamp = strtotime($shift->shift_start_time) + 15*60; // Adding 15 minutes
			$data['savior_in'] = date('H:i:s', $savior_in_timestamp);
			$data['savior_in_disable']=false;
		} else {
			$data['savior_in'] = $punchdata[0]['savior_in'];
			$data['savior_in_disable']=true;
		}

		if ($punchdata[0]['savior_out'] == "00:00:00" || $punchdata[0]['savior_out'] == "") {
			$savior_out_timestamp = strtotime($shift->shift_end_time) + 15*60; // Adding 15 minutes
			$data['savior_out'] = date('H:i:s', $savior_out_timestamp);
			$data['savior_out_disable']=false;
		} else {
			$data['savior_out'] = $punchdata[0]['savior_out'];
			$data['savior_out_disable']=true;
		}

		$data['shifts']=( new ShiftModel())->getAll();
		$data['reasons']=(new ReasonModel())->getAll();

		return  $this->template->view('Admin\MisPunch\Views\misPunchRequestPop',$data);
	}

	public function approval(){
		$this->template->set_meta_title(lang('MisPunch.heading_title'));
		$this->template->add_package(array('datatable','select2','daterangepicker'),true);

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('MisPunch.heading_title'),
			'href' => admin_url('mispunch')
		);
		$data['heading_title'] = "MIS Punch Approval";


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

		if($this->request->getGet('status')){
            $data['status'] = $this->request->getGet('status');
        }else{
            $data['status'] = 0;
        }

        $filter_data=[
           	'branch_id'=>$data['branch_id'],
            'user_id'=>$data['user_id'],
			'status'=>$data['status']
        ];

		$data['mispunches']=$this->misPunchModel->getMisPunch($filter_data);

		$data['branches']=(new BranchModel())->getAll();

		return $this->template->view('Admin\MisPunch\Views\misPunchApproval', $data);

	}

	public function approvalPop(){
		$this->template->set_meta_title(lang('MisPunch.heading_title'));
		$mispinch_id=$this->uri->getSegment(4);

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST'){
			$punchrequest=$this->request->getPost();

			$mispunchdata=$this->misPunchModel->getMisPunch(['id'=>$mispinch_id])[0];

			$user_id=$mispunchdata['user_id'];
			$punchdate=$mispunchdata['punch_date'];
			$clm_in=$mispunchdata['clm_in'];
			$clm_out=$mispunchdata['clm_out'];
			$savior_in=$punchrequest['savior_in'];
			$savior_out=$punchrequest['savior_out'];
			$empdata=(new EmployeeModel())->getEmployee($user_id);
			$shiftdata=(new EmployeeModel())->getEmployeeShift($user_id);
			$timedata=(new EmployeeModel())->getEmployeeTime($user_id);

			$clmpunch=[
				[
					'time'=>$clm_in,
					'flag'=>'IN'
				],
				[
					'time'=>$clm_out,
					'flag'=>'OUT'
				]
			];
			foreach($clmpunch as $clmtime){
				$mainrawpunchdata=[
					'user_id'=>$user_id,
					'branch_id'=>$empdata->branch_id,
					'safety_pass_no'=>$empdata->safety_pass_no,
					'punch_date'=>$punchdate,
					'punch_time'=>$clmtime['time'],
					'flag'=>$clmtime['flag'],
					'shift'=>$empdata->shift_id,
					'department_id'=>$empdata->department_id,
					'location'=>'',
					'rstatus'=>$punchrequest['is_request']
				];

				$checkclm=(new MainRawPunchModel())->where('user_id',$user_id)
										->where('punch_date',$punchdate)
										->where('punch_time',$clmtime['time'])
										->get()->getRow();

				if(!$checkclm){
					$mainpunchdata_id=(new MainRawPunchModel())->insert($mainrawpunchdata);
				}else{
					$mainpunchdata_id=(new MainRawPunchModel())->update($checkclm->id,$mainrawpunchdata);
				}
			}

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
				'rstatus'=>$punchrequest['is_request']
			];
			$singledata =(new MainPunchModel())->where(['user_id' => $empdata->user_id, 'punch_date' => $punchdate, 'branch_id' => $empdata->branch_id])->first();
			if (empty($singledata)) {
				$punch_id=(new MainPunchModel())->skipValidation()->insert($mainpunchdata);
			}else{
				$mainpunchdata['updated_at']=date('Y-m-d H:i:s');
				$mainpunchdata['rstatus']=$punchrequest['is_request'];
				$punch_id=(new MainPunchModel())->skipValidation()->update($singledata->id,$mainpunchdata);
				$punch_id=$singledata->id;
			}
			foreach($clmpunch as $clmtime){
				$punch_history=array(
					'punch_id'=>$punch_id,
					'user_id'=>$empdata->user_id,
					'card_no'=>$empdata->card_no,
					'paycode'=>$empdata->paycode,
					'punch_date'=>$punchdate,
					'punch_time'=>$clmtime['time'],
					'punch_type'=>'M',
					'branch_id'=>$empdata->branch_id,
					'punch_status'=>1,
					'rstatus'=>$punchrequest['is_request']
				);
				$singlepdata = (new MainPunchHistoryModel())->where(['punch_id' => $punch_id, 'punch_date' => $punchdate, 'punch_time' => $clmtime['time'], 'branch_id' => $empdata->branch_id])->first();
				if (empty($singlepdata)) {
					$punch_histrory_id=(new MainPunchHistoryModel())->insert($punch_history);
				}else{
					$punch_histrory_id=(new MainPunchHistoryModel())->update($singlepdata->id,$punch_history);
				}
			}



			//savior punch
			$saviortime=[$savior_in,$savior_out];
			foreach($saviortime as $savior){
				$punchdatetime=$punchdate.' '.$savior_in;

				$saviorrawpunch=array(
					'device_user_id'=>$empdata->card_no,
					'punchtime'=>$punchdatetime,
					'branch_id'=>$empdata->branch_id
				);
				$checksavior=(new RawPunchModel())->where($saviorrawpunch)->first();
				$saviorrawpunch['rstatus']=$punchrequest['is_request'];
				if(!$checksavior){
					$savior_punch_id=(new RawPunchModel())->insert($saviorrawpunch);
				}else{
					$savior_punch_id=(new RawPunchModel())->update($checksavior->id,$saviorrawpunch);
				}
			}

			$punch_data=array(
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
				'shift_type'=>$empdata->shift_type,
				'shift_id'=>$shiftdata->shift_id,
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
				'status'=>1,
				'rstatus'=>$punchrequest['is_request']
			);
			$singledata =(new PunchModel())->where(['user_id' => $empdata->user_id, 'punch_date' => $punchdate, 'branch_id' => $empdata->branch_id])->first();
			if (empty($singledata)) {
				$punch_id=(new PunchModel())->skipValidation()->insert($punch_data);
			}else{
				$punch_id=(new PunchModel())->skipValidation()->update($singledata->id,$punch_data);
				$punch_id=$singledata->id;
			}

			foreach($saviortime as $punch_time){

				$punch_history=array(
					'punch_id'=>$punch_id,
					'user_id'=>$empdata->user_id,
					'card_no'=>$empdata->card_no,
					'paycode'=>$empdata->paycode,
					'punch_date'=>$punchdate,
					'punch_time'=>$punch_time,
					'punch_type'=>"M",
					'branch_id'=>$empdata->branch_id,
					'punch_status'=>1,
					'rstatus'=>$punchrequest['is_request']
				);

				$singlepdata = (new PunchHistoryModel())->where(['punch_id' => $punch_id, 'punch_date' => $punchdate, 'punch_time' => $savior, 'branch_id' => $empdata->branch_id])->first();
				if (empty($singlepdata)) {
					$punch_histrory_id=(new PunchHistoryModel())->insert($punch_history);
				}else{
					//update
					$punch_histrory_id=(new PunchHistoryModel())->update($singlepdata->id,$punch_history);
				}
			}

			//update punch request
			$this->misPunchModel->update($mispinch_id,['is_request'=>$punchrequest['is_request']]);

			$this->session->setFlashdata('success','Action Submitted Successfully.');
			//json
			echo "1";
			exit;


		}

		$mispunchdata=$this->misPunchModel->find($mispinch_id);

		if($mispunchdata){

			$user=(new EmployeeModel())->getEmployee($mispunchdata->user_id);
			$shift=(new ShiftModel())->find($mispunchdata->shift_id);

			$data['heading_title']="Mis Punch Approve for ".$user->employee_name;
			$data['employee_name']=$user->employee_name;
			$data['punch_date']=$mispunchdata->punch_date;
			$data['shift_id']=$user->shift_id;
			$data['clm_in']=$mispunchdata->clm_in;
			$data['clm_out']=$mispunchdata->clm_out;
			$data['savior_in']=$mispunchdata->savior_in;
			$data['savior_out']=$mispunchdata->savior_out;
			$data['remarks']=$mispunchdata->remarks;
			$data['reason_id']=$mispunchdata->reason_id;
			$data['is_request']=$mispunchdata->is_request;
			$data['action_remarks']=$mispunchdata->action_remarks;
			$data['action_by']=$mispunchdata->action_by;

			$data['shifts']=( new ShiftModel())->getAll();
			$data['reasons']=(new ReasonModel())->getAll();

		}else{
			$data['heading_title']="Mis Punch Request";
		}


		return  $this->template->view('Admin\MisPunch\Views\misPunchApprovalPop',$data);

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