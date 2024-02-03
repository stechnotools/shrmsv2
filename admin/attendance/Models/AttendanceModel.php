<?php
namespace Admin\Attendance\Models;
use CodeIgniter\Model;
class AttendanceModel extends Model
{
	protected $DBGroup              = 'default';
    protected $table                = 'attendance';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'object';
    protected $useSoftDelete        = true;
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
        'name' => array(
            'label' => 'Name',
            'rules' => 'trim|required|max_length[100]'
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
	public function addAttendance($data) {

		$attendancedata=array(
			"user_id"=>$data['user_id'],
			"site_attendance_check"=>isset($data['site_attendance_check'])?1:0,
			"paycode"=>$data['paycode'],
			"employee_name"=>$data['employee_name'],
			"branch_id"=>$data['branch_id'],
			"month_days"=>$data['month_days'],
			"absent_days"=>$data['absent_days'],
			"present_days"=>0,
			"pwo"=>$data['pwo'],
			"weekly_off"=>$data['weekly_off'],
			"holidays"=>$data['holidays'],
			"el"=>$data['el'],
			"cl"=>$data['cl'],
			"sl"=>$data['sl'],
			"cof"=>$data['cof'],
			"ot"=>$data['ot'],
			"arrear_days"=>$data['arrear_days'],
			"deduction_days"=>$data['deduction_days'],
			"status"=>1,
			"date_added"=>$data['date_added'],
		);
      	$this->db->insert("attendance", $attendancedata);
      	$attendance_id=$this->db->insert_id() ;

		if(isset($data['site_attendance_check'])){
			if (isset($data['site_attendance'])) {
				foreach ($data['site_attendance'] as $site_attendance) {
					$site_attendance_data=array(
						"user_id"=>$data['user_id'],
						"attendance_id"=>$attendance_id,
						"site_id"=>$site_attendance['site_id'],
						"present_days"=>$site_attendance['present_days'],
						"pwo"=>$site_attendance['pwo'],
						"arrear_days"=>$site_attendance['arrear_days'],
						"deduction_days"=>$site_attendance['deduction_days'],
						"ot"=>$site_attendance['ot'],
						"date_added"=>$data['date_added']
					);
					$this->db->insert("site_attendances", $site_attendance_data);

				}
			}
		}
		return $attendance_id;
	}
	public function editAttendance($attendance_id, $data) {

		$attendancedata=array(
			"user_id"=>$data['user_id'],
			"site_attendance_check"=>isset($data['site_attendance_check'])?1:0,
			"paycode"=>$data['paycode'],
			"employee_name"=>$data['employee_name'],
			"branch_id"=>$data['branch_id'],
			"month_days"=>$data['month_days'],
			"absent_days"=>$data['absent_days'],
			"present_days"=>0,
			"pwo"=>$data['pwo'],
			"weekly_off"=>$data['weekly_off'],
			"holidays"=>$data['holidays'],
			"el"=>$data['el'],
			"cl"=>$data['cl'],
			"sl"=>$data['sl'],
			"cof"=>$data['cof'],
			"ot"=>$data['ot'],
			"arrear_days"=>$data['arrear_days'],
			"deduction_days"=>$data['deduction_days'],
			"status"=>1,
			"date_added"=>$data['date_added'],
		);

		$this->db->where("id",$attendance_id);
      	$this->db->update("attendance", $attendancedata);

		$this->db->where("attendance_id", $attendance_id);
		$this->db->delete("site_attendances");


		if(isset($data['site_attendance_check'])){
			if (isset($data['site_attendance'])) {
				foreach ($data['site_attendance'] as $site_attendance) {
					$site_attendance_data=array(
						"user_id"=>$data['user_id'],
						"attendance_id"=>$attendance_id,
						"site_id"=>$site_attendance['site_id'],
						"present_days"=>$site_attendance['present_days'],
						"pwo"=>$site_attendance['pwo'],
						"arrear_days"=>$site_attendance['arrear_days'],
						"deduction_days"=>$site_attendance['deduction_days'],
						"ot"=>$site_attendance['ot'],
						"date_added"=>$data['date_added']
					);
					$this->db->insert("site_attendances", $site_attendance_data);

				}
			}
		}

	}
	public function getAttendances($data = array()){
		if(!empty($data['branch_id']) && !empty($data['month'])){
			$this->db->select("a.*,eo.user_id,eo.employee_name,eo.paycode");
			$this->db->from("employee_office eo");
			//$this->db->join('attendance a', 'a.user_id = eo.user_id','left');
			$sql="select * from attendance  where 1=1 ";

			if(!empty($data['month'])){
				$sql.="and DATE_FORMAT(date_added,'%m/%Y')='".$data['month']."'";
			}
			$this->db->join("($sql) a", 'a.user_id = eo.user_id','left');

			if(!empty($data['branch_id'])){
				$this->db->where("eo.branch_id",$data['branch_id']);
			}
			if (!empty($data['filter_search'])) {
				$this->db->where("
					eo.employee_name LIKE '%{$data['filter_search']}%'"
				);
			}

			if (isset($data['sort']) && $data['sort']) {
				$sort = $data['sort'];
			} else {
				$sort = "eo.employee_name";
			}
			if (isset($data['order']) && ($data['order'] == 'desc')) {
				$order = "desc";
			} else {
				$order = "asc";
			}
			$this->db->order_by($sort, $order);
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}
				if ($data['limit'] < 1) {
					$data['limit'] = 10;
				}
				$this->db->limit((int)$data['limit'],(int)$data['start']);
			}
			$res = $this->db->get()->result();
		}else{
			$res=array();
		}
		//echo $this->db->last_query();
		return $res;
	}
	public function getTotalAttendances($data = array()) {
		$this->db->select("a.*,eo.user_id,eo.employee_name,eo.paycode");
		$this->db->from("employee_office eo");
		//$this->db->join('attendance a', 'a.user_id = eo.user_id','left');
		$sql="select * from attendance  where 1=1 ";

		if(!empty($data['month'])){
			$sql.="and DATE_FORMAT(date_added,'%m/%Y')='".$data['month']."'";
		}
		$this->db->join("($sql) a", 'a.user_id = eo.user_id','left');

		if(!empty($data['branch_id'])){
			$this->db->where("eo.branch_id",$data['branch_id']);
		}
		if (!empty($data['filter_search'])) {
			$this->db->where("
				eo.employee_name LIKE '%{$data['filter_search']}%'"
			);
		}
		$count = $this->db->count_all_results();
		return $count;
	}
	public function getAttendance($attendance_id){

		$this->db->from('attendance ep');
		$this->db->where("ep.id",$attendance_id);
		$res = $this->db->get()->row();
		return $res;
	}
	public function getAttendanceByUserID($user_id,$date_added) {
		$this->db->where('user_id', $user_id);
		$this->db->where('date_added', $date_added);
		$query = $this->db->get('attendance');
		$attendance=$query->row();
		return $attendance;
	}
	public function deleteAttendance($attendance_id){
		$this->db->where_in("id", $attendance_id);
		$this->db->delete("attendance");

	}
	public function getSiteAttendances($attendance_id) {
		$this->db->from("site_attendances");
		$this->db->where("attendance_id",$attendance_id);
		$site_attendance_data = $this->db->get()->result_array();
		return $site_attendance_data;
	}

	public function getSiteAttendancesByUserID($user_id,$month) {
		$this->db->where('user_id', $user_id);
		$this->db->where("
			DATE_FORMAT(date_added, '%m/%Y') = '{$month}'"
		);
		$query = $this->db->get('attendance');
		$attendance=$query->row();
		return $attendance;
	}
}