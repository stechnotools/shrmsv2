<?php
namespace Admin\Employee\Models;
use CodeIgniter\Model;

class EmployeeModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'employee';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'object';
    protected $useSoftDelete        = false;
    protected $protectFields        = false;
    protected $allowedFields        = [];

    // Dates
    protected $useTimestamps        = true;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'employee_name' => array(
            'field' => 'employee_name',
            'label' => 'Employee Name',
            'rules' => 'trim|required|max_length[100]'
        ),
        'paycode' => array(
            'field' => 'paycode',
            'label' => 'paycode',
            'rules' => 'trim|required|max_length[100]'
        ),
        'branch_id' => array(
            'field' => 'branch_id',
            'label' => 'Branch Name',
            'rules' => 'trim|required'
        ),
        'dob' => array(
            'field' => 'dob',
            'label' => 'Date of Birth',
            'rules' => 'trim|required'
        ),
        'doj' => array(
            'field' => 'doj',
            'label' => 'Date of Joining',
            'rules' => 'trim|required'
        ),
        'telephone' => array(
            'field' => 'telephone',
            'label' => 'Telephone',
            'rules' => 'trim|required'
        ),
        'sex' => array(
            'field' => 'sex',
            'label' => 'Gender',
            'rules' => 'trim|required'
        ),

    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks       = true;
    protected $beforeInsert         = [];
    protected $afterInsert          = [];
    protected $beforeUpdate         = [];
    protected $afterUpdate          = [];
    protected $beforeFind           = [];
    protected $afterFind            = [];
    protected $beforeDelete         = [];
    protected $afterDelete          = [];


    public function __construct(){
		parent::__construct();
	}

    public function getAll($data = array()){
        $builder=$this->db->table("{$this->table} e");
        $this->filter($builder,$data);

        $builder->select("*");

        if (isset($data['sort']) && $data['sort']) {
            $sort = $data['sort'];
        } else {
            $sort = "e.id";
        }

        if (isset($data['order']) && ($data['order'] == 'desc')) {
            $order = "desc";
        } else {
            $order = "asc";
        }
        $builder->orderBy($sort, $order);

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 10;
            }
            $builder->limit((int)$data['limit'],(int)$data['start']);
        }

        $res = $builder->get()->getResult();
        //echo $this->db->getLastQuery();
        return $res;
    }

    public function getTotal($data = array()) {
        $builder=$this->db->table("{$this->table} e");
        $this->filter($builder,$data);
        $count = $builder->countAllResults();
        return $count;
    }

    private function filter($builder,$data){
        $builder->join('user u', 'e.user_id = u.id','left');
        $builder->join('employee_office eo','e.user_id = eo.user_id','left');

		if(!empty($data['branch_id'])){
			$builder->whereIn("eo.branch_id",$data['branch_id']);
		}
		if(!empty($data['department_id'])){
			$builder->whereIn("eo.department_id",$data['department_id']);
		}
		if(!empty($data['designation_id'])){
			$builder->whereIn("eo.designation_id",$data['designation_id']);
		}
		if(isset($data['status'])){
			$builder->where("u.enabled",$data['status']);
		}
        $builder->where("u.{$this->deletedField}", null);
        if (!empty($data['filter_search'])) {
            $builder->where("
				name LIKE '%{$data['filter_search']}%'
				OR code LIKE '%{$data['filter_search']}%'
				OR short LIKE '%{$data['filter_search']}%')"
            );
        }
    }

    public function addEmployee($data) {
		$builder = $this->db->table("user");
		$userdata = array(
			"user_group_id" => 4,
			"name" => isset($data['employee_name']) ? $data['employee_name'] : '',
			"image" => isset($data['image']) ? $data['image'] : '',
			"email" => isset($data['email']) ? $data['email'] : '',
			"phone" => isset($data['telephone']) ? $data['telephone'] : '',
			"address" => isset($data['permanent']) ? $data['permanent'] : '',
			"country_id" => '', // You might add isset check for these as well
			"state_id" => '',   // depending on their source
			"city_id" => '',    // or existence
			"branch_id" => isset($data['branch_id']) ? $data['branch_id'] : '',
			"zip" => isset($data['pincode']) ? $data['pincode'] : '',
			"username" => isset($data['paycode']) ? $data['paycode'] : '',
			"password" => md5('1234'), // Consider adjusting password assignment
			"show_password" => '1234', // if you're hashing differently
			"enabled" => isset($data['enabled']) ? $data['enabled'] : ''
		);
        $builder->insert($userdata);
      	$user_id=$this->db->insertID() ;

		$builder = $this->db->table("employee");
		$employeedata = array(
			"user_id" => $user_id,
			"branch_id" => isset($data['branch_id']) ? $data['branch_id'] : '',
			"card_no" => isset($data['card_no']) ? $data['card_no'] : '',
			"dob" => isset($data['dob']) ? $data['dob'] : '',
			"doj" => isset($data['doj']) ? $data['doj'] : '',
			"married" => isset($data['married']) ? $data['married'] : '',
			"bg" => isset($data['bg']) ? $data['bg'] : '',
			"qualification" => isset($data['qualification']) ? $data['qualification'] : '',
			"experience" => isset($data['experience']) ? $data['experience'] : '',
			"sex" => isset($data['sex']) ? $data['sex'] : '',
			"email" => isset($data['email']) ? $data['email'] : '',
			"bus_route" => isset($data['bus_route']) ? $data['bus_route'] : '',
			"vehicle" => isset($data['vehicle']) ? $data['vehicle'] : '',
			"eid_no" => isset($data['eid_no']) ? $data['eid_no'] : '',
			"permanent" => isset($data['permanent']) ? $data['permanent'] : '',
			"pincode" => isset($data['pincode']) ? $data['pincode'] : '',
			"telephone" => isset($data['telephone']) ? $data['telephone'] : '',
			"temporary" => isset($data['temporary']) ? $data['temporary'] : '',
			"temp_pin" => isset($data['temp_pin']) ? $data['temp_pin'] : '',
			"temp_tel" => isset($data['temp_tel']) ? $data['temp_tel'] : '',
			"bank_id" => isset($data['bank_id']) ? $data['bank_id'] : '',
			"bank_account" => isset($data['bank_account']) ? $data['bank_account'] : '',
			"eid_time" => isset($data['eid_time']) ? $data['eid_time'] : '',
			"eid_name" => isset($data['eid_name']) ? $data['eid_name'] : '',
			"aadhar" => isset($data['aadhar']) ? $data['aadhar'] : '',
			"mobile" => isset($data['mobile']) ? $data['mobile'] : '',
			"imei" => isset($data['imei']) ? $data['imei'] : '',
			"mobile_make" => isset($data['mobile_make']) ? $data['mobile_make'] : '',
			"mobile_model" => isset($data['mobile_model']) ? $data['mobile_model'] : '',
			"mobile_os" => isset($data['mobile_os']) ? $data['mobile_os'] : '',
			"mobile_operator" => isset($data['mobile_operator']) ? $data['mobile_operator'] : '',
			"geofence" => isset($data['geofence']) ? $data['geofence'] : '',
		);

        $builder->insert($employeedata);

		$builder = $this->db->table("employee_office");
		$employeeofficedata = array(
			"user_id" => $user_id,
			"branch_id" => isset($data['branch_id']) ? $data['branch_id'] : '',
			"card_no" => isset($data['card_no']) ? $data['card_no'] : '',
			"safety_pass_no" => isset($data['safety_pass_no']) ? $data['safety_pass_no'] : '',
			"employee_name" => isset($data['employee_name']) ? $data['employee_name'] : '',
			"guardian_name" => isset($data['guardian_name']) ? $data['guardian_name'] : '',
			"relationship" => isset($data['relationship']) ? $data['relationship'] : '',
			"paycode" => isset($data['paycode']) ? $data['paycode'] : '',
			"department_id" => isset($data['department_id']) ? $data['department_id'] : '',
			"category_id" => isset($data['category_id']) ? $data['category_id'] : '',
			"section_id" => isset($data['section_id']) ? $data['section_id'] : '',
			"grade_id" => isset($data['grade_id']) ? $data['grade_id'] : '',
			"designation_id" => isset($data['designation_id']) ? $data['designation_id'] : '',
			"hod_id" => isset($data['hod_id']) ? $data['hod_id'] : '',
			"image" => isset($data['image']) ? $data['image'] : '',
			"signature" => isset($data['signature']) ? $data['signature'] : '',
			"pf_no" => isset($data['pf_no']) ? $data['pf_no'] : '',
			"esi" => isset($data['esi']) ? $data['esi'] : '',
			"pan" => isset($data['pan']) ? $data['pan'] : '',
			"leaving_date" => isset($data['leaving_date']) ? $data['leaving_date'] : '',
			"reason" => isset($data['reason']) ? $data['reason'] : '',
			"employee_type" => isset($data['employee_type']) ? $data['employee_type'] : '',
		);

		$builder->insert($employeeofficedata);

		$builder = $this->db->table("employee_time");
		$employetimedata = array(
			"user_id" => $user_id,
			"branch_id" => isset($data['branch_id']) ? $data['branch_id'] : '',
			"perm_late" => isset($data['perm_late']) ? $data['perm_late'] : '',
			"perm_early" => isset($data['perm_early']) ? $data['perm_early'] : '',
			"max_work" => isset($data['max_work']) ? $data['max_work'] : '',
			"out_dura" => isset($data['out_dura']) ? $data['out_dura'] : '',
			"out_freq" => isset($data['out_freq']) ? $data['out_freq'] : '',
			"clock_work" => isset($data['clock_work']) ? 1 : 0,
			"time_loss" => isset($data['time_loss']) ? 1 : 0,
			"half_markting" => isset($data['half_markting']) ? 1 : 0,
			"short_markting" => isset($data['short_markting']) ? 1 : 0,
			"punches" => isset($data['punches']) ? $data['punches'] : '',
			"single_punch" => isset($data['single_punch']) ? $data['single_punch'] : 0,
			"overtime_app" => isset($data['overtime_app']) ? 1 : 0,
			"overstay_app" => isset($data['overstay_app']) ? 1 : 0,
			"halfday_late" => isset($data['halfday_late']) ? 1 : 0,
			"late_utility" => isset($data['late_utility']) ? 1 : 0,
			"rate_hour" => isset($data['rate_hour']) ? $data['rate_hour'] : '',
			"overstay_min" => isset($data['overstay_min']) ? 1 : 0,
			"half_late" => isset($data['half_late']) ? $data['half_late'] : '',
			"half_early" => isset($data['half_early']) ? $data['half_early'] : '',
		);

        $builder->insert($employetimedata);

		$builder = $this->db->table("employee_shift");
		$shift_remain=isset($data['shift_remain']) ? $data['shift_remain']:0;
		$shift_apply_date=date('Y-m-d', strtotime('+' . $shift_remain . ' day'));
		$employeshiftdata = array(
			"user_id" => $user_id,
			"branch_id" => isset($data['branch_id']) ? $data['branch_id'] : '',
			"shift_type" => isset($data['shift_type']) ? $data['shift_type'] : '',
			"shift_id" => isset($data['shift_id']) ? $data['shift_id'] : '',
			"shift_pattern" => isset($data['shift_pattern']) ? $data['shift_pattern'] : '',
			"run_auto_shift" => isset($data['run_auto_shift']) ? 1 : 0,
			"shift_apply_date" => $shift_apply_date,
			"shift_remain" => isset($data['shift_remain']) ? $data['shift_remain'] : '',
			"shift_change" => isset($data['shift_change']) ? $data['shift_change'] : '',
			"first_week" => isset($data['first_week']) ? $data['first_week'] : '',
			"second_week" => isset($data['second_week']) ? $data['second_week'] : '',
			"second_wo" => isset($data['second_wo']) ? $data['second_wo'] : '',
			"half_day" => isset($data['half_day']) ? $data['half_day'] : '',
			"second_week_off" => isset($data['second_week_off']) ? json_encode($data['second_week_off']) : '',
		);

        $builder->insert($employeshiftdata);
		return $user_id;
	}

	public function editEmployee($user_id, $data) {
		$builder = $this->db->table("user");
		$userdata = array(
			"user_group_id" => 4,
			"name" => isset($data['employee_name']) ? $data['employee_name'] : '',
			"image" => isset($data['image']) ? $data['image'] : '',
			"email" => isset($data['email']) ? $data['email'] : '',
			"phone" => isset($data['telephone']) ? $data['telephone'] : '',
			"address" => isset($data['permanent']) ? $data['permanent'] : '',
			"country_id" => '',
			"state_id" => '',
			"city_id" => '',
			"branch_id" => isset($data['branch_id']) ? $data['branch_id'] : '',
			"zip" => isset($data['pincode']) ? $data['pincode'] : '',
			"username" => isset($data['paycode']) ? $data['paycode'] : '',
			"password" => md5('1234'),
			"show_password" => '1234',
			"enabled" => isset($data['enabled']) ? $data['enabled'] : '',
			"updated_at" => date("Y-m-d")
		);

		$builder->where("id",$user_id);
		$builder->update($userdata);

		$builder = $this->db->table("employee");
		$builder->where("user_id", $user_id);
		$builder->delete();

		$employeedata = array(
			"user_id" => $user_id,
			"branch_id" => isset($data['branch_id']) ? $data['branch_id'] : '',
			"card_no" => isset($data['card_no']) ? $data['card_no'] : '',
			"dob" => isset($data['dob']) ? $data['dob'] : '',
			"doj" => isset($data['doj']) ? $data['doj'] : '',
			"married" => isset($data['married']) ? $data['married'] : '',
			"bg" => isset($data['bg']) ? $data['bg'] : '',
			"qualification" => isset($data['qualification']) ? $data['qualification'] : '',
			"experience" => isset($data['experience']) ? $data['experience'] : '',
			"sex" => isset($data['sex']) ? $data['sex'] : '',
			"email" => isset($data['email']) ? $data['email'] : '',
			"bus_route" => isset($data['bus_route']) ? $data['bus_route'] : '',
			"vehicle" => isset($data['vehicle']) ? $data['vehicle'] : '',
			"eid_no" => isset($data['eid_no']) ? $data['eid_no'] : '',
			"permanent" => isset($data['permanent']) ? $data['permanent'] : '',
			"pincode" => isset($data['pincode']) ? $data['pincode'] : '',
			"telephone" => isset($data['telephone']) ? $data['telephone'] : '',
			"temporary" => isset($data['temporary']) ? $data['temporary'] : '',
			"temp_pin" => isset($data['temp_pin']) ? $data['temp_pin'] : '',
			"temp_tel" => isset($data['temp_tel']) ? $data['temp_tel'] : '',
			"bank_id" => isset($data['bank_id']) ? $data['bank_id'] : '',
			"bank_account" => isset($data['bank_account']) ? $data['bank_account'] : '',
			"eid_time" => isset($data['eid_time']) ? $data['eid_time'] : '',
			"eid_name" => isset($data['eid_name']) ? $data['eid_name'] : '',
			"aadhar" => isset($data['aadhar']) ? $data['aadhar'] : '',
			"mobile" => isset($data['mobile']) ? $data['mobile'] : '',
			"imei" => isset($data['imei']) ? $data['imei'] : '',
			"mobile_make" => isset($data['mobile_make']) ? $data['mobile_make'] : '',
			"mobile_model" => isset($data['mobile_model']) ? $data['mobile_model'] : '',
			"mobile_os" => isset($data['mobile_os']) ? $data['mobile_os'] : '',
			"mobile_operator" => isset($data['mobile_operator']) ? $data['mobile_operator'] : '',
			"geofence" => isset($data['geofence']) ? $data['geofence'] : '',
		);

		$builder->insert($employeedata);


		$builder = $this->db->table("employee_office");
		$builder->where("user_id", $user_id);
		$builder->delete();

		$employeeofficedata = array(
			"user_id" => $user_id,
			"branch_id" => isset($data['branch_id']) ? $data['branch_id'] : '',
			"card_no" => isset($data['card_no']) ? $data['card_no'] : '',
			"safety_pass_no" => isset($data['safety_pass_no']) ? $data['safety_pass_no'] : '',
			"employee_name" => isset($data['employee_name']) ? $data['employee_name'] : '',
			"guardian_name" => isset($data['guardian_name']) ? $data['guardian_name'] : '',
			"relationship" => isset($data['relationship']) ? $data['relationship'] : '',
			"paycode" => isset($data['paycode']) ? $data['paycode'] : '',
			"department_id" => isset($data['department_id']) ? $data['department_id'] : '',
			"category_id" => isset($data['category_id']) ? $data['category_id'] : '',
			"section_id" => isset($data['section_id']) ? $data['section_id'] : '',
			"grade_id" => isset($data['grade_id']) ? $data['grade_id'] : '',
			"designation_id" => isset($data['designation_id']) ? $data['designation_id'] : '',
			"hod_id" => isset($data['hod_id']) ? $data['hod_id'] : '',
			"image" => isset($data['image']) ? $data['image'] : '',
			"signature" => isset($data['signature']) ? $data['signature'] : '',
			"pf_no" => isset($data['pf_no']) ? $data['pf_no'] : '',
			"esi" => isset($data['esi']) ? $data['esi'] : '',
			"pan" => isset($data['pan']) ? $data['pan'] : '',
			"leaving_date" => isset($data['leaving_date']) ? $data['leaving_date'] : '',
			"reason" => isset($data['reason']) ? $data['reason'] : '',
			"employee_type" => isset($data['employee_type']) ? $data['employee_type'] : '',
		);

		$builder->insert($employeeofficedata);

		$builder = $this->db->table("employee_time");
		$builder->where("user_id", $user_id);
		$builder->delete();

		$employetimedata = array(
			"user_id" => $user_id,
			"branch_id" => isset($data['branch_id']) ? $data['branch_id'] : '',
			"perm_late" => isset($data['perm_late']) ? $data['perm_late'] : '',
			"perm_early" => isset($data['perm_early']) ? $data['perm_early'] : '',
			"max_work" => isset($data['max_work']) ? $data['max_work'] : '',
			"out_dura" => isset($data['out_dura']) ? $data['out_dura'] : '',
			"out_freq" => isset($data['out_freq']) ? $data['out_freq'] : '',
			"clock_work" => isset($data['clock_work']) ? 1 : 0,
			"time_loss" => isset($data['time_loss']) ? 1 : 0,
			"half_markting" => isset($data['half_markting']) ? 1 : 0,
			"short_markting" => isset($data['short_markting']) ? 1 : 0,
			"punches" => isset($data['punches']) ? $data['punches'] : '',
			"single_punch" => isset($data['single_punch']) ? $data['single_punch'] : 0,
			"overtime_app" => isset($data['overtime_app']) ? 1 : 0,
			"overstay_app" => isset($data['overstay_app']) ? 1 : 0,
			"halfday_late" => isset($data['halfday_late']) ? 1 : 0,
			"late_utility" => isset($data['late_utility']) ? 1 : 0,
			"rate_hour" => isset($data['rate_hour']) ? $data['rate_hour'] : '',
			"overstay_min" => isset($data['overstay_min']) ? 1 : 0,
			"half_late" => isset($data['half_late']) ? $data['half_late'] : '',
			"half_early" => isset($data['half_early']) ? $data['half_early'] : '',
		);


		$builder->insert($employetimedata);

		$shift_remain=isset($data['shift_remain']) ? $data['shift_remain']:0;
		$shift_apply_date=date('Y-m-d', strtotime('+' . $shift_remain . ' day'));

		$builder = $this->db->table("employee_shift");
		$builder->where("user_id", $user_id);
		$builder->delete();

		$employeshiftdata = array(
			"user_id" => $user_id,
			"branch_id" => isset($data['branch_id']) ? $data['branch_id'] : '',
			"shift_type" => isset($data['shift_type']) ? $data['shift_type'] : '',
			"shift_id" => isset($data['shift_id']) ? $data['shift_id'] : '',
			"shift_pattern" => isset($data['shift_pattern']) ? $data['shift_pattern'] : '',
			"run_auto_shift" => isset($data['run_auto_shift']) ? 1 : 0,
			"shift_apply_date" => $shift_apply_date,
			"shift_remain" => isset($data['shift_remain']) ? $data['shift_remain'] : '',
			"shift_change" => isset($data['shift_change']) ? $data['shift_change'] : '',
			"first_week" => isset($data['first_week']) ? $data['first_week'] : '',
			"second_week" => isset($data['second_week']) ? $data['second_week'] : '',
			"second_wo" => isset($data['second_wo']) ? $data['second_wo'] : '',
			"half_day" => isset($data['half_day']) ? $data['half_day'] : '',
			"second_week_off" => isset($data['second_week_off']) ? json_encode($data['second_week_off']) : '',
		);

		$builder->insert($employeshiftdata);

	}

    public function getEmployee($user_id){
        $builder=$this->db->table("{$this->table} e");
        $builder->join('user u', 'e.user_id = u.id','left');
        $builder->join('employee_office eo','e.user_id = eo.user_id','left');
        $builder->join('employee_time et','e.user_id = et.user_id','left');
        $builder->join('employee_shift es','e.user_id = es.user_id','left');
        $builder->join('branch b', 'eo.branch_id = b.id','left');
        $builder->join('department d', 'eo.department_id = d.id','left');
        $builder->join('designation ds', 'eo.designation_id = ds.id','left');

        $builder->select("*,b.name as branch,d.name as department,ds.name as designation");
		$builder->where("e.user_id",$user_id);
        $res = $builder->get()->getRow();
		//echo $this->db->getLastQuery();
		return $res;
    }

	public function deleteEmployee($user_id) {
		$builder = $this->db->table("{$this->table}");
		$builder->whereIn("user_id", $user_id);
		$builder->delete();
	}


}
