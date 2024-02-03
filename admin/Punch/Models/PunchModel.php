<?php
namespace Admin\Punch\Models;
use CodeIgniter\Model;

class PunchModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'punch';
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

		'user_id' => array(
			'field' => 'user_id',
			'label' => 'Paycode',
			'rules' => 'trim|required|max_length[100]'
		),

		'punch_date' => array(
			'field' => 'punch_date',
			'label' => 'Punch Date',
			'rules' => "trim|required"
		),
		'punch_time' => array(
			'field' => 'punch_time',
			'label' => 'Punch Time',
			'rules' => "trim|required"
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
        $builder=$this->db->table("{$this->table} p");
		$this->filter($builder,$data);

        $builder->select("p.*,(select min(ph.punch_time) from punch_history ph where p.id=ph.punch_id) as intime, (select max(ph.punch_time) from punch_history ph where p.id=ph.punch_id) as outtime,(select count(ph.id) from punch_history ph where p.id=ph.punch_id) as total");

        if (isset($data['sort']) && $data['sort']) {
            $sort = $data['sort'];
        } else {
            $sort = "p.paycode";
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
        $builder=$this->db->table("{$this->table} p");
        $this->filter($builder,$data);
        $count = $builder->countAllResults();
        return $count;
    }

    private function filter($builder,$data){
        //$builder->where($this->deletedField, null);
        if (!empty($data['filter_search'])) {
            $builder->where("
				p.paycode LIKE '%{$data['filter_search']}%')"
            );
        }
    }

    public function addPunch($data) {
		//printr($_POST);
		//exit;
		$userdata=array(
			"user_group_id"=>4,
			"firstname"=>$data['punch_name'],
			"lastname"=>'',
			"image"=>$data['image'],
			"email"=>$data['email'],
			"phone"=>$data['telephone'],
			"address"=>$data['permanent'],
			"country_id"=>'',
			"state_id"=>'',
			"city_id"=>'',
			"branch_id"=>$data['branch_id'],
			"zip"=>$data['pincode'],
			"username"=>$data['paycode'],
			"password"=>md5('1234'),
			"show_password"=>'1234',
			"enabled"=>$data['enabled']
		);
        $this->db->table('user')->insert($userdata);
      	$user_id=$this->db->insertID() ;
		$punchdata=array(
			"user_id"=>$user_id,
			"branch_id"=>$data['branch_id'],
			"dob"=>$data['dob'],
			"doj"=>$data['doj'],
			"married"=>$data['married'],
			"bg"=>$data['bg'],
			"qualification"=>$data['qualification'],
			"experience"=>$data['experience'],
			"sex"=>$data['sex'],
			"email"=>$data['email'],
			"bus_route"=>$data['bus_route'],
			"vehicle"=>$data['vehicle'],
			"eid_no"=>$data['eid_no'],
			"permanent"=>$data['permanent'],
			"pincode"=>$data['pincode'],
			"telephone"=>$data['telephone'],
			"temporary"=>$data['temporary'],
			"temp_pin"=>$data['temp_pin'],
			"temp_tel"=>$data['temp_tel'],
			"bank_id"=>$data['bank_id'],
			"bank_account"=>$data['bank_account'],
			"eid_time"=>$data['eid_time'],
			"eid_name"=>$data['eid_name'],
			"aadhar"=>$data['aadhar'],
			"mobile"=>$data['mobile'],
			"imei"=>$data['imei'],
			"mobile_make"=>$data['mobile_make'],
			"mobile_model"=>$data['mobile_model'],
			"mobile_os"=>$data['mobile_os'],
			"mobile_operator"=>$data['mobile_operator'],
			"geofence"=>$data['geofence'],
		);
        $this->db->table('punch')->insert($punchdata);
		//echo $this->db->last_query();
		$punchofficedata=array(
			"user_id"=>$user_id,
			"branch_id"=>$data['branch_id'],
			"card_no"=>$data['card_no'],
			"punch_name"=>$data['punch_name'],
			"guardian_name"=>$data['guardian_name'],
			"relationship"=>$data['relationship'],
			"paycode"=>$data['paycode'],
			"department_id"=>$data['department_id'],
			"category_id"=>$data['category_id'],
			"section_id"=>$data['section_id'],
			"grade_id"=>$data['grade_id'],
			"designation_id"=>$data['designation_id'],
			"hod_id"=>$data['hod_id'],
			"image"=>$data['image'],
			"signature"=>$data['signature'],
			"pf_no"=>$data['pf_no'],
			"esi"=>$data['esi'],
			"pan"=>$data['pan'],
			"leaving_date"=>$data['leaving_date'],
			"reason"=>$data['reason'],
			"punch_type"=>$data['punch_type'],
		);
		$this->db->table('punch_office')->insert($punchofficedata);
		$employetimedata=array(
			"user_id"=>$user_id,
			"branch_id"=>$data['branch_id'],
			"perm_late"=>$data['perm_late'],
			"perm_early"=>$data['perm_early'],
			"max_work"=>$data['max_work'],
			"out_dura"=>$data['out_dura'],
			"out_freq"=>$data['out_freq'],
			"clock_work"=>isset($data['clock_work'])?1:0,
			"time_loss"=>isset($data['time_loss'])?1:0,
			"half_markting"=>isset($data['half_markting'])?1:0,
			"short_markting"=>isset($data['short_markting'])?1:0,
			"punches"=>$data['punches'],
			"single_punch"=>isset($data['single_punch'])?$data['single_punch']:0,
			"overtime_app"=>isset($data['overtime_app'])?1:0,
			"overstay_app"=>isset($data['overstay_app'])?1:0,
			"halfday_late"=>isset($data['halfday_late'])?1:0,
			"late_utility"=>isset($data['late_utility'])?1:0,
			"rate_hour"=>$data['rate_hour'],
			"overstay_min"=>isset($data['overstay_min'])?1:0,
			"half_late"=>$data['half_late'],
			"half_early"=>$data['half_early'],
		);
        $this->db->table('punch_time')->insert($employetimedata);

		$shift_apply_date=date('Y-m-d', strtotime('+' . $data['shift_remain'] . ' day'));

		$employeshiftdata=array(
			"user_id"=>$user_id,
			"branch_id"=>$data['branch_id'],
			"shift_type"=>$data['shift_type'],
			"shift_id"=>isset($data['shift_id'])?$data['shift_id']:'',
			"shift_pattern"=>isset($data['shift_pattern'])?$data['shift_pattern']:'',
			"run_auto_shift"=>isset($data['run_auto_shift'])?1:0,
			"shift_apply_date"=>$shift_apply_date,
			"shift_remain"=>$data['shift_remain'],
			"shift_change"=>$data['shift_change'],
			"first_week"=>$data['first_week'],
			"second_week"=>$data['second_week'],
			"second_wo"=>$data['second_wo'],
			"half_day"=>isset($data['half_day'])?$data['half_day']:'',
			"second_week_off"=>isset($data['second_week_off'])?json_encode($data['second_week_off']):'',
		);
        $this->db->table('punch_shift')->insert($employeshiftdata);
		return $user_id;
	}

	public function getShiftCount($user_id,$duration){
		$bulider=$this->db->table("{$this->table} p");
		$bulider->select("p.shift_id");
		$bulider->where("user_id",$user_id);
		$bulider->where("punch_date>=DATE_SUB(CURDATE(), INTERVAL ".$duration." DAY");
		$bulider->groupBy("shift_id");
		$res=$bulider->get();
		return $res;
	}

	public function getPunchHistory($user_id,$punch_date){
		$bulider=$this->db->table("punch_history ph");
		$bulider->select("ph.*");
		$bulider->where("DATE_FORMAT(punch_date, '%d-%m-%Y')='".$punch_date."'");
		$bulider->where("user_id",$user_id);
		$res=$bulider->get()->getResultArray();
		return $res;
	}

	public function getTotalPunchByPunchId($punch_id){
		// using ci4 builder for count
		$bulider=$this->db->table("punch_history ph");
		$bulider->where("punch_id",$punch_id);
		$count = $bulider->countAllResults();
		return $count+1;

	}

	public function savePunchHistory($punch_history){
		$this->db->table('punch_history')->insert($punch_history);
		return $this->db->insertID();
	}

	public function deletePunchHistory($id){
		$this->db->table('punch_history')->where('id', $id)->delete();
	}

}
