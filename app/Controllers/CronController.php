<?php

namespace App\Controllers;

use Admin\Employee\Models\EmployeeModel;
use Admin\Localisation\Models\BlockModel;
use Admin\Localisation\Models\DistrictModel;
use Admin\Localisation\Models\GrampanchayatModel;
use Admin\MainPunch\Models\MainPunchHistoryModel;
use Admin\MainPunch\Models\MainPunchModel;
use Admin\MainPunch\Models\MainRawPunchModel;
use App\Controllers\BaseController;
use Mobile\Forms\Models\FieldvisitModel;
use Mobile\Forms\Models\GpsModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CronController extends BaseController
{
    public function clmpunch()
    {
        $filename="clmpunch.xlsx";
       
        $filePath = DIR_UPLOAD . 'files/clmpunch/' . $filename; 
       
        if(file_exists($filePath)){
            $spreadsheet = IOFactory::load($filePath);
            $sheetCount = $spreadsheet->getSheetCount();
            // Process each sheet
            for ($i = 0; $i < $sheetCount; $i++) {
                $sheet = $spreadsheet->getSheet($i);
                $sheetName = $sheet->getTitle();
                $sheetData = $sheet->toArray();
				$queuename=preg_replace('/[^A-Za-z0-9]/', '', $sheetName);
                $this->processSheetData($sheetName, $sheetData,$queuename);
				//$this->scheduleJob($queuename);         
            }
            //unlink($filePath);
            unlink($filePath);
        }else{
            echo "File not found";
        }
        
    }

    private function processSheetData($sheetName, $sheetData,$queuename)
    {
        $mainrawpunchModel = new MainRawPunchModel();
		$mainpunchModel = new MainPunchModel();
		$mainpunchHistoryModel= new MainPunchHistoryModel();
        $employeeModel=new EmployeeModel();

        
        $punchdate=date('Y-m-d',strtotime($sheetName));

        array_shift($sheetData);
        foreach($sheetData as $sheet){

			$safety_pass_no = trim($sheet[0]);
			$flag=	trim($sheet[3]);
			$shift = trim($sheet[4]);
			$punch_date = $punchdate;
			$punch_time = date('H:i:s', strtotime($sheet[5]));
			$location = trim($sheet[7]);


			$employee=$employeeModel->where('safety_pass_no', $safety_pass_no)->first();
			if($employee){
				$employeoffice=$employeeModel->getEmployee($employee->user_id);
				$machinerawpunch=[
					'user_id'=>$employeoffice->user_id,
					'branch_id'=>$employeoffice->branch_id,
					'safety_pass_no'=>$safety_pass_no,
					'punch_date'=>$punch_date,
					'punch_time'=>$punch_time,
					'flag'=>$flag,
					'shift'=>$employeoffice->shift_id,
					'department_id'=>$employeoffice->department_id,
					'location'=>$location
				];

				$checkdata = $mainrawpunchModel->where($machinerawpunch)->first();
				$lastquery= $mainrawpunchModel->getLastQuery();

				$errorMessage = 'Raw punch last query: ' . $lastquery;
				log_message('error', $errorMessage);
				if (empty($checkdata)) {
					$mainrawpunchModel->insert($machinerawpunch);
				} else {
					$mainrawpunchModel->update($checkdata->id, $machinerawpunch);
					$lastquery= $mainrawpunchModel->getLastQuery();
					$errorMessage = 'Raw punch update last query: ' . $lastquery;
					log_message('error', $errorMessage);
				}

				$pdata = [
					'user_id' => $employeoffice->user_id,
					'paycode' => $employeoffice->paycode,
					'branch_id' => $employeoffice->branch_id,
					'punch_date' => $punch_date,
					'punch_time' => $punch_time,
					'punch_type' => 'A'
				];

				$singledata = $mainpunchModel->where(['user_id' => $employeoffice->user_id, 'punch_date' => $punch_date, 'branch_id' => $employeoffice->branch_id])->first();

				if (empty($singledata)) {
					$punch_id = $this->saveMainPunch($pdata);
				} else {
					$punch_id = $singledata->id;
					$this->saveMainPunch($pdata, $punch_id);
				}

				$singlepdata = $mainpunchHistoryModel->where(['punch_id' => $punch_id, 'punch_date' => $punch_date, 'punch_time' => $punch_time, 'branch_id' => $employeoffice->branch_id])->first();

				if (empty($singlepdata)) {
					$this->saveMainPunchHistory($pdata, $punch_id);
				} else {
					$this->saveMainPunchHistory($pdata, $punch_id, $singlepdata->id);
				}

			}

		}
    }

    public function saveMainPunch($data,$punch_id=0){
		$mainpunchModel=new MainPunchModel();

		$employeedata=(new EmployeeModel())->getEmployee($data['user_id']);
		$shiftdata=(new EmployeeModel())->getEmployeeShift($data['user_id']);
		$timedata=(new EmployeeModel())->getEmployeeTime($data['user_id']);

		$punch_date=date("Y-m-d",strtotime($data['punch_date']));
		$punch_data=array(
			'user_id'=>$data['user_id'],
			'employee_name'=>$employeedata->employee_name,
			'paycode'=>$employeedata->paycode,
			'branch_id'=>$employeedata->branch_id,
			'branch_name'=>$employeedata->branch_name,
			'department_id'=>$employeedata->department_id,
			'department_name'=>$employeedata->department_name,
			'category_id'=>$employeedata->category_id,
			'category_name'=>$employeedata->category_name,
			'section_id'=>$employeedata->section_id,
			'section_name'=>$employeedata->section_name,
			'grade_id'=>$employeedata->grade_id,
			'grade_name'=>$employeedata->grade_name,
			'designation_id'=>$employeedata->designation_id,
			'designation_name'=>$employeedata->designation_name,
			'shift_type'=>$employeedata->shift_type,
			'shift_id'=>$shiftdata->shift_id,
			'shift_name'=>$shiftdata->shift_name,
			'shift_pattern'=>$shiftdata->shift_pattern,
			'shift_start_time'=>$shiftdata->shift_start_time,
			'shift_end_time'=>$shiftdata->shift_end_time,
			'auto_shift'=>$shiftdata->run_auto_shift,
			'first_week'=>$shiftdata->first_week,
			'second_week'=>$shiftdata->second_week,
			'late_arrival'=>$timedata ? ($timedata->perm_late ?? '00:00:00') : '00:00:00',
			'early_departure'=>$timedata ? ($timedata->perm_early ?? '00:00:00') : '00:00:00',
			'total_punch'=>$timedata ? ($timedata->punches ?? 0) : 0,
			'punch_date'=>$punch_date,
			'status'=>1
		);
		if($punch_id){
			$mainpunchModel->update($punch_id,$punch_data);
		}else{
			$punch_id=$mainpunchModel->insert($punch_data);
		}

		return $punch_id;

	}

	public function saveMainPunchHistory($data,$punch_id,$punch_history_id=0){
		$mainpunchHistoryModel=new MainPunchHistoryModel();
		$timedata=(new EmployeeModel())->getEmployeeTime($data['user_id']);
		$employeedata=(new EmployeeModel())->getEmployee($data['user_id']);

		$no_of_punch=$mainpunchHistoryModel->where(['punch_id'=>$punch_id])-> countAllResults();
		$punches=$timedata?($timedata->punches ?? 0):0;
		if($punches==-1){
			$punch_status=1;
		}else if($no_of_punch<=($punches)){
			$punch_status=1;
		}else{
			$punch_status=0;
		}
		$punch_history=array(
			'punch_id'=>$punch_id,
			'user_id'=>$data['user_id'],
			'card_no'=>$employeedata->card_no,
			'paycode'=>$data['paycode'],
			'punch_date'=>$data['punch_date'],
			'punch_time'=>$data['punch_time'],
			'punch_type'=>$data['punch_type'],
			'branch_id'=>$data['branch_id'],
			'no_of_punch'=>$no_of_punch+1,
			'punch_status'=>$punch_status
		);
		if($punch_history_id){
			$mainpunchHistoryModel->update($punch_history_id,$punch_history);
		}else{
			$punch_history_id=$mainpunchHistoryModel->insert($punch_history);
		}

	}
     
}
