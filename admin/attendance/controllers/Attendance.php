<?php
namespace Admin\Attendance\Controllers;

use Admin\Attendance\Models\AttendanceModel;
use Admin\Branch\Models\BranchModel;
use Admin\Department\Models\DepartmentModel;
use Admin\Designation\Models\DesignationModel;
use Admin\Employee\Models\EmployeeModel;
use Admin\Site\Models\SiteModel;
use App\Controllers\AdminController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
class Attendance extends AdminController {
	private $error = array();
	private $attendanceModel;
	private $employeeModel;
	private $branchModel;
	public function __construct(){
		$this->attendanceModel= new AttendanceModel();
		$this->employeeModel= new EmployeeModel();
		$this->branchModel = new BranchModel();
	}
	public function index(){
		$this->template->set_meta_title(lang('Attendance.heading_title'));
        return $this->getList();
	}
	protected function search() {
		$requestData= $_REQUEST;
		$totalData = $this->attendanceModel->getTotalAttendances();
		$totalFiltered = $totalData;
		$filter_data = array(
			'filter_search'  	=> $requestData['search']['value'],
			'branch_id' 			=> $requestData['branch_id'],
			'department_id' 		=> $requestData['department_id'],
			'designation_id' 		=> $requestData['designation_id'],
			'month' 			=> $requestData['month'],
			'order'  		 	=> $requestData['order'][0]['dir'],
			'sort' 			 	=> $requestData['order'][0]['column'],
			'start' 		 	=> $requestData['start'],
			'limit' 		 	=> $requestData['length']
		);
		$totalFiltered = $this->attendanceModel->getTotalAttendances($filter_data);
		$filteredData = $this->attendanceModel->getAttendances($filter_data);
		//printr($filteredData);
		$datatable=array();
		foreach($filteredData as $result) {
			if($result->id){
				$url=admin_url('attendance/edit/'.$result->user_id .'/'.$requestData['month'].'/'.$result->id );
				$urltxt="Edit Attendance";
			}else{
				$url=admin_url('attendance/add/'.$result->user_id .'/'.$requestData['month']);
				$urltxt="Add Attendance";
			}
			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.$url.'">'.$urltxt.'</a>';
			//$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('salary/attendance/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';
			$action .= '</div>';
			$datatable[]=array(
				'<input type="checkbox" name="selected[]" value="'.$result->id.'" />',
				$result->employee_name,
				$result->paycode,
				$result->month_days,
				$result->absent_days,
				$result->status?'Uploaded':'Not Uploaded',
				$action
			);
		}
		//printr($datatable);
		$json_data = array(
			"draw"            => isset($requestData['draw']) ? intval( $requestData['draw'] ):1,
			"recordsTotal"    => intval( $totalData ),
			"recordsFiltered" => intval( $totalFiltered ),
			"data"            => $datatable
		);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($json_data));  // send data as json format
	}
	public function add(){

		$this->template->set_meta_title(lang('Attendance.heading_title'));
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){
			$var = $_POST['month_days'].'/'.$_POST['date_added'];
			$date = str_replace('/', '-', $var);
			$_POST['date_added']=date("Y-m-d", strtotime($date));
			$userid=$this->attendanceModel->addAttendance($this->request->getPost());
			$this->session->set_flashdata('message', 'attendance Saved Successfully.');
			redirect(ADMIN_PATH.'/attendance');
		}
		$this->getForm();
	}
	public function edit(){

		$this->template->set_meta_title(lang('Attendance.heading_title'));
		if ($this->request->getMethod('REQUEST_METHOD') === 'POST'){
			$attendance_id=$this->uri->segment(7);
			$var = $_POST['month_days'].'/'.$_POST['date_added'];
			$date = str_replace('/', '-', $var);
			$_POST['date_added']=date("Y-m-d", strtotime($date));
			$this->attendanceModel->editAttendance($attendance_id,$this->request->getPost());
			$this->session->set_flashdata('message', 'attendance Updated Successfully.');
			redirect(ADMIN_PATH.'/attendance');
		}
		$this->getForm();
	}
	public function delete(){
		if ($this->request->getPost('selected')){
         $selected = $this->request->getPost('selected');
      }else{
         $selected = (array) $this->uri->segment(5);
       }
		$this->attendanceModel->deleteAttendance($selected);
		$this->session->set_flashdata('message', 'attendance deleted Successfully.');
		redirect(ADMIN_PATH.'/attendance');
	}
	protected function getList() {
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Attendance.heading_title'),
			'href' => admin_url('attendance')
		);
		$this->template->add_package(array('datepicker','datatable','select2'),true);
		$data['add'] = admin_url('attendance/add');
		$data['delete'] = admin_url('attendance/delete');
		$data['datatable_url'] = admin_url('attendance/search');
		$data['attendance_sample']=base_url('storage/uploads/files/attendance-sample.xlsx');
		$data['heading_title'] = lang('Attendance.heading_title');
		$data['text_list'] = lang('Attendance.text_list');
		$data['text_no_results'] = lang('Attendance.text_no_results');
		$data['text_confirm'] = lang('Attendance.text_confirm');
		$data['button_add'] = lang('Attendance.button_add');
		$data['button_edit'] = lang('Attendance.button_edit');
		$data['button_delete'] = lang('Attendance.button_delete');
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}
		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		$data['month'] = date('m/Y');

		$data['branches']=$this->branchModel->getAll();

		$data['departments']=(new DepartmentModel())->getAll();

		$data['designations']=(new DesignationModel())->getAll();

		$data['sites']=(new SiteModel())->getAll();

		return $this->template->view('Admin\Attendance\Views\attendances', $data);


	}
	public function attendanceList(){
		$requestData= $_REQUEST;
		$totalData = $this->attendanceModel->getTotalAttendances();
		$totalFiltered = $totalData;
		$filter_data = array(
			'filter_search'  => $requestData['search']['value'],
			'branch_id' => $requestData['branch_id'],
			'department_id' => $requestData['department_id'],
			'designation_id' => $requestData['designation_id'],
			'month' => $requestData['month'],
			'order'  		 => $requestData['order'][0]['dir'],
			'sort' 			 => $requestData['order'][0]['column'],
			'start' 		 => $requestData['start'],
			'limit' 		 => $requestData['length']
		);
		$totalFiltered = $this->attendanceModel->getTotalAttendances($filter_data);
		$filteredData = $this->attendanceModel->getAttendances($filter_data);
		//printr($filteredData);
		$datatable=array();
		foreach($filteredData as $result) {

			$action  = '<div class="btn-group btn-group-sm pull-right">';
			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('attendance/edit/'.$result->id.'?id='.$result->user_id).'"><i class="fa fa-pencil"></i></a>';
			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('attendance/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';
			$action .= '</div>';
			$datatable[]=array(
				'<input type="checkbox" name="selected[]" value="'.$result->id.'" />',
				$result->employee_name,
				$result->paycode,
				$result->date_added,
				$result->present_days,
				$result->pwo,
				$action
			);
		}
		//printr($datatable);
		$json_data = array(
			"draw"            => isset($requestData['draw']) ? intval( $requestData['draw'] ):1,
			"recordsTotal"    => intval( $totalData ),
			"recordsFiltered" => intval( $totalFiltered ),
			"data"            => $datatable
		);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($json_data));
	}
	protected function getForm(){
		$data = array();
		$data =
		$this->template->add_package(array('ckeditor','ckfinder','colorbox','select2','datepicker','datatable'),true);
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Attendance.heading_title'),
			'href' => admin_url('attendance')
		);
		/*$url=[];
		if($this->request->get('id')){
			$url['id']=$this->request->get('id');
		}
		if($this->request->get('month')){
			$url['month']=$this->request->get('month');
		}*/
		$month=$this->uri->segment(5).'/'.$this->uri->segment(6);

		//printr($_SESSION);
		$_SESSION['isLoggedIn'] = true;
		$data['heading_title'] 	= lang('Attendance.heading_title');
		$data['text_form'] = $this->uri->segment(7) ? "attendance Edit" : "attendance Add";
		$data['text_image'] =lang('Attendance.text_image');
		$data['text_none'] = lang('Attendance.text_none');
		$data['text_clear'] = lang('Attendance.text_clear');
		$data['cancel'] = admin_url('attendance');
		$data['button_save'] = lang('Attendance.button_save');
		$data['button_cancel'] = lang('Attendance.button_cancel');
		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}
		if ($this->uri->segment(7) && ($this->request->getMethod('REQUEST_METHOD') != 'POST')) {
			$attendance = $this->attendanceModel->getAttendance($this->uri->segment(7));
			$data['attendance_id']=$this->uri->segment(7);
			$data['edit']=true;
		}else{
			$data['edit']=false;
			$data['attendance_id']=0;
		}
		if($this->uri->segment(4)){
			$empoffice_info = $this->employee_model->getEmployeeOffice($this->uri->segment(4));
		}else{
			$empoffice_info=[];
		}

		$this->load->model('site/site_model');
		$data['sites']=$this->site_model->getSites();

		//branch

		//printr($data['shifts']);

		foreach($this->attendanceModel->getTableColumns() as $field) {
			if($this->request->getPost($field)) {
				$data[$field] = $this->request->getPost($field);
			} else if(isset($attendance->{$field}) && $attendance->{$field}) {
				$data[$field] = $attendance->{$field};
			} else {
				$data[$field] = '';
			}
		}

		if($month){
			$data['date_added']=$month;
			$data['month_days']=date("t", strtotime("01/".$month));
		}else{
			$data['date_added']="";
			$data['month_days']="";
		}


		if ($this->request->getPost('site_attendance')) {
			$site_attendances = $this->request->getPost('site_attendance');
		} elseif ($this->uri->segment(7)) {
			$site_attendances = $this->attendanceModel->getSiteAttendances($this->uri->segment(7));
		} else {
			$site_attendances = array();
		}
		//printr($site_attendances);
		$data['site_attendances'] = array();

		foreach ($site_attendances as $site_attendance) {
			$data['site_attendances'][] = array(
				"site_id"=>$site_attendance['site_id'],
				"present_days"=>$site_attendance['present_days'],
				"pwo"=>$site_attendance['pwo'],
				"arrear_days"=>$site_attendance['arrear_days'],
				"deduction_days"=>$site_attendance['deduction_days'],
				"ot"=>$site_attendance['ot']

			);
		}

		if (!empty($empoffice_info)) {
			$data['user_id'] = $empoffice_info->user_id;
		} else {
			$data['user_id'] = '';
		}

		if (!empty($empoffice_info)) {
			$data['branch_id'] = $empoffice_info->branch_id;
		} else {
			$data['branch_id'] = 0;
		}

		if ($this->request->getPost('paycode')) {
			$data['paycode'] = $this->request->getPost('paycode');
		} elseif (!empty($empoffice_info)) {
			$data['paycode'] = $empoffice_info->paycode;
		} else {
			$data['paycode'] = '';
		}

		if ($this->request->getPost('employee_name')) {
			$data['employee_name'] = $this->request->getPost('employee_name');
		} elseif (!empty($empoffice_info)) {
			$data['employee_name'] = $empoffice_info->employee_name;
		} else {
			$data['employee_name'] = '';
		}

		if ($this->request->getPost('card_no')) {
			$data['card_no'] = $this->request->getPost('card_no');
		} elseif (!empty($empoffice_info)) {
			$data['card_no'] = $empoffice_info->card_no;
		} else {
			$data['card_no'] = '';
		}

		if ($this->request->getPost('branch_name')) {
			$data['branch_name'] = $this->request->getPost('branch_name');
		} elseif (!empty($empoffice_info)) {
			$data['branch_name'] = $empoffice_info->branch_name;
		} else {
			$data['branch_name'] = '';
		}

		if ($this->request->getPost('department_name')) {
			$data['department_name'] = $this->request->getPost('department_name');
		} elseif (!empty($empoffice_info)) {
			$data['department_name'] = $empoffice_info->department_name;
		} else {
			$data['department_name'] = '';
		}

		if ($this->request->getPost('designation_name')) {
			$data['designation_name'] = $this->request->getPost('designation_name');
		} elseif (!empty($empoffice_info)) {
			$data['designation_name'] = $empoffice_info->designation_name;
		} else {
			$data['designation_name'] = '';
		}

		/*if ($this->request->getPost('date_added')) {
			$data['date_added'] = $this->request->getPost('date_added');
		} elseif (!empty($attendance)) {
			$data['date_added'] = date("m-Y",strtotime($attendance->date_added));
		} else {
			$data['date_added'] = '';
		}*/


		$this->template->view('attendanceForm',$data);
	}
	protected function validateForm() {
		$user_id=$this->uri->segment(4);
		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path
      	$regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
      	$regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor
		$rules=array(
			'paycode' => array(
				'field' => 'paycode',
				'label' => 'Paycode',
				'rules' => 'trim|required|max_length[100]'
			),

		);
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() == TRUE)
		{
			return true;
    	}
		else
		{
			$this->error['warning']=lang('Attendance.error_warning');
			return false;
    	}
		return !$this->error;
	}
	public function email_check($email, $user_id=''){
		$attendance = $this->attendanceModel->getEmployeeByEmail($email);
      	if (!empty($attendance) && $attendance->id != $user_id){
			$this->form_validation->set_message('email_check', "This email address is already in use.");
         	return FALSE;
		}else{
         	return TRUE;
      	}
   	}
	public function username_check($username, $user_id=''){
      $attendance = $this->attendanceModel->getEmployeeByUsername($username);
      if (!empty($attendance) && $attendance->id != $user_id){
            $this->form_validation->set_message('username_check', "This {field} provided is already in use.");
            return FALSE;
		}else{
         return TRUE;
      }
   }
	public function autocomplete(){
		$json = array();
		if (is_ajax()){
			$filter_data = array(
				'filter_search'  => $this->request->get('searchTerm'),
				'start' 		 => 0,
				'limit' 		 => 5
			);
			$filteredData = $this->attendanceModel->getEmployees($filter_data);
			//printr($filteredData);
			foreach($filteredData as $result){
				$json[] = array(
					'id' => $result->id,
					'text'    => $result->paycode,
					'empname'    => $result->attendance_name,
					'card_no' => $result->card_no,
					'department_name'     => $result->department_name,
				);
			}
			echo json_encode($json);
		}else{
         	return show_404();
      	}
	}
	public function downloadall(){
		$this->load->model('leave/application_model');
		$branch_id=$this->request->get('branch_id');

		$month="01/".$this->request->get('month');
		$timestamp = strtotime(str_replace('/', '-', $month));
		$month_day=date("t",$timestamp);
		$year_month=date("Ym",$timestamp);

		$this->load->model('users/users_model');
		if($branch_id=="-1"){
			$branch_id=0;
		}


		$style['heading1'] = [
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => 'FFFFA500']
            ]
        ];
        $style['heading2'] = [
            'font' => [
                'bold' => true,
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => 'FFFFFF00']
            ]
        ];
        $style['border'] = [
			'font' => [
                'bold' => true,
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => 'FFFFFF00']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

		$data['heading'] = "Employee Attendance Data-".date("M Y",$timestamp);

		$filter=array(
			'branch_id'=>$branch_id
		);
		$branches=$this->branchModel->getBranches($filter);

		$spreadsheet = new Spreadsheet();
		$leave_array=[

		];
		foreach($branches as $key=>$branch){
			$users = $this->users_model->getUsersByBranch($branch->id);
			//$branch=$this->branchModel->getBranch($branch_id);
			$envirnment=json_decode($branch->envirnment,true);

			$sites=[];

			if($branch->envirnment && $envirnment['site_available']=='yes'){
				$this->load->model('site/site_model');
				$sites=$this->site_model->getSites();
				$total=count($sites)-1;
			}else{
				$total=0;
			}

			$sheetindex = $key;
			$myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $branch->name);
			$spreadsheet->addSheet($myWorkSheet, $sheetindex);
			$spreadsheet->setActiveSheetIndex($sheetindex);
			$activeSheet = $spreadsheet->getActiveSheet();

			$heading=$data['heading'];
			$row=1;
			$activeSheet->setCellValue("A$row",$heading)->mergeCells("A$row:O$row");
			$activeSheet->getStyle("A$row")->applyFromArray($style['heading1']);
			$activeSheet->getStyle("A$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

			$row=2;
			$activeSheet->setCellValue("A$row", "SL.NO");
			$activeSheet->setCellValue("B$row", "Employee Code");
			$activeSheet->setCellValue("C$row", "Employee Name");
			$activeSheet->setCellValue("D$row", "Month Total Days");
			$activeSheet->setCellValue("E$row", "Absent Days");
			$activeSheet->setCellValue("F$row", "Present Weekly Off");
			$activeSheet->setCellValue("G$row", "Weekly Off");
			$activeSheet->setCellValue("H$row", "Holidays");
			$activeSheet->setCellValue("I$row", "EL");
			$activeSheet->setCellValue("J$row", "CL");
			$activeSheet->setCellValue("K$row", "SL");
			$activeSheet->setCellValue("L$row", "COF");
			$activeSheet->setCellValue("M$row", "OT");
			$activeSheet->setCellValue("N$row", "Arrear Days");
			$activeSheet->setCellValue("O$row", "Deduction Days");

			$activeSheet->getStyle("A$row:O$row")->applyFromArray($style['border']);

			foreach($users as $key=>$user){
				$lfilter=array(
					'user_id'=>$user->id,
					'ym'=>$year_month
				);
				$user_leaves=$this->application_model->getTotalUserLeaves($lfilter);
				//printr($user_leaves);
				$cl=$el=$sl=0;
				if($user_leaves){
					$clkey = searchForKey('1','leave_code', $user_leaves);
					if($clkey!=-1){
						$cl=$user_leaves[$clkey]['LeaveDays'];
					}

					$elkey = searchForKey('2','leave_code', $user_leaves);

					if($elkey!=-1){
						$el=$user_leaves[$elkey]['LeaveDays'];
					}

					$slkey = searchForKey('3','leave_code', $user_leaves);
					if($slkey!=-1){
						$sl=$user_leaves[$slkey]['LeaveDays'];
					}
				}

				$row++;

				$activeSheet->setCellValue("A$row", $key+1);
				$activeSheet->setCellValue("B$row", $user->username);
				$activeSheet->setCellValue("C$row", $user->firstname);
				$activeSheet->setCellValue("D$row", $month_day);
				$activeSheet->setCellValue("E$row", "");
				$activeSheet->setCellValue("F$row", "");
				$activeSheet->setCellValue("G$row", "");
				$activeSheet->setCellValue("H$row", "");
				$activeSheet->setCellValue("I$row", $el);
				$activeSheet->setCellValue("J$row", $cl);
				$activeSheet->setCellValue("K$row", $sl);
				$activeSheet->setCellValue("L$row", "");
				$activeSheet->setCellValue("M$row", "");
				$activeSheet->setCellValue("N$row", "");
				$activeSheet->setCellValue("O$row", "");

			}

			foreach(range('A','O') as $columnID) {
				$activeSheet->getColumnDimension($columnID)
					->setAutoSize(true);
			}

		}

		$spreadsheet->removeSheetByIndex(
			$spreadsheet->getIndex(
				$spreadsheet->getSheetByName('Worksheet')
			)
		);

		$spreadsheet->setActiveSheetIndex(0);



		$filename = "Attendance-sample".'-'.$month.".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0*/

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;


	}

	public function download(){
		$branch_id=$this->request->get('branch_id');
		$month="01/".$this->request->get('month');
		$timestamp = strtotime(str_replace('/', '-', $month));
		$month_day=date("t",$timestamp);
		$this->load->model('users/users_model');
		$users = $this->users_model->getUsersByBranch($branch_id);
		$branch=$this->branchModel->getBranch($branch_id);
		$envirnment=json_decode($branch->envirnment,true);
		$sites=[];

		if($envirnment['site_available']=='yes'){
			$this->load->model('site/site_model');
			$sites=$this->site_model->getSites();
			$total=count($sites)-1;
		}else{
			$total=0;
		}

		$style['heading1'] = [
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => 'FFFFA500']
            ]
        ];
        $style['heading2'] = [
            'font' => [
                'bold' => true,
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => 'FFFFFF00']
            ]
        ];
        $style['border'] = [
			'font' => [
                'bold' => true,
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => 'FFFFFF00']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

		$data['heading'] = "Employee Attendance Data-".date("M Y",strtotime($month));

		$sheets=array(
			'All Employee',
			'Site Info'

		);

		$spreadsheet = new Spreadsheet();

		foreach($sheets as $key=>$sheet){
			$sheetindex = $key;
			$myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $sheet);
			$spreadsheet->addSheet($myWorkSheet, $sheetindex);
			$spreadsheet->setActiveSheetIndex($sheetindex);
			$activeSheet = $spreadsheet->getActiveSheet();

			if($sheetindex==0){

				$heading=$data['heading'];
				$row=1;
				$activeSheet->setCellValue("A$row",$heading)->mergeCells("A$row:Q$row");
				$activeSheet->getStyle("A$row")->applyFromArray($style['heading1']);
				$activeSheet->getStyle("A$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

				$row=2;
				$activeSheet->setCellValue("A$row", "SL.NO");
				$activeSheet->setCellValue("B$row", "Employee Code");
				$activeSheet->setCellValue("C$row", "Employee Name");
				$activeSheet->setCellValue("D$row", "Month Total Days");
				$activeSheet->setCellValue("E$row", "Site ID");
				$activeSheet->setCellValue("F$row", "Absent Days");
				$activeSheet->setCellValue("G$row", "Present Days");
				$activeSheet->setCellValue("H$row", "Present Weekly Off");
				$activeSheet->setCellValue("I$row", "Weekly Off");
				$activeSheet->setCellValue("J$row", "Holidays");
				$activeSheet->setCellValue("K$row", "EL");
				$activeSheet->setCellValue("L$row", "CL");
				$activeSheet->setCellValue("M$row", "SL");
				$activeSheet->setCellValue("N$row", "COF");
				$activeSheet->setCellValue("O$row", "OT");
				$activeSheet->setCellValue("P$row", "Arrear Days");
				$activeSheet->setCellValue("Q$row", "Deduction Days");

				$activeSheet->getStyle("A$row:Q$row")->applyFromArray($style['border']);

				foreach($users as $key=>$user){
					$row++;

					$activeSheet->setCellValue("A$row", $key+1);
					$activeSheet->setCellValue("B$row", $user->username);
					$activeSheet->setCellValue("C$row", $user->firstname);
					$activeSheet->setCellValue("D$row", $month_day);
					$activeSheet->setCellValue("E$row", "");
					$activeSheet->setCellValue("F$row", "");
					$activeSheet->setCellValue("G$row", "");
					$activeSheet->setCellValue("H$row", "");
					$activeSheet->setCellValue("I$row", "");
					$activeSheet->setCellValue("J$row", "");
					$activeSheet->setCellValue("K$row", "");
					$activeSheet->setCellValue("L$row", "");
					$activeSheet->setCellValue("M$row", "");
					$activeSheet->setCellValue("N$row", "");
					$activeSheet->setCellValue("O$row", "");
					$activeSheet->setCellValue("P$row", "");
					$activeSheet->setCellValue("Q$row", "");
				}

				foreach(range('A','Q') as $columnID) {
					$activeSheet->getColumnDimension($columnID)
						->setAutoSize(true);
				}
			}else if($sheetindex==1){
				$heading="All Site Information";
				$row=1;
				$activeSheet->setCellValue("A$row",$heading)->mergeCells("A$row:B$row");
				$activeSheet->getStyle("A$row")->applyFromArray($style['heading1']);
				$activeSheet->getStyle("A$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

				$row=2;
				$activeSheet->setCellValue("A$row", "ID");
				$activeSheet->setCellValue("B$row", "Name");

				$activeSheet->getStyle("A$row:B$row")->applyFromArray($style['border']);

				foreach($sites as $key=>$site){
					$row++;

					$activeSheet->setCellValue("A$row", $site->id);
					$activeSheet->setCellValue("B$row", $site->name);
				}

				foreach(range('A','B') as $columnID) {
					$activeSheet->getColumnDimension($columnID)
						->setAutoSize(true);
				}
			}
		}

		$spreadsheet->removeSheetByIndex(
			$spreadsheet->getIndex(
				$spreadsheet->getSheetByName('Worksheet')
			)
		);

		$spreadsheet->setActiveSheetIndex(0);



		$filename = "Attendance-sample".'-'.$month.".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0*/

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;


	}
	public function download_old(){
		$branch_id=$this->request->get('branch_id');
		$month="01-".$this->request->get('month');
		$month_day=date("t",strtotime($month));
		$this->load->model('users/users_model');
		$users = $this->users_model->getUsersByBranch($branch_id);
		$branch=$this->branchModel->getBranch($branch_id);
		$envirnment=json_decode($branch->envirnment,true);
		$sites=[];

		if($envirnment['site_available']=='yes'){
			$this->load->model('site/site_model');
			$sites=$this->site_model->getSites();
			$total=count($sites)-1;
		}else{
			$total=0;
		}

		$allemp=new stdClass;
		$allemp->code="Office Employee";
		$allemp->name="Office Employee";
		array_unshift($sites,$allemp);



		$style['heading1'] = [
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => 'FFFFA500']
            ]
        ];
        $style['heading2'] = [
            'font' => [
                'bold' => true,
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => 'FFFFFF00']
            ]
        ];
        $style['border'] = [
			'font' => [
                'bold' => true,
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => 'FFFFFF00']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

		$data['heading'] = "Employee Attendance Data-".date("M Y",strtotime($month));



		$spreadsheet = new Spreadsheet();

		foreach($sites as $key=>$site){
			$sheetindex = $key;
			if($site->code){
			$myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $site->code);
			$spreadsheet->addSheet($myWorkSheet, $sheetindex);
			$spreadsheet->setActiveSheetIndex($sheetindex);
			$activeSheet = $spreadsheet->getActiveSheet();

			$heading=$data['heading'].'('.$site->name.')';
			$row=1;
			$activeSheet->setCellValue("A$row",$heading)->mergeCells("A$row:P$row");
			$activeSheet->getStyle("A$row")->applyFromArray($style['heading1']);
			$activeSheet->getStyle("A$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

			$row=2;
			$activeSheet->setCellValue("A$row", "SL.NO");
			$activeSheet->setCellValue("B$row", "Employee Code");
			$activeSheet->setCellValue("C$row", "Employee Name");
			$activeSheet->setCellValue("D$row", "Month Total Days");
			$activeSheet->setCellValue("E$row", "Absent Days");
			$activeSheet->setCellValue("F$row", "Present Days");
			$activeSheet->setCellValue("G$row", "Present Weekly Off");
			$activeSheet->setCellValue("H$row", "Weekly Off");
			$activeSheet->setCellValue("I$row", "Holidays");
			$activeSheet->setCellValue("J$row", "EL");
			$activeSheet->setCellValue("K$row", "CL");
			$activeSheet->setCellValue("L$row", "SL");
			$activeSheet->setCellValue("M$row", "COF");
			$activeSheet->setCellValue("N$row", "OT");
			$activeSheet->setCellValue("O$row", "Arrear Days");
			$activeSheet->setCellValue("P$row", "Deduction Days");

			$activeSheet->getStyle("A$row:P$row")->applyFromArray($style['border']);

			foreach($users as $key=>$user){
				$row++;

				$activeSheet->setCellValue("A$row", $key+1);
				$activeSheet->setCellValue("B$row", $user->username);
				$activeSheet->setCellValue("C$row", $user->firstname);
				$activeSheet->setCellValue("D$row", $month_day);
				$activeSheet->setCellValue("E$row", "");
				$activeSheet->setCellValue("F$row", "");
				$activeSheet->setCellValue("G$row", "");
				$activeSheet->setCellValue("H$row", "");
				$activeSheet->setCellValue("I$row", "");
				$activeSheet->setCellValue("J$row", "");
				$activeSheet->setCellValue("K$row", "");
				$activeSheet->setCellValue("L$row", "");
				$activeSheet->setCellValue("M$row", "");
				$activeSheet->setCellValue("N$row", "");
				$activeSheet->setCellValue("O$row", "");
				$activeSheet->setCellValue("P$row", "");
			}

			foreach(range('A','P') as $columnID) {
				$activeSheet->getColumnDimension($columnID)
					->setAutoSize(true);
			}
			}
		}

		$spreadsheet->removeSheetByIndex(
			$spreadsheet->getIndex(
				$spreadsheet->getSheetByName('Worksheet')
			)
		);

		$spreadsheet->setActiveSheetIndex(0);



		$filename = "Attendance-sample".'-'.$month.".xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0*/

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;


	}
	public function upload(){
		$json=array();
		$month=$this->request->getPost('month');
		$this->load->library('upload');
		$this->load->model('users/users_model');
		$configmain = array(
			'allowed_types' => 'csv|xlsx|xls',
			'upload_path' => FCPATH . 'storage/uploads/files',
			'max_size' => 2097152,
			'overwrite' => True,
			'file_name' =>  'attendance'
		);
		$this->upload->initialize($configmain);
		if($this->upload->do_upload('battendance')){
			$data = array('upload_data' => $this->upload->data());
			$file=$data['upload_data']['full_path'];

			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			$spreadsheet = $reader->load($file);
			$sheetCount = $spreadsheet->getSheetCount();
			//echo $sheetCount;
			$empdata=$employeeofficedata=$employeedata=$employeshiftdata=$employetimedata=array();
			for ($i = 0; $i < $sheetCount; $i++) {
				$sheet = $spreadsheet->getSheet($i);
				$sheetData = $sheet->toArray(null, true, true, true);

				array_shift($sheetData);
				array_shift($sheetData);
				array_shift($sheetData);

				foreach($sheetData as $sheet){
					if($i==0){
						$paycode=trim($sheet['B']);

						$user=$this->users_model->getUserByUsername($paycode);
						if($user){
							$user_id=$user->id;
							$empdata[]=array(
								"user_id"=>$user_id,
								"paycode"=>$paycode,
								"employee_name"=>$user->firstname,
								"branch_id"=>$user->branch_id,
								"month_days"=>$sheet['D'],
								"absent_days"=>$sheet['E'],
								"present_days"=>$sheet['F'],
								"pwo"=>$sheet['G'],
								"weekly_off"=>$sheet['H'],
								"holidays"=>$sheet['I'],
								"el"=>$sheet['J'],
								"cl"=>$sheet['K'],
								"sl"=>$sheet['L'],
								"cof"=>$sheet['M'],
								"ot"=>$sheet['N'],
								"arrear_days"=>$sheet['O'],
								"deduction_days"=>$sheet['P'],
								"status"=>1,
								"date_added"=>date("Y-m-t", strtotime("01-".$month)),

							);
						}
					}
				}
			}


			foreach($empdata as $edata){
				$attendance=$this->attendanceModel->getAttendanceByUserID($edata['user_id'],$edata['date_added']);
				if($attendance){
					$this->attendanceModel->editAttendance($attendance->id,$edata);
				}else{
					$this->attendanceModel->addAttendance($edata);
				}
			}
			$json= array(
				'success'=>'Employee attendance Upload successfully',
				'redirect'=>admin_url('attendance')
			);
		}else{
			$json['error'] = $this->upload->display_errors();
		}
		echo json_encode($json);
    	exit;
	}

	public function uploadall(){
		$json=array();
		$month=$this->request->getPost('month');
		$this->load->library('upload');
		$this->load->model('users/users_model');

		$month="01/".$this->request->getPost('month');
		$timestamp = strtotime(str_replace('/', '-', $month));
		$attendance_date=date("Y-m-t", $timestamp);
		$configmain = array(
			'allowed_types' => 'csv|xlsx|xls',
			'upload_path' => FCPATH . 'storage/uploads/files',
			'max_size' => 2097152,
			'overwrite' => True,
			'file_name' =>  'attendance'
		);
		$this->upload->initialize($configmain);
		if($this->upload->do_upload('battendance')){
			$data = array('upload_data' => $this->upload->data());
			$file=$data['upload_data']['full_path'];

			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			$spreadsheet = $reader->load($file);
			$sheetCount = $spreadsheet->getSheetCount();
			//echo $sheetCount;
			$empdata=$employeeofficedata=$employeedata=$employeshiftdata=$employetimedata=array();
			for ($i = 0; $i < $sheetCount; $i++) {
				$sheet = $spreadsheet->getSheet($i);
				$sheetData = $sheet->toArray(null, true, true, true);

				array_shift($sheetData);
				array_shift($sheetData);

				foreach($sheetData as $sheet){

					$paycode=trim($sheet['B']);

					$user=$this->users_model->getUserByUsername($paycode);
					if($user){
						$user_id=$user->id;
						$empdata[]=array(
							"user_id"=>$user_id,
							"paycode"=>$paycode,
							"employee_name"=>$user->firstname,
							"branch_id"=>$user->branch_id,
							"month_days"=>$sheet['D'],
							"absent_days"=>$sheet['E'],
							"pwo"=>$sheet['F'],
							"weekly_off"=>$sheet['G'],
							"holidays"=>$sheet['H'],
							"el"=>$sheet['I'],
							"cl"=>$sheet['J'],
							"sl"=>$sheet['K'],
							"cof"=>$sheet['L'],
							"ot"=>$sheet['M'],
							"arrear_days"=>$sheet['N'],
							"deduction_days"=>$sheet['O'],
							"status"=>1,
							"date_added"=>$attendance_date,

						);
					}

				}
			}


			foreach($empdata as $edata){
				$attendance=$this->attendanceModel->getAttendanceByUserID($edata['user_id'],$edata['date_added']);
				if($attendance){
					$this->attendanceModel->editAttendance($attendance->id,$edata);
				}else{
					$this->attendanceModel->addAttendance($edata);
				}
			}
			$json= array(
				'success'=>'Employee attendance Upload successfully',
				'redirect'=>admin_url('attendance')
			);
		}else{
			$json['error'] = $this->upload->display_errors();
		}
		echo json_encode($json);
    	exit;
	}

}
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */
/* End of file hmvc.php */