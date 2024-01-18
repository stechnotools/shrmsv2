<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */
class Loan_model extends MY_Model
{	
   public function __construct(){
		parent::__construct();
	}
	public function addLoan($data) {
		//printr($_POST);
		//exit;
		$install_start = date("Y-m-d",strtotime("01-".$data['install_start']));
		$paid_month=date("Y-m-d",strtotime("01-".$data['paid_month']));
		$loandata=array(
			"user_id"=>$data['user_id'],
			"paid_month"=>$paid_month,
			"install_start"=>$install_start,
			"loan_amount"=>$data['loan_amount'],
			"installment_amount"=>$data['installment_amount'],
			"interest"=>$data['interest'],
			"no_installment"=>$data['no_installment'],
			"merge_loan"=>isset($data['merge_loan'])?1:0,
			"date_added"=>date("Y-m-d"),
		);
      	$this->db->insert("loan", $loandata);
      	$loan_id=$this->db->insert_id();
		
		//adjustment
		$rest_amount=$data['loan_amount'];
		$lastfixedPayment=$rest_amount % $data['installment_amount'];
		//$adjustment[]
		for($i=0;$i<$data['no_installment'];$i++){
			$install_start = strtotime(date("Y-m-d",strtotime("01-".$data['install_start'])));
			$installment_date = date("Y-m-d", strtotime("+".$i." month", $install_start));
			$interestRateForMonth=$data['interest'] / 12;
			$interest_amount=($rest_amount) / 100 * $interestRateForMonth;
			if($i==$data['no_installment']-1){
				$installment_amount=$lastfixedPayment;
			}else{
				$installment_amount=$data['installment_amount'];
			}
			
			$rest_amount=$rest_amount-$installment_amount;
			
			$adjustment=array(
				'user_id'=>$data['user_id'],
				'installment_date'=>$installment_date,
				'loan_id'=>$loan_id,
				'installment_amount'=>$installment_amount,
				'rest_amount'=>$rest_amount,
				'interest_amount'=>round($interest_amount),
				'interest_rate'=>$data['interest'],
				'paid_amount'=>0,
				'recieved'=>0,
				'adjustment'=>0
				
			);
			$this->db->insert("loan_adjustment", $adjustment);
			
		}
		
		return $loan_id;
	}
	public function editLoan($loan_id, $data) {
		$loandata=array(
			"user_id"=>$data['user_id'],
			"paid_month"=>date("Y-m-d",strtotime("01-".$data['paid_month'])),
			"install_start"=>date("Y-m-d",strtotime("01-".$data['install_start'])),
			"loan_amount"=>$data['loan_amount'],
			"installment_amount"=>$data['installment_amount'],
			"no_installment"=>$data['no_installment']
		
		);
		$this->db->where("id",$loan_id);
      	$this->db->update("loan", $loandata);
		
		
		$this->db->where_in("loan_id", $loan_id);
		$this->db->delete("loan_adjustment");
		
		$rest_amount=$data['loan_amount'];
		//$adjustment[]
		for($i=0;$i<$data['no_installment'];$i++){
			$install_start = strtotime(date("Y-m-d",strtotime("01-".$data['install_start'])));
			$installment_date = date("Y-m-d", strtotime("+".$i." month", $install_start));
			$rest_amount=$rest_amount-$data['installment_amount'];
			$adjustment=array(
				'user_id'=>$data['user_id'],
				'installment_date'=>$installment_date,
				'loan_id'=>$loan_id,
				'installment_amount'=>$data['installment_amount'],
				'rest_amount'=>$rest_amount,
				'paid_amount'=>0,
				'recieved'=>0,
				'adjustment'=>0
				
			);
			$this->db->insert("loan_adjustment", $adjustment);
			
		}
		
		
	}
	public function getLoans($data = array()){
		$this->db->select("a.*,eo.employee_name,eo.paycode");
		$this->db->from("loan a");
		$this->db->join('employee_office eo', 'eo.user_id = a.user_id','left');
		
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
		return $res;
	}
	public function getTotalLoans($data = array()) {
		$this->db->from("loan a");
		$this->db->join('employee_office eo', 'eo.user_id = a.user_id','left');
		
		if (!empty($data['filter_search'])) {
			$this->db->where("
				eo.employee_name LIKE '%{$data['filter_search']}%'"				
			);
		}
		$count = $this->db->count_all_results();
		return $count;
	}
	public function getEmployeeLoan($id){
		$this->db->from('loan a');
		$this->db->where("a.id",$id);
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
	
	public function deleteLoan($id){
		$this->db->where_in("id", $id);
		$this->db->delete("loan");
		$this->db->where_in("loan_id", $id);
		$this->db->delete("loan_adjustment");
	}
	
	public function getEmployeeLoanAdjustment($loan_id){
		$this->db->select("*");
		$this->db->from("loan_adjustment");
		$this->db->where("loan_id",$loan_id);
		$res = $this->db->get()->result();
		return $res;	
	}
	
	public function getEmployeeLoanAdjustmentByMonth($user_id,$month){
		$this->db->select("*");
		$this->db->from("loan_adjustment");
		$this->db->where("user_id",$user_id);
		
		$this->db->where("
				DATE_FORMAT(installment_date, '%m-%Y') = '{$month}'"				
			);
		$res = $this->db->get()->result();
		return $res;	
	}
}