<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */

class Canteen_model extends MY_Model
{	
	
	public $_table = 'canteen';
	
	public function __construct(){
		parent::__construct();
	}
	
	public function addCanteen($data) {
		
		$order_date=explode(',',$data['from_date']);
		
		foreach($order_date as $date){
			$canteendata=array(
				"user_id"=>$data['user_id'],
				"branch_id"=>$data['branch_id'],
				"department_id"=>$data['department_id'],
				"no_of_guest"=>$data['no_of_guest'],
				"guest_name"=>$data['guest_name'],
				"from_date"=>date("Y-m-d",strtotime($date)),
				"breakfast"=>$data['breakfast'],
				"lunch"=>$data['lunch'],
				"snack"=>$data['snack'],
				"dinner"=>$data['dinner'],
				"status"=>$data['status'],
			);
			$this->db->insert("canteen", $canteendata);
			$canteen_id=$this->db->insert_id() ;
			
			/*if($data['types']){
				foreach($data['types'] as $type){
					$canteentypedata=array(
						"canteen_id"=>$canteen_id,
						"type"=>$type,
					);
					$this->db->insert("canteen_type", $canteentypedata);
				}
			}*/
			
		}
		
		
		return $canteen_id;
	}
	
	public function editCanteen($canteen_id, $data) {
		
		
		
		$canteendata=array(
			"user_id"=>$data['user_id'],
			"no_of_guest"=>$data['no_of_guest'],
			"guest_name"=>$data['guest_name'],
			"from_date"=>date("Y-m-d",strtotime($data['from_date'])),
			"breakfast"=>$data['breakfast'],
			"lunch"=>$data['lunch'],
			"snack"=>$data['snack'],
			"dinner"=>$data['dinner'],
			"status"=>$data['status'],
		);
		$this->db->where("id",$canteen_id);
      	$this->db->update("canteen", $canteendata);	
		
		
	}
	public function getCanteens($data = array()){
		$this->db->select("c.*,d.name as department_name ,eo.employee_name,eo.paycode");
		$this->db->from("canteen c");
		$this->db->join("employee_office eo","c.user_id=eo.user_id","left");
		$this->db->join("department d","c.department_id=d.id","left");
		
		if(!empty($data['filter_from'])){
            $this->db->where("c.from_date >= '".date("Y-m-d",strtotime($data['filter_from']))."'");
        }
        if(!empty($data['filter_to'])){
            $this->db->where("c.from_date <= '".date("Y-m-d",strtotime($data['filter_to']))."'");
		}
		
		if (!empty($data['filter_search'])) {
			$this->db->where("
				eo.employee_name LIKE '%{$data['filter_search']}%'"				
			);
		}
		
		if (!empty($data['filter_user_id'])) {
			$this->db->where("c.user_id",$data['filter_user_id']);
		}
		
		if (!empty($data['filter_depratment'])) {
			$this->db->where("c.department_id",$data['filter_depratment']);
		}

		if (isset($data['sort']) && $data['sort']) {
			$sort = $data['sort'];
		} else {
			$sort = "c.from_date";
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
	public function getTotalCanteens($data = array()) {
		$this->db->from("canteen c");
		$this->db->join("employee_office eo","c.user_id=eo.user_id","left");
		$this->db->join("department d","c.department_id=d.id","left");
		
		if(!empty($data['filter_from'])){
            $this->db->where("c.from_date >= '".date("Y-m-d",strtotime($data['filter_from']))."'");
        }
        if(!empty($data['filter_to'])){
            $this->db->where("c.from_date <= '".date("Y-m-d",strtotime($data['filter_to']))."'");
		}
		
		if (!empty($data['filter_search'])) {
			$this->db->where("
				eo.employee_name LIKE '%{$data['filter_search']}%'"				
			);
		}
		
		if (!empty($data['filter_user_id'])) {
			$this->db->where("c.user_id",$data['filter_user_id']);
		}
		
		if (!empty($data['filter_depratment'])) {
			$this->db->where("c.department_id",$data['filter_depratment']);
		}
		
		$count = $this->db->count_all_results();

		return $count;	
	}
	public function getCanteen($id){
		$this->db->where("id",$id);
		$res=$this->db->get('canteen')->row();
		return $res;
	}
	
	public function getCanteenType($id){
		$this->db->select("type");
		$this->db->where("canteen_id",$id);
		$res=$this->db->get('canteen_type')->result_array();
		return $res;
	}
	
	public function deleteCanteen($id){
		$this->db->where_in("id", $id);
		$this->db->delete("canteen");
	}
	
	public function checkFoodDate($user_id,$date, $types){
		$type=implode(',',$types);
		$sql="SELECT group_concat(ct.type) as type FROM `canteen` c left join canteen_type ct on (c.id=ct.canteen_id) WHERE c.user_id='".$user_id."' and DATE(c.from_date)= '".date("Y-m-d",strtotime($date))."' and FIND_IN_SET(ct.type, '".$type."')";
		$res=$this->db->query($sql)->row_array();
		return $res;
	}
	
	
	
	public function checkOrderDate($user_id,$date){
		$sql="SELECT * FROM `canteen` c WHERE c.user_id='".$user_id."' and DATE(c.from_date)= '".date("Y-m-d",strtotime($date))."'";
		$res=$this->db->query($sql)->row_array();
		return $res;
	}
	
}
