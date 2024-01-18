<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */
class Salary_model extends MY_Model
{	
   public function __construct(){
		parent::__construct();
	}
	public function addSalary($data) {
		//printr($_POST);
		//exit;
		$salarydata=array(
			"user_id"=>$data['user_id'],
			"ctc"=>$data['ctc'],
			"basic"=>$data['basic'],
			"da"=>$data['da'],
			"hra"=>$data['hra'],
			"conveyance"=>$data['conveyance'],
			"ot"=>$data['ot'],
			"tds"=>$data['tds'],
			"emp_welfare"=>$data['emp_welfare'],
			"empr_welfare"=>$data['empr_welfare'],
			"earning"=>isset($data['earning'])?json_encode($data['earning']):'',
			"deduction"=>isset($data['deduction'])?json_encode($data['deduction']):'',
			"salary_mode"=>$data['salary_mode'],
			"bank_id"=>$data['bank_id'],
			"account_no"=>$data['account_no'],
			"l_folio_no"=>$data['l_folio_no'],
			"wo_payable"=>isset($data['wo_payable'])?1:0,
			"holiday_payable"=>isset($data['holiday_payable'])?1:0,
			"lta"=>isset($data['lta'])?1:0,
			"lic"=>isset($data['lic'])?1:0,
			"edli"=>isset($data['edli'])?1:0,
			"exgra"=>isset($data['exgra'])?1:0,
			"gratuity_add"=>isset($data['gratuity_add'])?1:0,
			"status"=>1,
			"date_added"=>date("Y-m-d"),
		);
      	$this->db->insert("employee_payroll", $salarydata);
      	$salary_id=$this->db->insert_id() ;
		return $salary_id;
	}
	public function editSalary($salary_id, $data) {
		$salarydata=array(
			"ctc"=>$data['ctc'],
			"basic"=>$data['basic'],
			"da"=>$data['da'],
			"hra"=>$data['hra'],
			"conveyance"=>$data['conveyance'],
			"ot"=>$data['ot'],
			"tds"=>$data['tds'],
			"emp_welfare"=>$data['emp_welfare'],
			"empr_welfare"=>$data['empr_welfare'],
			"earning"=>isset($data['earning'])?json_encode($data['earning']):'',
			"deduction"=>isset($data['deduction'])?json_encode($data['deduction']):'',
			"salary_mode"=>$data['salary_mode'],
			"bank_id"=>$data['bank_id'],
			"account_no"=>$data['account_no'],
			"l_folio_no"=>$data['l_folio_no'],
			"wo_payable"=>isset($data['wo_payable'])?1:0,
			"holiday_payable"=>isset($data['holiday_payable'])?1:0,
			"lta"=>isset($data['lta'])?1:0,
			"lic"=>isset($data['lic'])?1:0,
			"edli"=>isset($data['edli'])?1:0,
			"exgra"=>isset($data['exgra'])?1:0,
			"gratuity_add"=>isset($data['gratuity_add'])?1:0,
			"status"=>1,
		
		);
		$this->db->where("user_id",$salary_id);
      	$this->db->update("employee_payroll", $salarydata);
		
		
	}
	public function getSalaries($data = array()){
		$this->db->select("ep.*,eo.employee_name,eo.paycode");
		$this->db->from("employee_payroll ep");
		$this->db->join('employee_office eo', 'eo.user_id = ep.user_id','left');
		
		if (!empty($data['filter_search'])) {
			$this->db->where("
				eo.employee_name LIKE '%{$data['filter_search']}%'"				
			);
		}
		if(!empty($data['branch_id'])){
			$this->db->where("eo.branch_id",$data['branch_id']);
		}
		if(!empty($data['department_id'])){
			$this->db->where("eo.department_id",$data['department_id']);
		}
		if(!empty($data['designation_id'])){
			$this->db->where("eo.designation_id",$data['designation_id']);
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
		return $res;
	}
	public function getTotalSalaries($data = array()) {
		$this->db->from("employee_payroll ep");
		$this->db->join('employee_office eo', 'eo.user_id = ep.user_id','left');
		
		if (!empty($data['filter_search'])) {
			$this->db->where("
				eo.employee_name LIKE '%{$data['filter_search']}%'"				
			);
		}
		if(!empty($data['branch_id'])){
			$this->db->where("eo.branch_id",$data['branch_id']);
		}
		if(!empty($data['department_id'])){
			$this->db->where("eo.department_id",$data['department_id']);
		}
		if(!empty($data['designation_id'])){
			$this->db->where("eo.designation_id",$data['designation_id']);
		}
		$count = $this->db->count_all_results();
		return $count;
	}
	public function getEmployeePayroll($user_id){
		$this->db->from('employee_payroll ep');
		$this->db->where("ep.user_id",$user_id);
		$res = $this->db->get()->row();
		return $res;	
	}
	public function getEmployeeOffice($emp_id){
		$this->db->select("eo.*,
		(select c.name from branch c where c.id=eo.branch_id) as branch_name,
		(select d.name from department d where d.id=eo.department_id) as department_name,
		(select ca.name from category ca where ca.id=eo.category_id) as category_name,
		(select s.name from section s where s.id=eo.section_id) as section_name,
		(select g.name from grade g where g.id=eo.grade_id) as grade_name,
		(select d.name from designation d where d.id=eo.designation_id) as designation_name,
		");
		$this->db->from("employee_office eo");
		$this->db->where("eo.user_id",$emp_id);
		$res = $this->db->get()->row();
		return $res;	
	}
	
	public function deleteSalary($user_id){
		$this->db->where_in("user_id", $user_id);
		$this->db->delete("employee_payroll");
	}
	
	public function getSalarySheets($data = array()){

        $this->db->select("eo.user_id as euser_id,eo.branch_id,eo.employee_name,eo.paycode,ep.*,ep.earning,ep.deduction,ep.tds,a.id AS atttenace_id,a.site_attendance_check,a.ot,a.pwo,a.holidays,a.month_days,a.weekly_off,a.absent_days,a.arrear_days,a.deduction_days,(select d.name from designation d where d.id=eo.designation_id) as designation_name");
        $this->db->from("employee_office eo");
        $this->db->join('employee_payroll ep', 'eo.user_id = ep.user_id','left');
        $this->db->join("(select * from attendance WHERE DATE_FORMAT(date_added, '%m/%Y') = '".$data['month']."') a", 'eo.user_id = a.user_id','left');


		if (!empty($data['branch_id'])) {
			$this->db->where("
				eo.branch_id = '{$data['branch_id']}'"
			);
		}

		if (!empty($data['salary_mode'])) {
			$this->db->where("
				ep.salary_mode = '{$data['salary_mode']}'"
			);
		}
		
		/*if (!empty($data['month'])) {
			$this->db->where("
				DATE_FORMAT(a.date_added, '%m/%Y') = '{$data['month']}'"				
			);
		}*/
		
		if (!empty($data['user_id'])) {
			$this->db->where("
				eo.user_id = '{$data['user_id']}'"
			);
            $res = $this->db->get()->row();
		}else{
            $res = $this->db->get()->result();
        }
		

		//echo $this->db->last_query();
		return $res;
	}
	
	public function getSalarySheet($user_id,$month){
		$this->db->select("ep.*,a.*,a.id as atttenace_id, a.employee_name,a.paycode,eo.designation_id as designation_id,(select d.name from designation d where d.id=eo.designation_id) as designation_name");
		$this->db->from("attendance a");
		$this->db->join("employee_payroll ep",'a.user_id = ep.user_id','left');
		$this->db->join('employee_office eo', 'eo.user_id = a.user_id','left');

		$this->db->where("
			DATE_FORMAT(a.date_added, '%m/%Y') = '{$month}'"				
		);
		
		$this->db->where("
			a.user_id = '{$user_id}'"				
		);
		
		
		$res = $this->db->get()->row();
		//echo $this->db->last_query();
		return $res;
	}
	
	public function getSiteAttendanceSalaries($attendance_id,$designation_id) {
		$this->db->from("site_attendances sa");
		$this->db->join("site s","sa.site_id=s.id","left");
		$this->db->join("site_salaries ss","sa.site_id=ss.site_id","left");
		$this->db->where("sa.attendance_id",$attendance_id);
		$this->db->where("ss.designation_id",$designation_id);
		$site_attendance_data = $this->db->get()->result_array();
		return $site_attendance_data;
	}
}