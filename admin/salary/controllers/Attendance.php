<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');use PhpOffice\PhpSpreadsheet\Spreadsheet;use PhpOffice\PhpSpreadsheet\Writer\Xlsx;use PhpOffice\PhpSpreadsheet\IOFactory;use PhpOffice\PhpSpreadsheet\Style\Fill;use PhpOffice\PhpSpreadsheet\Style\Border;use PhpOffice\PhpSpreadsheet\Style\Alignment;use PhpOffice\PhpSpreadsheet\Cell\DataType;class Attendance extends Admin_Controller {	private $error = array();	public function __construct(){		parent::__construct();		$this->load->model('attendance_model');		$this->load->model('employee/employee_model');			$this->load->model('branch/branch_model');				}	public function index(){      	$this->lang->load('attendance');		$this->template->set_meta_title($this->lang->line('heading_title'));		$this->getList();  	}	protected function search() {		$requestData= $_REQUEST;		$totalData = $this->attendance_model->getTotalAttendances();		$totalFiltered = $totalData;		$filter_data = array(			'filter_search'  => $requestData['search']['value'],			'branch_id' => $requestData['branch_id'],			'department_id' => $requestData['department_id'],			'designation_id' => $requestData['designation_id'],			'month' => $requestData['month'],			'order'  		 => $requestData['order'][0]['dir'],			'sort' 			 => $requestData['order'][0]['column'],			'start' 		 => $requestData['start'],			'limit' 		 => $requestData['length']		);		$totalFiltered = $this->attendance_model->getTotalAttendances($filter_data);		$filteredData = $this->attendance_model->getAttendances($filter_data);		//printr($filteredData);		$datatable=array();		foreach($filteredData as $result) {			if($result->id){				$url=admin_url('salary/attendance/edit/'.$result->user_id .'/'.$requestData['month'].'/'.$result->id );				$urltxt="Edit Attendance";			}else{				$url=admin_url('salary/attendance/add/'.$result->user_id .'/'.$requestData['month']);				$urltxt="Add Attendance";			}			$action  = '<div class="btn-group btn-group-sm pull-right">';			$action .= 		'<a class="btn btn-sm btn-primary" href="'.$url.'">'.$urltxt.'</a>';			//$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('salary/attendance/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';			$action .= '</div>';			$datatable[]=array(				'<input type="checkbox" name="selected[]" value="'.$result->id.'" />',				$result->employee_name,				$result->paycode,				$result->present_days,				$result->pwo,				$action			);		}		//printr($datatable);		$json_data = array(			"draw"            => isset($requestData['draw']) ? intval( $requestData['draw'] ):1,			"recordsTotal"    => intval( $totalData ),			"recordsFiltered" => intval( $totalFiltered ),			"data"            => $datatable		);		$this->output		->set_content_type('application/json')		->set_output(json_encode($json_data));  // send data as json format	}	public function add(){		$this->lang->load('attendance');		$this->template->set_meta_title($this->lang->line('heading_title'));		if ($this->input->server('REQUEST_METHOD') === 'POST' && $this->validateForm()){				$var = $_POST['month_days'].'/'.$_POST['date_added'];			$date = str_replace('/', '-', $var);			$_POST['date_added']=date("Y-m-d", strtotime($date));			$userid=$this->attendance_model->addAttendance($this->input->post());			$this->session->set_flashdata('message', 'attendance Saved Successfully.');			redirect(ADMIN_PATH.'/salary/attendance');		}		$this->getForm();	}	public function edit(){		$this->lang->load('attendance');		$this->template->set_meta_title($this->lang->line('heading_title'));		if ($this->input->server('REQUEST_METHOD') === 'POST'){				$user_id=$this->uri->segment(5);			echo $user_id;			exit;			$_POST['date_added']=date("Y-m-t", strtotime("01-".$_POST['date_added']));			$this->attendance_model->editAttendance($user_id,$this->input->post());			$this->session->set_flashdata('message', 'attendance Updated Successfully.');			redirect(ADMIN_PATH.'/salary/attendance');		}		$this->getForm();	}	public function delete(){		if ($this->input->post('selected')){         $selected = $this->input->post('selected');      }else{         $selected = (array) $this->uri->segment(5);       }		$this->attendance_model->deleteAttendance($selected);		$this->session->set_flashdata('message', 'attendance deleted Successfully.');		redirect(ADMIN_PATH.'/salary/attendance');	}	protected function getList() {		$data['breadcrumbs'] = array();		$data['breadcrumbs'][] = array(			'text' => $this->lang->line('heading_title'),			'href' => admin_url('attendance')		);		$this->template->add_package(array('datepicker','datatable','select2'),true);		$data['add'] = admin_url('salary/attendance/add');		$data['delete'] = admin_url('salary/attendance/delete');		$data['datatable_url'] = admin_url('salary/attendance/search');		$data['attendance_sample']=base_url('storage/uploads/files/attendance-sample.xlsx');		$data['heading_title'] = $this->lang->line('heading_title');		$data['text_list'] = $this->lang->line('text_list');		$data['text_no_results'] = $this->lang->line('text_no_results');		$data['text_confirm'] = $this->lang->line('text_confirm');		$data['button_add'] = $this->lang->line('button_add');		$data['button_edit'] = $this->lang->line('button_edit');		$data['button_delete'] = $this->lang->line('button_delete');		if(isset($this->error['warning'])){			$data['error'] 	= $this->error['warning'];		}		if ($this->input->post('selected')) {			$data['selected'] = (array)$this->input->post('selected');		} else {			$data['selected'] = array();		}				$data['month'] = date('m/Y');				$this->load->model('branch/branch_model');		$data['branches']=$this->branch_model->getBranches();				$this->load->model('department/department_model');		$data['departments']=$this->department_model->getDepartments();				$this->load->model('designation/designation_model');		$data['designations']=$this->designation_model->getDesignations();				$this->load->model('site/site_model');		$data['sites']=$this->site_model->getSites();				$this->template->view('attendances', $data);	}	public function attendanceList(){		$requestData= $_REQUEST;		$totalData = $this->attendance_model->getTotalAttendances();		$totalFiltered = $totalData;		$filter_data = array(			'filter_search'  => $requestData['search']['value'],			'branch_id' => $requestData['branch_id'],			'department_id' => $requestData['department_id'],			'designation_id' => $requestData['designation_id'],			'month' => $requestData['month'],			'order'  		 => $requestData['order'][0]['dir'],			'sort' 			 => $requestData['order'][0]['column'],			'start' 		 => $requestData['start'],			'limit' 		 => $requestData['length']		);		$totalFiltered = $this->attendance_model->getTotalAttendances($filter_data);		$filteredData = $this->attendance_model->getAttendances($filter_data);		//printr($filteredData);		$datatable=array();		foreach($filteredData as $result) {						$action  = '<div class="btn-group btn-group-sm pull-right">';			$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('salary/attendance/edit/'.$result->id.'?id='.$result->user_id).'"><i class="fa fa-pencil"></i></a>';			$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('salary/attendance/delete/'.$result->id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fa fa-trash-o"></i></a>';			$action .= '</div>';			$datatable[]=array(				'<input type="checkbox" name="selected[]" value="'.$result->id.'" />',				$result->employee_name,				$result->paycode,				$result->date_added,				$result->present_days,				$result->pwo,				$action			);		}		//printr($datatable);		$json_data = array(			"draw"            => isset($requestData['draw']) ? intval( $requestData['draw'] ):1,			"recordsTotal"    => intval( $totalData ),			"recordsFiltered" => intval( $totalFiltered ),			"data"            => $datatable		);		$this->output		->set_content_type('application/json')		->set_output(json_encode($json_data));	}	protected function getForm(){		$data = array();		$data = $this->lang->load('attendance');		$this->template->add_package(array('ckeditor','ckfinder','colorbox','select2','datepicker','datatable'),true);		$data['breadcrumbs'] = array();		$data['breadcrumbs'][] = array(			'text' => $this->lang->line('heading_title'),			'href' => admin_url('salary/attendance')		);		/*$url=[];		if($this->input->get('id')){			$url['id']=$this->input->get('id');		}		if($this->input->get('month')){			$url['month']=$this->input->get('month');		}*/		$month=$this->uri->segment(6).'/'.$this->uri->segment(7);					//printr($_SESSION);		$_SESSION['isLoggedIn'] = true;		$data['heading_title'] 	= $this->lang->line('heading_title');		$data['text_form'] = $this->uri->segment(8) ? "attendance Edit" : "attendance Add";		$data['text_image'] =$this->lang->line('text_image');		$data['text_none'] = $this->lang->line('text_none');		$data['text_clear'] = $this->lang->line('text_clear');		$data['cancel'] = admin_url('salary/attendance');		$data['button_save'] = $this->lang->line('button_save');		$data['button_cancel'] = $this->lang->line('button_cancel');		if(isset($this->error['warning'])){			$data['error'] 	= $this->error['warning'];		}		if ($this->uri->segment(8) && ($this->input->server('REQUEST_METHOD') != 'POST')) {			$attendance = $this->attendance_model->getAttendance($this->uri->segment(5),$month);			$data['attendance_id']=$this->uri->segment(5);			$data['edit']=true;		}else{			$data['edit']=false;			$data['attendance_id']=0;		}		if($this->uri->segment(5)){			$empoffice_info = $this->employee_model->getEmployeeOffice($this->uri->segment(5));		}else{			$empoffice_info=[];		}				$this->load->model('site/site_model');		$data['sites']=$this->site_model->getSites();				//branch				//printr($data['shifts']);				foreach($this->attendance_model->getTableColumns() as $field) {			if($this->input->post($field)) {				$data[$field] = $this->input->post($field);			} else if(isset($attendance->{$field}) && $attendance->{$field}) {				$data[$field] = $attendance->{$field};			} else {				$data[$field] = '';			}		}				if($month){			$data['date_added']=$month;			$data['month_days']=date("t", strtotime("01/".$month));		}else{			$data['date_added']="";			$data['month_days']="";		}						if ($this->input->post('site_attendance')) {			$site_attendances = $this->input->post('site_attendance');		} elseif ($this->uri->segment(5)) {			$site_attendances = $this->attendance_model->getSiteAttendances($this->uri->segment(4));		} else {			$site_attendances = array();		}		$data['site_attendances'] = array();				foreach ($site_attendances as $site_attendance) {			$data['site_attendances'][] = array(				'designation_id'=> $site_attendance->designation_id,				'type' => $site_attendance->type,				'salary' => $site_attendance->salary			);		}				if (!empty($empoffice_info)) {			$data['user_id'] = $empoffice_info->user_id;		} else {			$data['user_id'] = '';		}				if (!empty($empoffice_info)) {			$data['branch_id'] = $empoffice_info->branch_id;		} else {			$data['branch_id'] = 0;		}				if ($this->input->post('paycode')) {			$data['paycode'] = $this->input->post('paycode');		} elseif (!empty($empoffice_info)) {			$data['paycode'] = $empoffice_info->paycode;		} else {			$data['paycode'] = '';		}				if ($this->input->post('employee_name')) {			$data['employee_name'] = $this->input->post('employee_name');		} elseif (!empty($empoffice_info)) {			$data['employee_name'] = $empoffice_info->employee_name;		} else {			$data['employee_name'] = '';		}				if ($this->input->post('card_no')) {			$data['card_no'] = $this->input->post('card_no');		} elseif (!empty($empoffice_info)) {			$data['card_no'] = $empoffice_info->card_no;		} else {			$data['card_no'] = '';		}				if ($this->input->post('branch_name')) {			$data['branch_name'] = $this->input->post('branch_name');		} elseif (!empty($empoffice_info)) {			$data['branch_name'] = $empoffice_info->branch_name;		} else {			$data['branch_name'] = '';		}				if ($this->input->post('department_name')) {			$data['department_name'] = $this->input->post('department_name');		} elseif (!empty($empoffice_info)) {			$data['department_name'] = $empoffice_info->department_name;		} else {			$data['department_name'] = '';		}				if ($this->input->post('designation_name')) {			$data['designation_name'] = $this->input->post('designation_name');		} elseif (!empty($empoffice_info)) {			$data['designation_name'] = $empoffice_info->designation_name;		} else {			$data['designation_name'] = '';		}				/*if ($this->input->post('date_added')) {			$data['date_added'] = $this->input->post('date_added');		} elseif (!empty($attendance)) {			$data['date_added'] = date("m-Y",strtotime($attendance->date_added));		} else {			$data['date_added'] = '';		}*/						$this->template->view('attendanceForm',$data);	}	protected function validateForm() {		$user_id=$this->uri->segment(4);		$regex = "(\/?([a-zA-Z0-9+\$_-]\.?)+)*\/?"; // Path      	$regex .= "(\?[a-zA-Z+&\$_.-][a-zA-Z0-9;:@&%=+\/\$_.-]*)?"; // GET Query      	$regex .= "(#[a-zA-Z_.-][a-zA-Z0-9+\$_.-]*)?"; // Anchor 		$rules=array(
			'paycode' => array(
				'field' => 'paycode', 
				'label' => 'Paycode', 
				'rules' => 'trim|required|max_length[100]'
			),					);		$this->form_validation->set_rules($rules);		if ($this->form_validation->run() == TRUE)		{			return true;    	}		else		{			$this->error['warning']=$this->lang->line('error_warning');			return false;    	}		return !$this->error;	}	public function email_check($email, $user_id=''){		$attendance = $this->attendance_model->getEmployeeByEmail($email);      	if (!empty($attendance) && $attendance->id != $user_id){			$this->form_validation->set_message('email_check', "This email address is already in use.");         	return FALSE;		}else{         	return TRUE;      	}   	}	public function username_check($username, $user_id=''){      $attendance = $this->attendance_model->getEmployeeByUsername($username);      if (!empty($attendance) && $attendance->id != $user_id){            $this->form_validation->set_message('username_check', "This {field} provided is already in use.");            return FALSE;		}else{         return TRUE;      }   }	public function autocomplete(){		$json = array();		if (is_ajax()){			$filter_data = array(				'filter_search'  => $this->input->get('searchTerm'),				'start' 		 => 0,				'limit' 		 => 5			);			$filteredData = $this->attendance_model->getEmployees($filter_data);			//printr($filteredData);			foreach($filteredData as $result){				$json[] = array(					'id' => $result->id,					'text'    => $result->paycode,					'empname'    => $result->attendance_name,					'card_no' => $result->card_no,					'department_name'     => $result->department_name,				);			}			echo json_encode($json);		}else{         	return show_404();      	}	}		public function download(){		$branch_id=$this->input->get('branch_id');		$month="01-".$this->input->get('month');		$month_day=date("t",strtotime($month));		$this->load->model('users/users_model');			$users = $this->users_model->getUsersByBranch($branch_id);		$branch=$this->branch_model->getBranch($branch_id);		$envirnment=json_decode($branch->envirnment,true);		$sites=[];					if($envirnment['site_available']=='yes'){			$this->load->model('site/site_model');			$sites=$this->site_model->getSites();			$total=count($sites)-1;		}else{			$total=0;		}		$style['heading1'] = [            'font' => [                'bold' => true,                'size' => 12,            ],            'fill' => [                'fillType' => Fill::FILL_SOLID,                'color' => ['argb' => 'FFFFA500']            ]        ];        $style['heading2'] = [            'font' => [                'bold' => true,                'size' => 10,            ],            'fill' => [                'fillType' => Fill::FILL_SOLID,                'color' => ['argb' => 'FFFFFF00']            ]        ];        $style['border'] = [			'font' => [                'bold' => true,                'size' => 10,            ],            'fill' => [                'fillType' => Fill::FILL_SOLID,                'color' => ['argb' => 'FFFFFF00']            ],            'borders' => [                'allBorders' => [                    'borderStyle' => Border::BORDER_THIN,                    'color' => ['argb' => 'FF000000'],                ],            ],        ];				$data['heading'] = "Employee Attendance Data-".date("M Y",strtotime($month));        		$sheets=array(			'All Employee',			'Site Info'					);				$spreadsheet = new Spreadsheet();				foreach($sheets as $key=>$sheet){			$sheetindex = $key;			$myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $sheet);			$spreadsheet->addSheet($myWorkSheet, $sheetindex);			$spreadsheet->setActiveSheetIndex($sheetindex);			$activeSheet = $spreadsheet->getActiveSheet();						if($sheetindex==0){							$heading=$data['heading'];				$row=1;				$activeSheet->setCellValue("A$row",$heading)->mergeCells("A$row:Q$row");					$activeSheet->getStyle("A$row")->applyFromArray($style['heading1']);				$activeSheet->getStyle("A$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);								$row=2;				$activeSheet->setCellValue("A$row", "SL.NO");				$activeSheet->setCellValue("B$row", "Employee Code");				$activeSheet->setCellValue("C$row", "Employee Name");				$activeSheet->setCellValue("D$row", "Month Total Days");				$activeSheet->setCellValue("E$row", "Site ID");				$activeSheet->setCellValue("F$row", "Absent Days");				$activeSheet->setCellValue("G$row", "Present Days");				$activeSheet->setCellValue("H$row", "Present Weekly Off");				$activeSheet->setCellValue("I$row", "Weekly Off");				$activeSheet->setCellValue("J$row", "Holidays");				$activeSheet->setCellValue("K$row", "EL");				$activeSheet->setCellValue("L$row", "CL");				$activeSheet->setCellValue("M$row", "SL");				$activeSheet->setCellValue("N$row", "COF");				$activeSheet->setCellValue("O$row", "OT");				$activeSheet->setCellValue("P$row", "Arrear Days");				$activeSheet->setCellValue("Q$row", "Deduction Days");								$activeSheet->getStyle("A$row:Q$row")->applyFromArray($style['border']);								foreach($users as $key=>$user){					$row++;					$activeSheet->setCellValue("A$row", $key+1);					$activeSheet->setCellValue("B$row", $user->username);					$activeSheet->setCellValue("C$row", $user->firstname);					$activeSheet->setCellValue("D$row", $month_day);					$activeSheet->setCellValue("E$row", "");					$activeSheet->setCellValue("F$row", "");					$activeSheet->setCellValue("G$row", "");					$activeSheet->setCellValue("H$row", "");					$activeSheet->setCellValue("I$row", "");					$activeSheet->setCellValue("J$row", "");					$activeSheet->setCellValue("K$row", "");					$activeSheet->setCellValue("L$row", "");					$activeSheet->setCellValue("M$row", "");					$activeSheet->setCellValue("N$row", "");					$activeSheet->setCellValue("O$row", "");						$activeSheet->setCellValue("P$row", "");					$activeSheet->setCellValue("Q$row", "");				}								foreach(range('A','Q') as $columnID) {					$activeSheet->getColumnDimension($columnID)						->setAutoSize(true);				}			}else if($sheetindex==1){				$heading="All Site Information";				$row=1;				$activeSheet->setCellValue("A$row",$heading)->mergeCells("A$row:B$row");					$activeSheet->getStyle("A$row")->applyFromArray($style['heading1']);				$activeSheet->getStyle("A$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);								$row=2;				$activeSheet->setCellValue("A$row", "ID");				$activeSheet->setCellValue("B$row", "Name");								$activeSheet->getStyle("A$row:B$row")->applyFromArray($style['border']);								foreach($sites as $key=>$site){					$row++;					$activeSheet->setCellValue("A$row", $site->id);					$activeSheet->setCellValue("B$row", $site->name);				}								foreach(range('A','B') as $columnID) {					$activeSheet->getColumnDimension($columnID)						->setAutoSize(true);				}			}		}				$spreadsheet->removeSheetByIndex(			$spreadsheet->getIndex(				$spreadsheet->getSheetByName('Worksheet')			)		);				$spreadsheet->setActiveSheetIndex(0);						$filename = "Attendance-sample".'-'.$month.".xlsx";        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');        header('Content-Disposition: attachment;filename="'.$filename.'"');        header('Cache-Control: max-age=0');		// If you're serving to IE 9, then the following may be needed        header('Cache-Control: max-age=1');		// If you're serving to IE over SSL, then the following may be needed        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1        header('Pragma: public'); // HTTP/1.0*/        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');        $writer->save('php://output');        exit;					}	public function download_old(){		$branch_id=$this->input->get('branch_id');		$month="01-".$this->input->get('month');		$month_day=date("t",strtotime($month));		$this->load->model('users/users_model');			$users = $this->users_model->getUsersByBranch($branch_id);		$branch=$this->branch_model->getBranch($branch_id);		$envirnment=json_decode($branch->envirnment,true);		$sites=[];					if($envirnment['site_available']=='yes'){			$this->load->model('site/site_model');			$sites=$this->site_model->getSites();			$total=count($sites)-1;		}else{			$total=0;		}				$allemp=new stdClass;		$allemp->code="Office Employee";		$allemp->name="Office Employee";		array_unshift($sites,$allemp);							$style['heading1'] = [            'font' => [                'bold' => true,                'size' => 12,            ],            'fill' => [                'fillType' => Fill::FILL_SOLID,                'color' => ['argb' => 'FFFFA500']            ]        ];        $style['heading2'] = [            'font' => [                'bold' => true,                'size' => 10,            ],            'fill' => [                'fillType' => Fill::FILL_SOLID,                'color' => ['argb' => 'FFFFFF00']            ]        ];        $style['border'] = [			'font' => [                'bold' => true,                'size' => 10,            ],            'fill' => [                'fillType' => Fill::FILL_SOLID,                'color' => ['argb' => 'FFFFFF00']            ],            'borders' => [                'allBorders' => [                    'borderStyle' => Border::BORDER_THIN,                    'color' => ['argb' => 'FF000000'],                ],            ],        ];				$data['heading'] = "Employee Attendance Data-".date("M Y",strtotime($month));        						$spreadsheet = new Spreadsheet();				foreach($sites as $key=>$site){			$sheetindex = $key;			if($site->code){			$myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $site->code);			$spreadsheet->addSheet($myWorkSheet, $sheetindex);			$spreadsheet->setActiveSheetIndex($sheetindex);			$activeSheet = $spreadsheet->getActiveSheet();						$heading=$data['heading'].'('.$site->name.')';			$row=1;			$activeSheet->setCellValue("A$row",$heading)->mergeCells("A$row:P$row");				$activeSheet->getStyle("A$row")->applyFromArray($style['heading1']);			$activeSheet->getStyle("A$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);						$row=2;			$activeSheet->setCellValue("A$row", "SL.NO");			$activeSheet->setCellValue("B$row", "Employee Code");			$activeSheet->setCellValue("C$row", "Employee Name");			$activeSheet->setCellValue("D$row", "Month Total Days");			$activeSheet->setCellValue("E$row", "Absent Days");			$activeSheet->setCellValue("F$row", "Present Days");			$activeSheet->setCellValue("G$row", "Present Weekly Off");			$activeSheet->setCellValue("H$row", "Weekly Off");			$activeSheet->setCellValue("I$row", "Holidays");			$activeSheet->setCellValue("J$row", "EL");			$activeSheet->setCellValue("K$row", "CL");			$activeSheet->setCellValue("L$row", "SL");			$activeSheet->setCellValue("M$row", "COF");			$activeSheet->setCellValue("N$row", "OT");			$activeSheet->setCellValue("O$row", "Arrear Days");			$activeSheet->setCellValue("P$row", "Deduction Days");						$activeSheet->getStyle("A$row:P$row")->applyFromArray($style['border']);						foreach($users as $key=>$user){				$row++;				$activeSheet->setCellValue("A$row", $key+1);				$activeSheet->setCellValue("B$row", $user->username);				$activeSheet->setCellValue("C$row", $user->firstname);				$activeSheet->setCellValue("D$row", $month_day);				$activeSheet->setCellValue("E$row", "");				$activeSheet->setCellValue("F$row", "");				$activeSheet->setCellValue("G$row", "");				$activeSheet->setCellValue("H$row", "");				$activeSheet->setCellValue("I$row", "");				$activeSheet->setCellValue("J$row", "");				$activeSheet->setCellValue("K$row", "");				$activeSheet->setCellValue("L$row", "");				$activeSheet->setCellValue("M$row", "");				$activeSheet->setCellValue("N$row", "");				$activeSheet->setCellValue("O$row", "");					$activeSheet->setCellValue("P$row", "");			}						foreach(range('A','P') as $columnID) {				$activeSheet->getColumnDimension($columnID)					->setAutoSize(true);			}			}		}				$spreadsheet->removeSheetByIndex(			$spreadsheet->getIndex(				$spreadsheet->getSheetByName('Worksheet')			)		);				$spreadsheet->setActiveSheetIndex(0);						$filename = "Attendance-sample".'-'.$month.".xlsx";        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');        header('Content-Disposition: attachment;filename="'.$filename.'"');        header('Cache-Control: max-age=0');		// If you're serving to IE 9, then the following may be needed        header('Cache-Control: max-age=1');		// If you're serving to IE over SSL, then the following may be needed        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1        header('Pragma: public'); // HTTP/1.0*/        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');        $writer->save('php://output');        exit;					}	public function upload(){		$json=array();		$month=$this->input->post('month');		$this->load->library('upload');		$this->load->model('users/users_model');		$configmain = array(			'allowed_types' => 'csv|xlsx|xls', 			'upload_path' => FCPATH . 'storage/uploads/files', 			'max_size' => 2097152,			'overwrite' => True, 			'file_name' =>  'attendance'		);		$this->upload->initialize($configmain); 		if($this->upload->do_upload('battendance')){  			$data = array('upload_data' => $this->upload->data());								$file=$data['upload_data']['full_path']; 			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();			$spreadsheet = $reader->load($file);			$sheetCount = $spreadsheet->getSheetCount();			//echo $sheetCount;			$empdata=$employeeofficedata=$employeedata=$employeshiftdata=$employetimedata=array();			for ($i = 0; $i < $sheetCount; $i++) {				$sheet = $spreadsheet->getSheet($i);				$sheetData = $sheet->toArray(null, true, true, true);								array_shift($sheetData);				array_shift($sheetData);				array_shift($sheetData);								foreach($sheetData as $sheet){					if($i==0){						$paycode=trim($sheet['B']);												$user=$this->users_model->getUserByUsername($paycode);						if($user){							$user_id=$user->id;							$empdata[]=array(								"user_id"=>$user_id,								"paycode"=>$paycode,								"employee_name"=>$user->firstname,								"branch_id"=>$user->branch_id,								"month_days"=>$sheet['D'],								"absent_days"=>$sheet['E'],								"present_days"=>$sheet['F'],								"pwo"=>$sheet['G'],								"weekly_off"=>$sheet['H'],								"holidays"=>$sheet['I'],								"el"=>$sheet['J'],								"cl"=>$sheet['K'],								"sl"=>$sheet['L'],								"cof"=>$sheet['M'],								"ot"=>$sheet['N'],								"arrear_days"=>$sheet['O'],								"deduction_days"=>$sheet['P'],								"status"=>1,								"date_added"=>date("Y-m-t", strtotime("01-".$month)),															);						}					}				}			}									foreach($empdata as $edata){				$attendance=$this->attendance_model->getAttendanceByUserID($edata['user_id'],$edata['date_added']);				if($attendance){					$this->attendance_model->editAttendance($attendance->id,$edata);				}else{					$this->attendance_model->addAttendance($edata);				}			}			$json= array(				'success'=>'Employee attendance Upload successfully',				'redirect'=>admin_url('salary/attendance')			);		}else{			$json['error'] = $this->upload->display_errors();		}		echo json_encode($json);    	exit;	}	}/* Location: ./application/widgets/hmvc/controllers/hmvc.php *//* End of file hmvc.php */