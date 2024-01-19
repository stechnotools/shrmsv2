<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */

class Mpunch_model extends MY_Model
{	
	
	public $_table = 'punch';
	
	public function __construct(){
		parent::__construct();
	}
	
	public function addMpunch($data) {
		/*$punchdata=array(
			"name"=>$data['name'],
			"code"=>$data['code'],
			"status"=>1,
		);*/
      	$this->db->insert("punch", $data);
      	$punch_id=$this->db->insert_id() ;
		
		return $punch_id;
	}
	
	public function editMpunch($punch_id, $data) {
		$punchdata=array(
			"name"=>$data['name'],
			"code"=>$data['code'],
			"status"=>1,
		);
		$this->db->where("id",$punch_id);
      	$this->db->update("punch", $punchdata);	
	}
	
	public function savePunchHistory($data) {
      	$this->db->insert("punch_history", $data);
      	$punch_history_id=$this->db->insert_id() ;
		
		return $punch_history_id;
	}
	
	public function getMpunchs($data = array()){
		//$this->db->select("p.*,`s`.`code` AS `shift`,s.shift_start_time,s.shift_end_time,`et`.`perm_late`,`et`.`perm_early`,COUNT(p.id) AS total,min(p.punch_time) as intime, max(p.punch_time) as outtime,s.code as shift");
		$this->db->select("p.*,(select min(ph.punch_time) from punch_history ph where p.id=ph.punch_id) as intime, (select max(ph.punch_time) from punch_history ph where p.id=ph.punch_id) as outtime,(select count(ph.id) from punch_history ph where p.id=ph.punch_id) as total");
		$this->db->from("punch p");
		//$this->db->join("select ph.punch_id,min(ph.punch_time) as intime,max(ph.punch_time) as outtime,count(ph.id) as total from punch_history ph ");
		
		if (!empty($data['filter_search'])) {
			$this->db->where("
				p.paycode LIKE '%{$data['filter_search']}%')"				
			);
		}

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
	
	public function getTotalMpunchs($data = array()) {
		$this->db->from("punch");
		
		if (!empty($data['filter_search'])) {
			$this->db->where("
				paycode LIKE '%{$data['filter_search']}%')"				
			);
		}
		$count = $this->db->count_all_results();

		return $count;	
	}
	
	public function getMpunch($id){
		
		$this->db->where("id",$id);
		$res=$this->db->get('punch')->row();
		return $res;
	}
	
	public function getPunchHistory($user_id,$punch_date) {
		$this->db->where('user_id',$user_id);
		$this->db->where("DATE_FORMAT(punch_date, '%d-%m-%Y')='".$punch_date."'");
		$query = $this->db->get('punch_history');
		$Mpunch=$query->result_array();
		
		return $Mpunch;
	}
	
	public function getTotalPunchByPunchId($punch_id){
		$this->db->from('punch_history');
		$this->db->where('punch_id',$punch_id);
		$Mpunch=$this->db->count_all_results();
		return $Mpunch+1;
	}
	
	public function deleteMpunch($user_id){
		$this->db->where_in("user_id", $user_id);
		$this->db->delete("punch");
	}
	
	public function deletePunchHistory($id){
		$this->db->where_in("id", $id);
		$this->db->delete("punch_history");
	}

	public function getShiftCount($user_id,$duration){
		$this->db->where("user_id",$user_id);
		$this->db->where("punch_date>=DATE_SUB(CURDATE(), INTERVAL ".$duration." DAY");
		$this->db->group_by("shift_id"); 
		$res=$this->db->get('punch')->row();
		return $res;
	}
	
}
