<?php
namespace App\Controllers;

use Admin\Branch\Models\BranchModel;
use Admin\Employee\Models\EmployeeModel;
use Admin\Machine\Models\MachineModel;
use Admin\Punch\Models\PunchHistoryModel;
use Admin\Punch\Models\PunchModel;
use Admin\Punch\Models\RawPunchModel;
use App\Jobs\ProcessPunchDataJob;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Queue\Queue;
use DateTime;

class Api extends ResourceController
{
	use ResponseTrait;
	private $user;

	public function __construct(){
		helper("aio");
		$this->user=service('user');
	}

	public function index(){
		$data['message'] = "Welcome to Stechnotools";
		return $this->respond($data);
	}

	public function create()
    {
        // Parse the request body to get the raw data
        $rawdata = $this->request->getBody();
        log_message('info', var_export($rawdata, true));
        $punch_data = json_decode($rawdata, true);

        if ($punch_data) {
            // Define the batch size
            $batchSize = 1000;
            $chunks = array_chunk($punch_data, $batchSize);

            foreach ($chunks as $chunk) {
                // Enqueue a job for each batch
				service('queue')->push('punch_data', new ProcessPunchDataJob($chunk));
				service('queue')->push('queueName', 'jobName', ['array' => 'parameters']);

            }

            return $this->respond(["success" => "Data processing tasks have been queued successfully"]);
        } else {
            return $this->failNotFound("No data");
        }
    }

	public function rawpunch()
	{
		$json = [];
		$rawdata = $this->request->getBody();
		$punch_data = json_decode($rawdata, true);

		if ($punch_data) {
			$batchSize = 500; // Adjust batch size as needed

			$totalRecords = count($punch_data);
			$batches = ceil($totalRecords / $batchSize);

			for ($i = 0; $i < $batches; $i++) {
				$batchStart = $i * $batchSize;
				$batchEnd = min(($i + 1) * $batchSize, $totalRecords);
				$batch = array_slice($punch_data, $batchStart, $batchEnd - $batchStart);
				// Process the batch
				$this->processBatch($batch);
			}

			$json['success'] = "success";
		} else {
			$json['success'] = "No data";
		}

		return $this->respond($json);
	}

