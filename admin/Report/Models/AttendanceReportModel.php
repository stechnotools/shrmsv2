<?php
namespace Admin\Report\Models;
use CodeIgniter\Model;

class AttendanceReportModel extends Model
{
    protected $table = '';
    protected $DBGroup      = 'default';
    protected $allowedFields = [];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    public function __construct()
    {
        parent::__construct();
    }

    public function getMonthAttendance($data=array()){
		//printr($data);
		/*$sql="WITH
			Numbers AS (SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9),
			DateSequence AS (SELECT DATE_ADD('".$data['fromdate']."', INTERVAL (Numbers.n + Tens.n * 10) DAY) AS punch_date FROM Numbers CROSS JOIN Numbers AS Tens WHERE DATE_ADD('".$data['fromdate']."', INTERVAL (Numbers.n + Tens.n * 10) DAY) <= '".$data['todate']."')"; */
		if(isset($data['fromdate'])){
		$DateSequence="SELECT DATE_ADD('".$data['fromdate']."', INTERVAL (t0.num + t1.num * 10 + t2.num * 100) DAY) AS punch_date
		FROM (
			SELECT 0 AS num UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
		) AS t0
		CROSS JOIN (
			SELECT 0 AS num UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
		) AS t1
		CROSS JOIN (
			SELECT 0 AS num UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
		) AS t2
		WHERE DATE_ADD('".$data['fromdate']."', INTERVAL (t0.num + t1.num * 10 + t2.num * 100) DAY) BETWEEN '".$data['fromdate']."' AND '".$data['todate']."' ORDER BY punch_date ASC
		";
		$sql="SELECT
			p1.punch_id,
			ds.punch_date,
			DATE_FORMAT(ds.punch_date, '%a') AS weekday,
			e.branch_id,
			e.user_id,
			p1.no_of_punch,
			e.paycode,
			et.punches,
			e.card_no,
			eo.employee_name,
			d.name AS department_name,
			d1.name AS designation_name,
			s1.id AS shift_id,
			s1.code AS shift_name,
			s1.shift_start_time AS start,
			s1.shift_end_time AS end,
			s1.lunch_deduction,
			s1.lunch_duration,
			s1.min_absent_hrs_halfday,
			p1.startin,
			p1.lunch_out,
			p1.lunch_in,
			p1.two_out,
			p1.four_out,
			p1.startout,
			p1.late_arrival,
			p1.early_departure,
			es.first_week,
			COALESCE(p1.status, 0) AS status
		FROM ($DateSequence) ds
		CROSS JOIN employee e
		LEFT JOIN (
			SELECT
				ph.punch_id,
				p.late_arrival,
				p.early_departure,
				p.user_id,
				p.punch_date,
				p.status,
				COUNT(*) AS no_of_punch,
				p.total_punch,
				MIN(ph.punch_time) AS startin,
				MAX(CASE WHEN ph.noofpunch = 2 AND
					p.total_punch = 4 THEN ph.punch_time END) AS lunch_out,
				MAX(CASE WHEN ph.noofpunch = 3 AND
					p.total_punch = 4 THEN ph.punch_time END) AS lunch_in,
				MAX(CASE WHEN ph.noofpunch = 2 AND
					p.total_punch = 2 THEN ph.punch_time END) AS two_out,
				MAX(CASE WHEN ph.noofpunch = 4 AND
					p.total_punch = 4 THEN ph.punch_time END) AS four_out,
				CASE WHEN COUNT(ph.punch_time) > 1 THEN MAX(ph.punch_time) END AS startout

				FROM punch p
				LEFT JOIN (SELECT
					@row_number := CASE WHEN @current_group = CONCAT(t.branch_id, '_', t.user_id, '_', t.punch_date) THEN @row_number + 1 ELSE 1 END AS noofpunch,
					@current_group := CONCAT(t.branch_id, '_', t.user_id, '_', t.punch_date) AS dummy,
					t.punch_id,
					t.punch_time,
					t.punch_date,
					t.branch_id,
					t.user_id
					FROM (SELECT
						punch_id,
						punch_time,
						punch_date,
						branch_id,
						user_id,
						CASE WHEN @prev_user <> user_id OR
							@prev_branch <> branch_id OR
							@prev_date <> punch_date OR
							@base_time IS NULL OR
							TIMEDIFF(punch_time, @base_time) >= '00:05:00' THEN @base_time := punch_time END AS is_base_time,
						@prev_user := user_id,
						@prev_branch := branch_id,
						@prev_date := punch_date
					FROM punch_history p
					ORDER BY branch_id, user_id, punch_date, punch_time) t
					WHERE is_base_time IS NOT NULL) ph
					ON ph.punch_id = p.id
				WHERE p.punch_date BETWEEN '".$data['fromdate']."' AND '".$data['todate']."'
				GROUP BY p.user_id,p.punch_date
			) p1 ON e.user_id = p1.user_id AND ds.punch_date = p1.punch_date
			LEFT JOIN (SELECT
				sr.user_id,
				sr.shift_id,
				s.code shift_name,
				sr.shift_apply_date
				FROM shift_roster sr
				LEFT JOIN shift s
					ON sr.shift_id = s.id
				WHERE CAST(sr.shift_apply_date AS date) >= '".$data['fromdate']."'
				AND CAST(sr.shift_apply_date AS date) <= '".$data['todate']."') sr
				ON e.user_id = sr.user_id  AND sr.shift_apply_date=ds.punch_date
			LEFT JOIN employee_office eo ON e.user_id = eo.user_id
			LEFT JOIN employee_time et ON e.user_id = et.user_id
			LEFT JOIN employee_shift es ON e.user_id = es.user_id
			LEFT JOIN department d ON eo.department_id = d.id
			LEFT JOIN designation d1 ON eo.designation_id = d1.id
			LEFT JOIN shift s1 ON (
				(sr.shift_id IS NOT NULL AND sr.shift_id=s1.id) OR
				(sr.shift_id IS NULL AND es.shift_id = s1.id)
			)  where 1=1";
			if(isset($data['branch_id']) && !empty($data['branch_id'])){
				if(is_array($data['branch_id'])){
					$sql.=" and eo.branch_id  in(".implode(',',$data['branch_id']).")";
				}else{
					$sql.=" and eo.branch_id  = " .$data['branch_id'];
				}
			}
			if(isset($data['user_id'])){
				if(is_array($data['user_id'])){
					$sql.=" and e.user_id in(".implode(',',$data['user_id']).")";
				}else if(!empty($data['user_id'])){
					$sql.=" and e.user_id = " .$data['user_id'];
				}

			}
			if(isset($data['department_id'])){
				if(is_array($data['department_id'])){
					$sql.=" and eo.department_id in(".implode(',',$data['department_id']).")";
				}else{
					$sql.=" and eo.department_id = " .$data['department_id'];
				}
			}
			if(isset($data['category_id'])){
				if(is_array($data['category_id'])){
					$sql.=" and eo.category_id in(".implode(',',$data['category_id']).")";
				}else{
					$sql.=" and eo.category_id = " .$data['category_id'];
				}

			}
			if(isset($data['section_id'])){
				if(is_array($data['section_id'])){
					$sql.=" and eo.section_id in(".implode(',',$data['section_id']).")";
				}else{
					$sql.=" and eo.section_id = " .$data['section_id'];
				}

			}
			if(isset($data['grade_id'])){
				if (is_array($data['grade_id'])){
					$sql.=" and eo.grade_id in(".implode(',',$data['grade_id']).")";
				}else{
					$sql.=" and eo.grade_id = " .$data['grade_id'];
				}

			}
			if(isset($data['designation_id'])){
				if(is_array($data['designation_id'])){
					$sql.=" and eo.designation_id in(".implode(',',$data['designation_id']).")";
				}else{
					$sql.=" and eo.designation_id = " .$data['designation_id'];
				}

			}
			if(isset($data['shift_id'])){
				if(is_array($data['shift_id'])){
					$sql.=" and sr.shift_id in(".implode(',',$data['shift_id']).")";
				}else{
					$sql.=" and sr.shift_id = " .$data['shift_id'];
				}

			}

			if(isset($data['status'])){
				$sql.=" having status = ".$data['status'];
			}
			$sql.=" ORDER BY user_id, ds.punch_date ASC";

			return $this->db->query($sql)->getResultArray();
		}
		else{
			return array();
		}
	}

