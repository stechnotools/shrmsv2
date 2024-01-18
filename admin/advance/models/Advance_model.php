<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */
class Advance_model extends MY_Model
{	
   public function __construct(){
		parent::__construct();
	}
	public function addAdvance($data) {
		//printr($_POST);
		//exit;
		$install_start = date("Y-m-d",strtotime("01-".$data['install_start']));
		$paid_month=date("Y-m-d",strtotime("01-".$data['paid_month']));
		$advancedata=array(
			"user_id"=>$data['user_id'],
			"paid_month"=>$paid_month,
			"install_start"=>$install_start,
			"advance_amount"=>$data['advance_amount'],
			"installment_amount"=>$data['installment_amount'],
			"no_installment"=>$data['no_installment'],
			"date_added"=>date("Y-m-d"),
		);
      	$this->db->insert("advance", $advancedata);
      	$advance_id=$this->db->insert_id();
		
		//adjustment
		$rest_amount=$data['advance_amount'];
		//$adjustment[]
		for($i=0;$i<$data['no_installment'];$i++){
			$install_start = strtotime(date("Y-m-d",strtotime("01-".$data['install_start'])));
			$installment_date = date("Y-m-d", strtotime("+".$i." month", $install_start));
			$rest_amount=$rest_amount-$data['installment_amount'];
			$adjustment=array(
				'user_id'=>$data['user_id'],
				'installment_date'=>$installment_date,
				'advance_id'=>$advance_id,
				'installment_amount'=>$data['installment_amount'],
				'rest_amount'=>$rest_amount,
				'paid_amount'=>0,
				'recieved'=>0,
				'adjustment'=>0
				
			);
			$this->db->insert("advance_adjustment", $adjustment);
			
		}
		
		return $advance_id;
	}
	public function editAdvance($advance_id, $data) {
		$advancedata=array(
			"user_id"=>$data['user_id'],
			"paid_month"=>date("Y-m-d",strtotime("01-".$data['paid_month'])),
			"install_start"=>date("Y-m-d",strtotime("01-".$data['install_start'])),
			"advance_amount"=>$data['advance_amount'],
			"installment_amount"=>$data['installment_amount'],
			"no_installment"=>$data['no_installment']
		
		);
		$this->db->where("id",$advance_id);
      	$this->db->update("advance", $advancedata);
		
		
		$this->db->where_in("advance_id", $advance_id);
		$this->db->delete("advance_adjustment");
		
		$rest_amount=$data['advance_amount'];
		//$adjustment[]
		for($i=0;$i<$data['no_installment'];$i++){
			$install_start = strtotime(date("Y-m-d",strtotime("01-".$data['install_start'])));
			$installment_date = date("Y-m-d", strtotime("+".$i." month", $install_start));
			$rest_amount=$rest_amount-$data['installment_amount'];
			$adjustment=array(
				'user_id'=>$data['user_id'],
				'installment_date'=>$installment_date,
				'advance_id'=>$advance_id,
				'installment_amount'=>$data['installment_amount'],
				'rest_amount'=>$rest_amount,
				'paid_amount'=>0,
				'recieved'=>0,
				'adjustment'=>0
				
			);
			$this->db->insert("advance_adjustment", $adjustment);
			
		}
		
		
	}
	public function getAdvances($data = array()){
		$this->db->select("a.*,eo.employee_name,eo.paycode");
		$this->db->from("advance a");
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
	public function getTotalAdvances($data = array()) {
		$this->db->from("advance a");
		$this->db->join('employee_office eo', 'eo.user_id = a.user_id','left');
		
		if (!empty($data['filter_search'])) {
			$this->db->where("
				eo.employee_name LIKE '%{$data['filter_search']}%'"				
			);
		}
		$count = $this->db->count_all_results();
		return $count;
	}
	public function getEmployeeAdvance($id){
		$this->db->from('advance a');
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
	
	public function deleteAdvance($id){
		$this->db->where_in("id", $id);
		$this->db->delete("advance");
	}
	
	public function getEmployeeAdvanceAdjustment($advance_id){
		$this->db->select("*");
		$this->db->from("advance_adjustment");
		$this->db->where("advance_id",$advance_id);
		$res = $this->db->get()->result();
		return $res;	
	}
	
	public function getEmployeeAdvanceAdjustmentByMonth($user_id,$month){
		$this->db->select("*");
		$this->db->from("advance_adjustment");
		
		$this->db->where("user_id",$user_id);
		
		$this->db->where("
				DATE_FORMAT(installment_date, '%m/%Y') = '{$month}'"				
			);
		$res = $this->db->get()->result();
		
		return $res;	
	}
	
}