	private function processBatch($batch){
		log_message('info', 'pucnchdata:'.json_encode($batch));
		$rawpunchModel = new RawPunchModel();
		$employeeModel = new EmployeeModel();
		$punchModel = new PunchModel();
		$punchHistoryModel = new PunchHistoryModel();
		$machineModel = new MachineModel();

		foreach ($batch as $punch) {
			$slno = trim($punch['SlNo']);
			$machine_id = trim($punch['MachineNo']);
			$device_user_id = trim($punch['CardNo']);
			$recordTime = trim($punch['PunchDateTime']);
			$recordTime = DateTime::createFromFormat('d/m/Y H:i:s', $recordTime);
			$punchDateTime = $recordTime->format('Y-m-d H:i:s');

			$punch_date = $recordTime->format('Y-m-d');
			$punch_time = $recordTime->format('H:i:s');

			$machine = $machineModel->where('code', (int)$machine_id)->first();

			if ($machine) {
				$branch_id = $machine->branch_id;

				$rawdata = [
					'slno' => $slno,
					'device_user_id' => $device_user_id,
					'punchtime' => $punchDateTime,
					'machine_id' => $machine_id,
					'branch_id' => $branch_id
				];

				$checkdata = $rawpunchModel->where($rawdata)->first();
				$lastquery= $rawpunchModel->getLastQuery();
				$errorMessage = 'Raw punch last query: ' . $lastquery;
				log_message('error', $errorMessage);
				if (empty($checkdata)) {
					$rawpunchModel->insert($rawdata);
				} else {
					$rawpunchModel->update($checkdata->id, $rawdata);
					$lastquery= $rawpunchModel->getLastQuery();
					$errorMessage = 'Raw punch update last query: ' . $lastquery;
					//$errorMessage = 'Raw punch data already exists: ' . var_export($checkdata, true);
					log_message('error', $errorMessage);
				}

				$employee = $employeeModel->where(['paycode' => $device_user_id, 'branch_id' => $branch_id])->first();

				if (!$employee) {
					$errorMessage = "device_user_id is: " . $device_user_id . " for branch id: " . $branch_id . " not assigned to any user";
					log_message('error', $errorMessage);

					$employee = $employeeModel->where('paycode', $device_user_id)->first();

					if ($employee && count($employee) == 1) {
						$user_id = $employee->user_id;
						$errorMessage = "Other Branch User with user_id " . $user_id;
						log_message('error', $errorMessage);
					} else {
						$user_id = 0;
					}
				} else {
					$user_id = $employee->user_id;
				}

				if ($user_id) {
					$pdata = [
						'user_id' => $user_id,
						'paycode' => $device_user_id,
						'machine_id' => $machine_id,
						'branch_id' => $branch_id,
						'punch_date' => $punch_date,
						'punch_time' => $punch_time,
						'punch_type' => 'A'
					];

					$singledata = $punchModel->where(['user_id' => $user_id, 'punch_date' => $punch_date, 'branch_id' => $branch_id])->first();

					if (empty($singledata)) {
						$punch_id = $this->savePunch($pdata);
					} else {
						$punch_id = $singledata->id;
						$this->savePunch($pdata, $punch_id);
					}

					$singlepdata = $punchHistoryModel->where(['punch_id' => $punch_id, 'punch_date' => $punch_date, 'punch_time' => $punch_time, 'branch_id' => $branch_id])->first();

					if (empty($singlepdata)) {
						$this->savePunchHistory($pdata, $punch_id);
					} else {
						$this->savePunchHistory($pdata, $punch_id, $singlepdata->id);
					}
				} else {
					$errorMessage = "No User: No user found for this device_user_id: " . $device_user_id;
					log_message('error', $errorMessage);
				}
			} else {
				$dbquery=$machineModel->getLastQuery();
				$errorMessage = "Sync failed due to branch not assigned to device for machine id: " . $machine_id;
				log_message('error', $errorMessage);
				log_message('error', $dbquery);
			}
		}
	}

	public function rawpunch_old(){
		$json=[];
		$rawdata = $this->request->getBody();
		log_message('info', var_export($rawdata, true));
		$punch_data = json_decode($rawdata, true);

		if($punch_data){
			$rawpunchModel = new RawPunchModel();
			$employeeModel = new EmployeeModel();
			$punchModel = new PunchModel();
			$punchHistoryModel = new PunchHistoryModel();
			$machineModel = new MachineModel();
			foreach($punch_data as $punch){
				$slno=trim($punch['SlNo']);
				$machine_id=trim($punch['MachineNo']);
				$device_user_id=trim($punch['CardNo']);
				$recordTime=trim($punch['PunchDateTime']);
				$recordTime = DateTime::createFromFormat('d/m/Y H:i:s', $recordTime);
				$punchDateTime=$recordTime->format('Y-m-d H:i:s');

				$punch_date=$recordTime->format('Y-m-d');
				$punch_time=$recordTime->format('H:i:s');

				$machine=$machineModel->where('id', $machine_id)->first();

				if($machine){

					$branch_id=$machine->branch_id;
					$rawdata=array(
						'slno'=>$slno,
						'device_user_id'=>$device_user_id,
						'punchtime'=>$punchDateTime,
						'machine_id'=>$machine_id,
						'branch_id'=>$branch_id
					);
					$checkdata=$rawpunchModel->where($rawdata)->first();
					if(empty($checkdata)){
						$rawpunchModel->insert($rawdata);
					}else{
						$errorMessage = 'Raw punch data already exits: ' . var_export($checkdata, true);
						log_message('error', $errorMessage);
					}

					$employee=$employeeModel->where(['paycode'=>$device_user_id,'branch_id'=>$branch_id])->first();

					if(!$employee){
						$errorMessage="device_user_id is: ".$device_user_id. " for branch id: ".$branch_id. "  not assign to any user ";
						log_message('error', $errorMessage);

						$employee=$employeeModel->where('paycode', $device_user_id)->first();

						if($employee && count($employee)==1){
							$user_id=$employee->user_id;
							$errorMessage = "Other Branch User with user_id".$user_id;
							log_message('error', $errorMessage);
						}else{
							$user_id=0;
						}
					}else{
						$user_id=$employee->user_id;
					}

					if($user_id){

						$pdata=array(
							'user_id'=>$user_id,
							'paycode'=>$device_user_id,
							'machine_id'=>$machine_id,
							'branch_id' => $branch_id,
							'punch_date'=>$punch_date,
							'punch_time'=>$punch_time,
							'punch_type'=>'A'
						);
						$singledata=$punchModel->where(['user_id'=>$user_id,'punch_date'=>$punch_date,'branch_id'=>$branch_id])->first();

						if(empty($singledata)){
							$punch_id=$this->savePunch($pdata);
						}else{
							$punch_id=$singledata->id;
							$this->savePunch($pdata,$punch_id);
						}

						$singlepdata=$punchHistoryModel->where(['punch_id'=>$punch_id,'punch_date'=>$punch_date,'punch_time'=>$punch_time,'branch_id'=>$branch_id])->first();
						if(empty($singlepdata)){
							$this->savePunchHistory($pdata,$punch_id);
						}else{
							$this->savePunchHistory($pdata,$punch_id,$singlepdata->id);
						}
					}else{
						$errorMessage = "No Userr: No user found for this device_user_id: ".$device_user_id;
						log_message('error', $errorMessage);
					}

				}else{

					$errorMessage = "Sync fail due to branch not assign to device for machine id: ".$machine_id;
					log_message('error', $errorMessage);

				}

			}

			$json['success']="success";
		}else{
			$json['success']="No data";
		}

		return $this->respond($json);
	}