	public function getCLMAttendance($data=array()){
		$sql="SELECT
		e.user_id,
		e.card_no,
		e.employee_name,
		e.branch_name,
		e.designation_name,
		e.department_name,
		e.safety_pass_no,
		clm.clm_in,
		clm.clm_out,
		savior.savior_in,
		savior.savior_out
	  FROM (SELECT
		  e.user_id,
		  eo.employee_name,
		  e.branch_id,
		  b.name AS branch_name,
		  eo.designation_id,
		  d.name AS designation_name,
		  eo.department_id,
		  d1.name AS department_name,
		  e.card_no,
		  e.safety_pass_no
		FROM employee e
		  LEFT JOIN branch b
			ON e.branch_id = b.id
		  LEFT JOIN employee_office eo
			ON e.user_id = eo.user_id
		  LEFT JOIN designation d
			ON eo.designation_id = d.id
		  LEFT JOIN department d1
			ON eo.department_id = d1.id
		WHERE e.branch_id = '".$data['branch_id']."') e
		LEFT JOIN (SELECT
			mh.user_id,
			MIN(mh.punch_time) AS clm_in,
			MAX(mh.punch_time) AS clm_out
		  FROM mainpunch_history mh
		  WHERE date(mh.punch_date) BETWEEN '".$data['fromdate']."' AND '".$data['todate']."'
		  AND mh.branch_id = '".$data['branch_id']."'
		  GROUP BY mh.user_id
		  ORDER BY mh.punch_time ASC) clm
		  ON clm.user_id = e.user_id
		LEFT JOIN (SELECT
			mh.user_id,
			MIN(mh.punch_time) AS savior_in,
			MAX(mh.punch_time) AS savior_out
		  FROM punch_history mh
		  WHERE date(mh.punch_date) BETWEEN '".$data['fromdate']."' AND '".$data['todate']."'
		  AND mh.branch_id = '".$data['branch_id']."'
		  GROUP BY mh.user_id
		  ORDER BY mh.punch_time ASC) savior
		  ON savior.user_id = e.user_id";
		  return $this->db->query($sql)->getResultArray();

	}
}