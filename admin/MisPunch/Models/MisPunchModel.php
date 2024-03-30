<?php
namespace Admin\MisPunch\Models;
use CodeIgniter\Model;

class MisPunchModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'mispunch_request';
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
    protected $skipValidation       = true;
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

	public function getMisPunch($data=array()){
		$builder = $this->db->table('mispunch_request mr');
		$builder->join('employee e', 'e.user_id = mr.user_id');
		$builder->join('employee_office eo', 'eo.user_id = mr.user_id');
		$builder->select('mr.id,mr.user_id,mr.punch_date,mr.clm_in,mr.clm_out,mr.savior_in,mr.savior_out,mr.is_request,e.card_no,eo.employee_name');
		if(isset($data['branch_id']) && $data['branch_id']){
			$builder->where("mr.branch_id", $data['branch_id']);
		}
		if(isset($data['user_id']) && $data['user_id']){
			$builder->where("mr.user_id", $data['user_id']);
		}
		if(isset($data['status']) && $data['status']){
			$builder->where("mr.is_request", $data['status']);
		}

		$query = $builder->get();
		return $query->getResultArray();

	}

	public function getCLMAttendance($data=array()){
		//printr($data);
		//exit;
		$sql="SELECT *
		FROM (
			SELECT
			  e.user_id,
			  e.card_no,
			  e.employee_name,
			  e.branch_name,
			  e.designation_name,
			  e.department_name,
			  e.safety_pass_no,
			  e.device_access,
			  ds.date AS punch_date,
			  clm.clm_in,
			  clm.clm_out,
			  savior.savior_in,
			  savior.savior_out,
			  mr.is_request,
			  CASE
				WHEN e.device_access = 'savior' THEN
					CASE
						WHEN IFNULL(savior.savior_in, '') IN ('', '00:00:00') AND IFNULL(savior.savior_out, '') IN ('', '00:00:00') THEN 'A'
						WHEN IFNULL(savior.savior_in, '') != '' AND IFNULL(savior.savior_out, '') != '' THEN 'P'
						ELSE 'MM'
					END
				WHEN e.device_access = 'clm' THEN
					CASE
						WHEN IFNULL(clm.clm_in, '') IN ('', '00:00:00') AND IFNULL(clm.clm_out, '') IN ('', '00:00:00') THEN 'A'
						WHEN IFNULL(clm.clm_in, '') != '' AND IFNULL(clm.clm_out, '') != '' THEN 'P'
						ELSE 'MM'
					END
				WHEN e.device_access = 'both' THEN
					CASE
						WHEN (IFNULL(savior.savior_in, '') IN ('', '00:00:00') AND IFNULL(savior.savior_out, '') IN ('', '00:00:00'))
							AND (IFNULL(clm.clm_in, '') IN ('', '00:00:00') AND IFNULL(clm.clm_out, '') IN ('', '00:00:00')) THEN 'A'
						WHEN (IFNULL(savior.savior_in, '') != '' AND IFNULL(savior.savior_out, '') != '')
							AND (IFNULL(clm.clm_in, '') != '' AND IFNULL(clm.clm_out, '') != '') THEN 'P'
						ELSE 'MM'
					END
				ELSE ''
			END AS status
			FROM
			  (SELECT
				  e.user_id,
				  eo.employee_name,
				  e.branch_id,
				  b.name AS branch_name,
				  eo.designation_id,
				  d.name AS designation_name,
				  eo.department_id,
				  d1.name AS department_name,
				  e.card_no,
				  e.safety_pass_no,
				  et.device_access
				FROM employee e
				LEFT JOIN branch b ON e.branch_id = b.id
				LEFT JOIN employee_office eo ON e.user_id = eo.user_id
				LEFT JOIN employee_time et ON e.user_id = et.user_id
				LEFT JOIN designation d ON eo.designation_id = d.id
				LEFT JOIN department d1 ON eo.department_id = d1.id
			  ) e
			JOIN
			  (SELECT DATE_ADD('".$data['fromdate']."', INTERVAL seq.seq DAY) AS date
			   FROM (
				   SELECT (t3.i * 100 + t2.i * 10 + t1.i) AS seq
				   FROM (SELECT 0 AS i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) AS t1
				   CROSS JOIN (SELECT 0 AS i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) AS t2
				   CROSS JOIN (SELECT 0 AS i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) AS t3
				   WHERE DATE_ADD('".$data['fromdate']."', INTERVAL (t3.i * 100 + t2.i * 10 + t1.i) DAY) <= '".$data['todate']."'
			   ) AS seq) AS ds
			LEFT JOIN
			  (SELECT
				  mh.user_id,
				  mh.punch_date,
				  MIN(mh.punch_time) AS clm_in,
				  MAX(mh.punch_time) AS clm_out
				FROM mainpunch_history mh
				GROUP BY mh.user_id, mh.punch_date
			  ) clm ON clm.user_id = e.user_id AND clm.punch_date = ds.date
			LEFT JOIN
			  (SELECT
				  mh.user_id,
				  mh.punch_date,
				  MIN(mh.punch_time) AS savior_in,
				  MAX(mh.punch_time) AS savior_out
				FROM punch_history mh
				GROUP BY mh.user_id, mh.punch_date
			  ) savior ON savior.user_id = e.user_id AND savior.punch_date = ds.date
			  LEFT JOIN mispunch_request mr
				ON mr.user_id = e.user_id
				AND mr.punch_date = ds.date
			WHERE 1=1";
			if(isset($data['branch_id']) && $data['branch_id']){
				$sql.=" AND e.branch_id = ".$data['branch_id'];
			}
			if(isset($data['user_id']) && $data['user_id']){
				$sql.=" AND e.user_id = ".$data['user_id'];
			}
		$sql.=" ) AS subquery
		WHERE
		  status = 'MM'";
		//echo $sql;
		return $this->db->query($sql)->getResultArray();

	}



}