	public function savePunch($data,$punch_id=0){
		$punchModel=new PunchModel();

		$employeedata=(new EmployeeModel())->getEmployee($data['user_id']);
		$shiftdata=(new EmployeeModel())->getEmployeeShift($data['user_id']);
		$timedata=(new EmployeeModel())->getEmployeeTime($data['user_id']);

		$punch_date=date("Y-m-d",strtotime($data['punch_date']));
		$punch_data=array(
			'user_id'=>$data['user_id'],
			'employee_name'=>$employeedata->employee_name,
			'paycode'=>$employeedata->paycode,
			'card_no'=>$employeedata->card_no,
			'mdevice_id'=>isset($data['mdevice_id'])?$data['mdevice_id']:"",
			'machine_id'=>$data['machine_id'],
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
			'late_arrival'=>$timedata->perm_late,
			'early_departure'=>$timedata->perm_early,
			'total_punch'=>$timedata->punches,
			'punch_date'=>$punch_date,
			'status'=>1
		);
		if($punch_id){
			$punchModel->update($punch_id,$punch_data);
		}else{
			$punch_id=$punchModel->insert($punch_data);
		}

		return $punch_id;

	}


	public function savePunchHistory($data,$punch_id,$punch_history_id=0){
		$punchHistoryModel=new PunchHistoryModel();
		$timedata=(new EmployeeModel())->getEmployeeTime($data['user_id']);
		$employeedata=(new EmployeeModel())->getEmployee($data['user_id']);

		$no_of_punch=$punchHistoryModel->where(['punch_id'=>$punch_id])-> countAllResults();
		if($timedata->punches==-1){
			$punch_status=1;
		}else if($no_of_punch<=$timedata->punches){
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
			'machine_id'=>$data['machine_id'],
			'mdevice_id'=>(int)isset($data['mdevice_id'])?$data['mdevice_id']:"",
			'longitude'=>isset($data['longitude'])?$data['longitude']:"",
			'latitude'=>isset($data['latitude'])?$data['latitude']:"",
			'no_of_punch'=>$no_of_punch,
			'punch_status'=>$punch_status
		);
		if($punch_history_id){
			$punchHistoryModel->update($punch_history_id,$punch_history);
		}else{
			$punch_history_id=$punchHistoryModel->insert($punch_history);
		}

	}

}
