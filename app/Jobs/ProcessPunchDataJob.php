<?php

namespace App\Jobs;

use Admin\Employee\Models\EmployeeModel as ModelsEmployeeModel;
use Admin\Machine\Models\MachineModel;
use Admin\Punch\Models\PunchHistoryModel as ModelsPunchHistoryModel;
use Admin\Punch\Models\PunchModel as ModelsPunchModel;
use Admin\Punch\Models\RawPunchModel as ModelsRawPunchModel;
use CodeIgniter\Queue\BaseJob;
use CodeIgniter\Queue\Interfaces\JobInterface;
use App\Models\RawPunchModel;
use App\Models\EmployeeModel;
use App\Models\PunchModel;
use App\Models\PunchHistoryModel;
use DateTime;

class ProcessPunchDataJob extends BaseJob implements JobInterface
{
    protected $punchData;

    public function __construct(array $punchData)
    {
        $this->punchData = $punchData;
    }

    public function process()
    {
        foreach ($this->punchData as $punch) {
            $slno = trim($punch['SlNo']);
            $machine_id = trim($punch['MachineNo']);
            $device_user_id = trim($punch['CardNo']);
            $recordTime = trim($punch['PunchDateTime']);
            $recordTime = DateTime::createFromFormat('d-m-Y H:i:s', $recordTime);
            $punchDateTime = $recordTime->format('Y-m-d H:i:s');

            $punch_date = $recordTime->format('Y-m-d');
            $punch_time = $recordTime->format('H:i:s');

            $machine = (new MachineModel())->where('id', $machine_id)->first();

            if ($machine) {
                $rawpunchModel = new ModelsRawPunchModel();
                $branch_id = $machine->branch_id;
                $rawdata = array(
                    'slno' => $slno,
                    'device_user_id' => $device_user_id,
                    'punchtime' => $punchDateTime,
                    'machine_id' => $machine_id,
                    'branch_id' => $branch_id
                );
                $checkdata = $rawpunchModel->where($rawdata)->first();
                if (empty($checkdata)) {
                    $rawpunchModel->insert($rawdata);
                } else {
                    $errorMessage = 'Raw punch data already exits: ' . var_export($checkdata, true);
                    log_message('error', $errorMessage);
                }
                $employeeModel = new ModelsEmployeeModel();
                $employee = $employeeModel->where(['paycode' => $device_user_id, 'branch_id' => $branch_id])->first();

                if (!$employee) {
                    $errorMessage = "device_user_id is: " . $device_user_id . " for branch id: " . $branch_id . "  not assign to any user ";
                    log_message('error', $errorMessage);

                    $employee = $employeeModel->where('paycode', $device_user_id)->first();

                    if ($employee && count($employee) == 1) {
                        $user_id = $employee->user_id;
                        $errorMessage = "Other Branch User with user_id" . $user_id;
                        log_message('error', $errorMessage);
                    } else {
                        $user_id = 0;
                    }
                } else {
                    $user_id = $employee->user_id;
                }

                if ($user_id) {
                    $punchModel = new ModelsPunchModel();
                    $punchHistoryModel = new ModelsPunchHistoryModel();
                    $pdata = array(
                        'user_id' => $user_id,
                        'paycode' => $device_user_id,
                        'machine_id' => $machine_id,
                        'branch_id' => $branch_id,
                        'punch_date' => $punch_date,
                        'punch_time' => $punch_time,
                        'punch_type' => 'A'
                    );
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
                $errorMessage = "Sync fail due to branch not assign to device for machine id: " . $machine_id;
                log_message('error', $errorMessage);
            }
        }
    }

    protected function savePunch($data, $punch_id = 0)
    {
        $punchModel = new PunchModel();
        $employeedata = (new EmployeeModel())->getEmployee($data['user_id']);
        $shiftdata = (new EmployeeModel())->getEmployeeShift($data['user_id']);
        $timedata = (new EmployeeModel())->getEmployeeTime($data['user_id']);

        $punch_date = date("Y-m-d", strtotime($data['punch_date']));
        $punch_data = array(
            'user_id' => $data['user_id'],
            'employee_name' => $employeedata->employee_name,
            'paycode' => $employeedata->paycode,
            'card_no' => $employeedata->card_no,
            'mdevice_id' => isset($data['mdevice_id']) ? $data['mdevice_id'] : "",
            'machine_id' => $data['machine_id'],
            'branch_id' => $employeedata->branch_id,
            'branch_name' => $employeedata->branch_name,
            'department_id' => $employeedata->department_id,
            'department_name' => $employeedata->department_name,
            'category_id' => $employeedata->category_id,
            'category_name' => $employeedata->category_name,
            'section_id' => $employeedata->section_id,
            'section_name' => $employeedata->section_name,
            'grade_id' => $employeedata->grade_id,
            'grade_name' => $employeedata->grade_name,
            'designation_id' => $employeedata->designation_id,
            'designation_name' => $employeedata->designation_name,
            'shift_type' => $employeedata->shift_type,
            'shift_id' => $shiftdata->shift_id,
            'shift_name' => $shiftdata->shift_name,
            'shift_pattern' => $shiftdata->shift_pattern,
            'shift_start_time' => $shiftdata->shift_start_time,
            'shift_end_time' => $shiftdata->shift_end_time,
            'auto_shift' => $shiftdata->run_auto_shift,
            'first_week' => $shiftdata->first_week,
            'second_week' => $shiftdata->second_week,
            'late_arrival' => $timedata->perm_late,
            'early_departure' => $timedata->perm_early,
            'total_punch' => $timedata->punches,
            'punch_date' => $punch_date,
            'status' => 1
        );
        if ($punch_id) {
            $punchModel->update($punch_id, $punch_data);
        } else {
            $punch_id = $punchModel->insert($punch_data);
        }

        return $punch_id;
    }

    protected function savePunchHistory($data, $punch_id, $punch_history_id = 0)
    {
        $punchHistoryModel = new PunchHistoryModel();
        $timedata = (new EmployeeModel())->getEmployeeTime($data['user_id']);
        $employeedata = (new EmployeeModel())->getEmployee($data['user_id']);

        $no_of_punch = $punchHistoryModel->where(['punch_id' => $punch_id])->countAllResults();
        if ($timedata->punches == -1) {
            $punch_status = 1;
        } else if ($no_of_punch <= $timedata->punches) {
            $punch_status = 1;
        } else {
            $punch_status = 0;
        }
        $punch_history = array(
            'punch_id' => $punch_id,
            'user_id' => $data['user_id'],
            'card_no' => $employeedata->card_no,
            'paycode' => $data['paycode'],
            'punch_date' => $data['punch_date'],
            'punch_time' => $data['punch_time'],
            'punch_type' => $data['punch_type'],
            'branch_id' => $data['branch_id'],
            'machine_id' => $data['machine_id'],
            'mdevice_id' => (int) isset($data['mdevice_id']) ? $data['mdevice_id'] : "",
            'longitude' => isset($data['longitude']) ? $data['longitude'] : "",
            'latitude' => isset($data['latitude']) ? $data['latitude'] : "",
            'no_of_punch' => $no_of_punch,
            'punch_status' => $punch_status
        );
        if ($punch_history_id) {
            $punchHistoryModel->update($punch_history_id, $punch_history);
        } else {
            $punch_history_id = $punchHistoryModel->insert($punch_history);
        }

        return $punch_history_id;
    }
}
