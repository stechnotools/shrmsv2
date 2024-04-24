<?php
namespace Admin\Employee\Controllers;
use Admin\Bank\Models\BankModel;
use Admin\Branch\Models\BranchModel;
use Admin\Category\Models\CategoryModel;
use Admin\Department\Models\DepartmentModel;
use Admin\Designation\Models\DesignationModel;
use Admin\Employee\Models\EmployeeModel;
use Admin\Grade\Models\GradeModel;
use Admin\Hod\Models\HodModel;
use Admin\Location\Models\LocationModel;
use Admin\Section\Controllers\Section;
use Admin\Section\Models\SectionModel;
use Admin\Shift\Models\ShiftModel;
use Admin\Workorder\Models\WorkorderModel;
use App\Controllers\AdminController;
use DateTime;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Employee extends AdminController {
	private $error = array();
	private  $employeeModel;
	public function __construct(){
        $this->employeeModel=new EmployeeModel();
	}

	public function index(){
		$this->template->set_meta_title(lang('Employee.heading_title'));
        return $this->getList();
	}

	public function search() {
		$requestData= $_REQUEST;
		$totalData = $this->employeeModel->getTotal();

		$totalFiltered = $totalData;

		$filter_data = array(
			'filter_search' => $requestData['search']['value'],
			'branch_id' 	=> $requestData['branch_id'],
			'department_id' => $requestData['department_id'],
			'designation_id'=> $requestData['designation_id'],
			'status' 		=> $requestData['status'],
			'order'  		=> $requestData['order'][0]['dir'],
			'sort' 			=> $requestData['order'][0]['column'],
			'start' 		=> $requestData['start'],
			'limit' 		=> $requestData['length']
		);
		$totalFiltered = $this->employeeModel->getTotal($filter_data);


		$filteredData = $this->employeeModel->getAll($filter_data);
		//printr($filteredData);

		$datatable=array();
		foreach($filteredData as $result) {
			if (is_file(DIR_UPLOAD . $result->image)) {
				$image = resize($result->image, 40, 40);
			} else {
				$image = resize('no_image.png', 40, 40);
			}
			if($requestData['popup']=="true"){
				$action  = '<div class="btn-group btn-group-sm pull-right">';
				$action = 	'<a class="btn btn-sm btn-primary" href="'.previous_url().'?user_id='.$result->user_id.'" data-reload="false" data-id="'.$result->user_id.'" data-name="'.$result->employee_name.'">Select</a>';
				$action .= '</div>';
			}else{
				$action  = '<div class="btn-group btn-group-sm pull-right">';
				$action .= 		'<a class="btn btn-sm btn-primary" href="'.admin_url('employee/edit/'.$result->user_id).'"><i class="fas fa-pencil-alt"></i></a>';
				$action .=		'<a class="btn-sm btn btn-danger btn-remove" href="'.admin_url('employee/delete/'.$result->user_id).'" onclick="return confirm(\'Are you sure?\') ? true : false;"><i class="fas fa-trash"></i></a>';
				$action .= '</div>';
			}


			if ($requestData['popup'] == "true") {
				$datatable[] = array(
					'<img src="'.$image.'" alt="'.$result->employee_name.'" class="img-fluid" />',
					$result->employee_name,
					$result->card_no,
					$action
				);
			}else{
				$datatable[]=array(
					'<div class="checkbox checkbox-primary checkbox-single">
						<input type="checkbox" name="selected[]" value="'.$result->user_id.'" />
						<label></label>
					</div>',
					'<img src="'.$image.'" alt="'.$result->employee_name.'" class="img-fluid" />',
					$result->employee_name,
					$result->card_no,
					$result->mobile,
					$result->enabled ? 'Enable':'Disable',
					$action
				);
			}

		}
		//printr($datatable);
		$json_data = array(
			"draw"            => isset($requestData['draw']) ? intval( $requestData['draw'] ):1,
			"recordsTotal"    => intval( $totalData ),
			"recordsFiltered" => intval( $totalFiltered ),
			"data"            => $datatable
		);

		return $this->response->setContentType('application/json')
            ->setJSON($json_data);  // send data as json format
	}

	public function add(){
		$this->template->set_meta_title(lang('Employee.heading_title'));

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){

			$this->employeeModel->addEmployee($this->request->getPost());

			$this->session->setFlashdata('message', 'Employee Saved Successfully.');
			return redirect()->to(admin_url('employee'));

		}
		$this->getForm();
	}

	public function edit(){

		$this->template->set_meta_title(lang('Employee.heading_title'));

		if ($this->request->getMethod('REQUEST_METHOD') === 'POST' && $this->validateForm()){
			$employee_id=$this->uri->getSegment(4);
			$this->employeeModel->editEmployee($employee_id,$this->request->getPost());

			$this->session->setFlashdata('message', 'Employee Updated Successfully.');
			return redirect()->to(admin_url('employee'));
		}
		$this->getForm();
	}

	public function delete(){
		if ($this->request->getPost('selected')){
         $selected = $this->request->getPost('selected');
      }else{
         $selected = (array) $this->uri->getSegment(4);
       }
		$this->employeeModel->deleteEmployee($selected);
		$this->session->setFlashdata('message', 'Employee deleted Successfully.');
		return redirect()->to(admin_url('employee'));
	}

	protected function getList() {

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Employee.heading_title'),
			'href' => admin_url('employee')
		);

		$this->template->add_package(array('datatable','select2','toastr'),true);


		$data['add'] = admin_url('employee/add');
		$data['delete'] = admin_url('employee/delete');
		$data['datatable_url'] = admin_url('employee/search');

		$data['heading_title'] = lang('Employee.heading_title');

		$data['text_list'] = lang('Employee.text_list');
		$data['text_no_results'] = lang('Employee.text_no_results');
		$data['text_confirm'] = lang('Employee.text_confirm');

		$data['column_employeename'] = lang('Employee.column_employeename');
		$data['column_status'] = lang('Employee.column_status');
		$data['column_date_added'] = lang('Employee.column_date_added');
		$data['column_action'] = lang('Employee.column_action');

		$data['button_add'] = lang('Employee.button_add');
		$data['button_edit'] = lang('Employee.button_edit');
		$data['button_delete'] = lang('Employee.button_delete');

		if(isset($this->error['warning'])){
			$data['error'] 	= $this->error['warning'];
		}

		if ($this->request->getPost('selected')) {
			$data['selected'] = (array)$this->request->getPost('selected');
		} else {
			$data['selected'] = array();
		}

		$data['branches']=(new BranchModel())->getAll();
		$data['departments']=(new DepartmentModel())->getAll();
		$data['designations']=(new DesignationModel())->getAll();

		$data['emp_sample']=upload_url('files/samples/employee-sample.xlsx');

		$data['popup']=$this->request->getGet('popup');

		return $this->template->view('Admin\Employee\Views\employee', $data);
	}

	protected function getForm(){

		$this->template->add_package(array('ckeditor','ckfinder','colorbox','select2','datepicker','timepicker'),true);

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => lang('Employee.heading_title'),
			'href' => admin_url('employee')
		);

		$data['breadcrumbs'][] = array(
			'text' => lang('Employee.text_add'),
			'href' => admin_url('employee/add')
		);

		$_SESSION['isLoggedIn'] = true;

		$data['heading_title'] 	= lang('Employee.heading_title');

		$data['text_form'] = $this->uri->getSegment(4) ? lang('Employee.text_edit') : lang('Employee.text_add');
		$data['button_save'] = lang('Employee.button_save');
		$data['button_cancel'] = lang('Employee.button_cancel');
		$data['text_image'] =lang('Employee.text_image');
		$data['text_none'] = lang('Employee.text_none');
		$data['text_clear'] = lang('Employee.text_clear');
		$data['cancel'] = admin_url('employee');

		if(isset($this->error['warning']))
		{
			$data['error'] 	= $this->error['warning'];
		}
		$id=$this->uri->getSegment(4);
		if ($id && ($this->request->getMethod('REQUEST_METHOD') != 'POST')) {
			$employee_info = $this->employeeModel->getEmployee($id);
		}

		//printr($employee_info);
		$tables=['user','employee','employee_office','employee_time','employee_shift'];

		foreach($tables as $table) {
			foreach($this->employeeModel->getFieldNames($table) as $field) {
				if($this->request->getPost($field)) {
					$data[$field] = $this->request->getPost($field);
				} else if(isset($employee_info->{$field}) && $employee_info->{$field}) {
					$data[$field] = $employee_info->{$field};
				} else {
					$data[$field] = '' || 0;
				}
			}
		}

		if ($this->request->getPost('image') && is_file(DIR_UPLOAD . $this->request->getPost('image'))) {
			$data['thumb_image'] = resize($this->request->getPost('image'), 100, 100);
		} elseif (!empty($empoffice_info) && is_file(DIR_UPLOAD . $empoffice_info->image)) {
			$data['thumb_image'] = resize($empoffice_info->image, 100, 100);
		} else {
			$data['thumb_image'] = resize('no_image.png', 100, 100);
		}

		if ($this->request->getPost('signature') && is_file(DIR_UPLOAD . $this->request->getPost('signature'))) {
			$data['thumb_sign'] = resize($this->request->getPost('signature'), 100, 100);
		} elseif (!empty($empoffice_info) && is_file(DIR_UPLOAD . $empoffice_info->signature)) {
			$data['thumb_sign'] = resize($empoffice_info->signature, 100, 100);
		} else {
			$data['thumb_sign'] = resize('no_image.png', 100, 100);
		}
		$data['no_image'] = resize('no_image.png', 100, 100);

		if ($this->request->getPost('second_week_off')) {
			$data['second_week_off'] = $this->request->getPost('second_week_off');
		} elseif (!empty($empoffice_info)) {
			$data['second_week_off'] = (array)json_decode($empoffice_info->second_week_off,true);
		} else {
			$data['second_week_off'] = array();
		}

		if (!empty($empoffice_info) && !empty($empoffice_info->shift_apply_date)) {
			$dayDifference = (new DateTime())->diff((new DateTime($empoffice_info->shift_apply_date))->modify('+1 day'))->format('%a');

			$data['shift_remain'] = $dayDifference;
		} else {
			$data['shift_remain'] = 0;
		}

		//branch
		$data['branches']=(new BranchModel())->getAll();
		$data['departments']=(new DepartmentModel())->getAll();
		$data['categories']=(new CategoryModel())->getAll();
		$data['sections']=(new SectionModel())->getAll();
		$data['grades']=(new GradeModel())->getAll();
		$data['designations']=(new DesignationModel())->getAll();
		$data['hods']=(new HodModel())->getAll();
		$data['shifts']=(new ShiftModel())->getAll();
		$data['banks']=(new BankModel())->getAll();
		$data['locations']=(new LocationModel())->getAll();

		$data['shift_patterns']=array_merge([''=>'Select Pattern'],generatePattern($data['shifts']));
		$data['weeks']=array(
			"none"=>"None",
			"sun"=>"Sunday",
			"mon"=>"Monday",
			"tues"=>"Tuesday",
			"wed"=>"Wednesday",
			"thurs"=>"Thursday",
			"fri"=>"Friday",
			"sat"=>"Satarday"
		);

		$data['blood_groups']=array(
			"A-Positive"=>"A+",
			"A-Negative"=>"A-",
			"B-Positive"=>"B+",
			"B-Negative"=>"B-",
			"AB-Positive"=>"AB+",
			"AB-Negative"=>"AB-",
			"O-Positive"=>"O+",
			"O-Negative"=>"O-"
		);



		echo $this->template->view('Admin\Employee\Views\employeeForm',$data);
	}

	public function uploademp(){
		$json = $this->request->getBody();

        // Decode the JSON data into an array
        $sheets = json_decode($json, true);
		
		foreach($sheets as $sheet){

			$department_name = trim($sheet[8]);
			$department_id = 0;
			if ($department_name) {
				$dep = (new DepartmentModel())->where('name', $department_name)->first();
				if ($dep) {
					$department_id = $dep->id;
				} else {
					$department_data = array(
						'name' => $department_name,
						'code' => 'DEP_' . time()
					);
					$department_id = (new DepartmentModel())->insert($department_data);
				}
			}
			$category_name = trim($sheet[9]);
			$category_id = 0;
			if ($category_name) {
				$cat = (new CategoryModel())->where('name', $category_name)->first();
				if ($cat) {
					$category_id = $cat->id;
				} else {
					$category_data = array(
						'name' => $category_name,
						'code' => 'CAT_' . time()
					);
					$category_id = (new CategoryModel())->insert($category_data);
				}
			}
			$section = trim($sheet[10]);
			$section_id = 0;
			if ($section) {
				$sec = (new SectionModel())->where('name', $section)->first();
				if ($sec) {
					$section_id = $sec->id;
				} else {
					$section_data = array(
						'name' => $section,
						'code' => 'SEC_' . time()
					);
					$section_id = (new SectionModel())->insert($section_data);
				}
			}

			$designation_name = trim($sheet[11]);
			$designation_id = 0;
			if ($designation_name) {
				$desg = (new DesignationModel())->where('name', $designation_name)->first();
				if ($desg) {
					$designation_id = $desg->id;
				} else {
					$designation_data = array(
						'name' => $designation_name,
						'code' => 'DES_' . time()
					);
					$designation_id = (new DesignationModel())->insert($designation_data);
				}
			}

			$workorder_name=trim($sheet[12]);
			$workorder_id=0;
			if($workorder_name){
				$workorder=(new WorkorderModel())->where('name',$workorder_name)->first();
				if($workorder){
					$workorder_id=$workorder->id;
				}else{
					$workorder_data=array(
						'name'=>$workorder_name,
						'code'=>'WO_'.time()
					);
					$workorder_id=(new WorkorderModel())->insert($workorder_data);
				}
			}


			$employeeofficedata[]=array(
				"branch_id"=>$sheet[1],
				"department_id"=>$department_id,
				"category_id"=>$category_id,
				"section_id"=>$section_id,
				"designation_id"=>$designation_id,
				"workorder_id"=>$workorder_id,
				"employee_name"=>$sheet[5],
				"guardian_name"=>$sheet[6],
				"relationship"=>$sheet[7],
				"pf_no"=>$sheet[13],
				"esi"=>$sheet[14],
				"uan_no"=>$sheet[15],
				"pan"=>$sheet[16],
				"employee_type"=>$sheet[34],
				"enabled"=>1
			);


			$employeedata[]=array(
				"branch_id"=>$sheet[1],
				"card_no"=>$sheet[2],
				"paycode"=>$sheet[3],
				"safety_pass_no"=>$sheet[4],
				"dob"=>$sheet[17],
				"doj"=>$sheet[18],
				"married"=>$sheet[19],
				"blood_group"=>$sheet[20],
				"qualification"=>$sheet[21],
				"experience"=>$sheet[22],
				"sex"=>$sheet[23],
				"aadhaar"=>$sheet[24],
				"permanent"=>$sheet[25],
				"pincode"=>$sheet[26],
				"telephone"=>$sheet[27],
				"email"=>$sheet[28],
				"bank_account"=>$sheet[29],
				"ifsc"=>trim($sheet[30])
			);

			$employeshiftdata[]=array(
				"shift_type"=>$sheet[31],
				"shift_id"=>$sheet[32]
			);

			$employetimedata[]=array(
				"punches"=>$sheet[33]
			);
		}



		foreach($employeeofficedata as $key=>$emp){
			$empdata[$key]=$emp;
		}
		foreach($employeedata as $key=>$emp){
			$empdata[$key]=array_merge($empdata[$key],$emp);
		}
		foreach($employeshiftdata as $key=>$emp){
			$empdata[$key]=array_merge($empdata[$key],$emp);
		}
		foreach($employetimedata as $key=>$emp){
			$empdata[$key]=array_merge($empdata[$key],$emp);
		}


		foreach($empdata as $row=>$edata){
			$employee=$this->employeeModel->where('card_no', $edata['card_no'])->first();

			if ($employee) {
				$this->employeeModel->editEmployee($employee->user_id,$edata);
				$status="Updated";
			} else {
				$this->employeeModel->addEmployee($edata);
				$status="Added";
			}

		}

		echo json_encode(array("status"=>$status));
		exit;

	}

	/*not in used*/
	/*public function upload(){
		$json=array();
		$validationRule = [
            'bemployee' => [
                'label' => 'Employee List',
                'rules' => [
                    'uploaded[bemployee]',
                    'mime_in[bemployee,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet]',
                    'max_size[bemployee,1024]',
                    'ext_in[bemployee,xlsx]',
                ],
            ],
        ];

		if (! $this->validate($validationRule)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid file',
                'errors' => $this->validator->getErrors()
            ]);
        }else{
			$file = $this->request->getFile('bemployee');
            try {
                $reader = IOFactory::createReader('Xlsx');
                $spreadsheet = $reader->load($file);
            } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Invalid file.'
                ]);
            }
		}

		$sheetCount = $spreadsheet->getSheetCount();


		$empdata=$employeeofficedata=$employeedata=$employeshiftdata=$employetimedata=array();
		$sheet = $spreadsheet->getSheet(0);
		$highestRow  = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();
		$chunkSize = 100;
		$startRow=2;
		//ob_start();
		for ($row = $startRow; $row <= $highestRow ; $row += $chunkSize) {
			$endRow = min($row + $chunkSize - 1, $highestRow );
			$range = 'A' . $row . ':' . $highestColumn . $endRow;
			// Fetch the chunk of data as an array
			$sheetData = $sheet->rangeToArray($range, null, true, true, true);

			foreach($sheetData as $sheet){

				$department_name=trim($sheet['I']);
				$department_id=0;
				if($department_name){
					$dep=(new DepartmentModel())->where('name',$department_name)->first();
					if($dep){
						$department_id=$dep->id;
					}else{
						$department_data=array(
							'name'=>$department_name,
							'code'=>'DEP_'.time()
						);
						$department_id=(new DepartmentModel())->insert($department_data);
					}
				}
				$category_name=trim($sheet['J']);
				$category_id=0;
				if($category_name){
					$cat=(new CategoryModel())->where('name',$category_name)->first();
					if($cat){
						$category_id=$cat->id;
					}else{
						$category_data=array(
							'name'=>$category_name,
							'code'=>'CAT_'.time()
						);
						$category_id=(new CategoryModel())->insert($category_data);
					}
				}
				$section=trim($sheet['K']);
				$section_id=0;
				if($section){
					$sec=(new SectionModel())->where('name',$section)->first();
					if($sec){
						$section_id=$sec->id;
					}else{
						$section_data=array(
							'name'=>$section,
							'code'=>'SEC_'.time()
						);
						$section_id=(new SectionModel())->insert($section_data);
					}
				}

				$designation_name=trim($sheet['L']);
				$designation_id=0;
				if($designation_name){
					$desg=(new DesignationModel())->where('name',$designation_name)->first();
					if($desg){
						$designation_id=$desg->id;
					}else{
						$designation_data=array(
							'name'=>$designation_name,
							'code'=>'DG_'.time()
						);
						$designation_id=(new DesignationModel())->insert($designation_data);
					}
				}

				$workorder_name=trim($sheet['M']);
				$workorder_id=0;
				if($workorder_name){
					$workorder=(new WorkorderModel())->where('name',$workorder_name)->first();
					if($workorder){
						$workorder_id=$workorder->id;
					}else{
						$workorder_data=array(
							'name'=>$workorder_name,
							'code'=>'WO_'.time()
						);
						$workorder_id=(new WorkorderModel())->insert($workorder_data);
					}
				}


				$employeeofficedata[]=array(
					"branch_id"=>$sheet['B'],
					"department_id"=>$department_id,
					"category_id"=>$category_id,
					"section_id"=>$section_id,
					"designation_id"=>$designation_id,
					"workorder_id"=>$workorder_id,
					"employee_name"=>$sheet['E'],
					"guardian_name"=>$sheet['F'],
					"relationship"=>$sheet['G'],
					"pf_no"=>$sheet['N'],
					"esi"=>$sheet['O'],
					"uan_no"=>$sheet['P'],
					"pan"=>$sheet['Q'],
					"employee_type"=>$sheet['AI'],
					"enabled"=>1
				);


				$employeedata[]=array(
					"branch_id"=>$sheet['B'],
					"card_no"=>$sheet['C'],
					"safety_pass_no"=>$sheet['D'],
					"paycode"=>$sheet['H'],
					"dob"=>$sheet['R'],
					"doj"=>$sheet['S'],
					"married"=>$sheet['T'],
					"blood_group"=>$sheet['U'],
					"qualification"=>$sheet['V'],
					"experience"=>$sheet['W'],
					"sex"=>$sheet['X'],
					"email"=>$sheet['AC'],
					"aadhaar"=>$sheet['Y'],
					"permanent"=>$sheet['Z'],
					"pincode"=>$sheet['AA'],
					"telephone"=>$sheet['AB'],
					"bank_account"=>$sheet['AD'],
					"ifsc"=>trim($sheet['AE'])
				);

				$employeshiftdata[]=array(
					"shift_type"=>$sheet['AF'],
					"shift_id"=>$sheet['AG']
				);

				$employetimedata[]=array(
					"punches"=>$sheet['AH']
				);
			}

			foreach($employeeofficedata as $key=>$emp){
				$empdata[$key]=$emp;
			}
			foreach($employeedata as $key=>$emp){
				$empdata[$key]=array_merge($empdata[$key],$emp);
			}
			foreach($employeshiftdata as $key=>$emp){
				$empdata[$key]=array_merge($empdata[$key],$emp);
			}
			foreach($employetimedata as $key=>$emp){
				$empdata[$key]=array_merge($empdata[$key],$emp);
			}


			foreach($empdata as $row=>$edata){
				$user=$this->employeeModel->where( 'card_no', $edata['card_no'])->first();

				if ($user) {
					$this->employeeModel->editEmployee($user->id,$edata);
				} else {
					$this->employeeModel->addEmployee($edata);
				}

			}



			usleep(50000); // For example, sleep for 0.05 seconds

			// Calculate progress
			$progress = min(100, intval(($row - $startRow + $chunkSize) / ($highestRow - $startRow + $chunkSize) * 100));

			// Send progress as a JSON response
			$progressData = ['progress' => $progress];
			$jsonResponse = json_encode($progressData);
			header('Content-Type: application/json');
			echo $jsonResponse;
			ob_end_flush();
		}


		$sheetData = $sheet->toArray(null, true, true, true);
		array_shift($sheetData);

		foreach($sheetData as $sheet){

				$department_name=trim($sheet['I']);
				$department_id=0;
				if($department_name){
					$dep=(new DepartmentModel())->where('name',$department_name)->first();
					if($dep){
						$department_id=$dep->id;
					}else{
						$department_data=array(
							'name'=>$department_name,
							'code'=>'dept_'.time()
						);
						$department_id=(new DepartmentModel())->insert($department_data);
					}
				}
				$category_name=trim($sheet['J']);
				$category_id=0;
				if($category_name){
					$cat=(new CategoryModel())->where('name',$category_name)->first();
					if($cat){
						$category_id=$cat->id;
					}else{
						$category_data=array(
							'name'=>$category_name,
							'code'=>'cat_'.time()
						);
						$category_id=(new CategoryModel())->insert($category_data);
					}
				}
				$section=trim($sheet['K']);
				$section_id=0;
				if($section){
					$sec=(new SectionModel())->where('name',$section)->first();
					if($sec){
						$section_id=$sec->id;
					}else{
						$section_data=array(
							'name'=>$section,
							'code'=>'sec_'.time()
						);
						$section_id=(new SectionModel())->insert($section_data);
					}
				}

				$designation_name=trim($sheet['L']);
				$designation_id=0;
				if($designation_name){
					$desg=(new DesignationModel())->where('name',$designation_name)->first();
					if($desg){
						$designation_id=$desg->id;
					}else{
						$designation_data=array(
							'name'=>$designation_name,
							'code'=>'desg_'.time()
						);
						$designation_id=(new DesignationModel())->insert($designation_data);
					}
				}
				$ifsc=trim($sheet['AE']);

				$employeeofficedata[]=array(
					"branch_id"=>$sheet['B'],
					"card_no"=>$sheet['C'],
					"safety_pass_no"=>$sheet['D'],
					"employee_name"=>$sheet['E'],
					"guardian_name"=>$sheet['F'],
					"relationship"=>$sheet['G'],
					"paycode"=>$sheet['H'],
					"department_id"=>$department_id,
					"category_id"=>$category_id,
					"section_id"=>$section_id,
					"designation_id"=>$designation_id,
					"pf_no"=>$sheet['M'],
					"esi"=>$sheet['N'],
					"pan"=>$sheet['O'],
					"employee_type"=>$sheet['AG'],
					"enabled"=>1
				);


				$employeedata[]=array(
					"dob"=>$sheet['P'],
					"doj"=>$sheet['Q'],
					"married"=>$sheet['R'],
					"bg"=>$sheet['S'],
					"qualification"=>$sheet['T'],
					"experience"=>$sheet['U'],
					"sex"=>$sheet['V'],
					"email"=>$sheet['W'],
					"aadhar"=>$sheet['X'],
					"permanent"=>$sheet['Y'],
					"pincode"=>$sheet['Z'],
					"telephone"=>$sheet['AA'],
					"bank_account"=>$sheet['AB'],
					"ifsc"=>$ifsc
				);

				$employeshiftdata[]=array(
					"shift_type"=>$sheet['AD'],
					"shift"=>$sheet['AE']
				);

				$employetimedata[]=array(
					"punches"=>$sheet['AF']
				);

		}

		$sheetData = $spreadsheet->getActiveSheet()->toArray();
		foreach($employeeofficedata as $key=>$emp){
			$empdata[$key]=$emp;
		}
		foreach($employeedata as $key=>$emp){
			$empdata[$key]=array_merge($empdata[$key],$emp);
		}
		foreach($employeshiftdata as $key=>$emp){
			$empdata[$key]=array_merge($empdata[$key],$emp);
		}
		foreach($employetimedata as $key=>$emp){
			$empdata[$key]=array_merge($empdata[$key],$emp);
		}


		foreach($empdata as $row=>$edata){
			$user=$this->employeeModel->where( 'card_no', $edata['card_no'])->first();

			if ($user) {
				$this->employeeModel->editEmployee($user->id,$edata);
			} else {
				$this->employeeModel->addEmployee($edata);
			}

			// Calculate progress percentage
			$progress = ($row - 1) / count($empdata) * 100;

			// Add progress data to array
			$data['progress'] = $progress;

			// Send progress to client-side using JSON
			echo json_encode($data);

			// Flush the output buffer to send immediately
			ob_flush();
			flush();
		}
		//echo "ok";
		$json= array(
			'success'=>'Employee Upload successfully',
			'redirect'=>admin_url('employee')
		);

		echo json_encode($json);
    	exit;
	}*/


	protected function validateForm() {
        $validation =  \Config\Services::validation();
        $id=$this->uri->getSegment(4);
        $rules = $this->employeeModel->validationRules;

        if ($this->validate($rules)){
            return true;
        }
        else{
            //printr($validation->getErrors());
            $this->error['warning']="Warning: Please check the form carefully for errors!";
            return false;
        }
        return !$this->error;
	}



}
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */