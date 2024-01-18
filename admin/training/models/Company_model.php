<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */

class Branch_model extends MY_Model
{	
	
	public $_table = 'branch';
	
	public function __construct(){
		parent::__construct();
	}
	
	public function addBranch($data) {
		$branchdata=array(
			"name"=>$data['name'],
			"code"=>$data['code'],
			"short"=>$data['short'],
			"address"=>$data['address'],
			"email1"=>$data['email1'],
			"email2"=>$data['email2'],
			"total_pass"=>$data['total_pass'],
			"pass_duration"=>$data['pass_duration'],
		);
      	$this->db->insert("branch", $branchdata);
      	$branch_id=$this->db->insert_id() ;
		
		return $branch_id;
	}
	
	public function editBranch($branch_id, $data) {
		$branchdata=array(
			"name"=>$data['name'],
			"code"=>$data['code'],
			"short"=>$data['short'],
			"address"=>$data['address'],
			"email1"=>$data['email1'],
			"email2"=>$data['email2'],
			"total_pass"=>$data['total_pass'],
			"pass_duration"=>$data['pass_duration'],
		);
		$this->db->where("id",$branch_id);
      	$this->db->update("branch", $branchdata);	
	}
	public function getBranches($data = array()){
		$this->db->from("branch");
		
		if (!empty($data['filter_search'])) {
			$this->db->where("
				name LIKE '%{$data['filter_search']}%'
				OR code LIKE '%{$data['filter_search']}%'
				OR short LIKE '%{$data['filter_search']}%')"				
			);
		}

		if (isset($data['sort']) && $data['sort']) {
			$sort = $data['sort'];
		} else {
			$sort = "name";
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
	public function getTotalBranches($data = array()) {
		$this->db->from("branch");
		
		if (!empty($data['filter_search'])) {
			$this->db->where("
				name LIKE '%{$data['filter_search']}%'
				OR code LIKE '%{$data['filter_search']}%'
				OR short LIKE '%{$data['filter_search']}%')"				
			);
		}
		
		$count = $this->db->count_all_results();

		return $count;	
	}
	public function getBranch($id){
		$this->db->where("id",$id);
		$res=$this->db->get('branch')->row();
		return $res;
	}
	
	public function getBranchByEmail($email) {
		$this->db->where('email',$email);
		$query = $this->db->get('branch');
		$Branch=$query->row();
		
		return $Branch;
	}
	
	public function getBranchByBranchname($branchname) {
		$this->db->where('branchname', $branchname);
		$query = $this->db->get('branch');
		$Branch=$query->row();
		return $Branch;
	}
	
	
	public function deleteBranch($id){
		$this->db->where_in("id", $id);
		$this->db->delete("branch");
	}
	public function updateAccount($id, $data) {
		$this->db->where("id",$id);
        $status=$this->db->update("branch", $data);
        
        if($status) 
		{
			
			return "success";
		}
	}
	
	public function getCountry($country_id) {
		$this->db->from("aio_country");
		$this->db->where("country_id",$country_id);
		$res = $this->db->get()->row();
		return $res->name;
	}
	public function getState($state_id) {
		$this->db->from("aio_state");
		$this->db->where("state_id",$state_id);
		$res = $this->db->get()->row();
		if(!empty($res))
		return $res->name;
		else
		return '';
	}
}
