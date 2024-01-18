<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */
class Formula_model extends MY_Model
{	
   public function __construct(){
		parent::__construct();
	}
	public function addFormula($data) {
		$formuladata=array(
			"branch_id"=>$data['branch_id'],
			"code"=>$data['code'],
			"formula"=>$data['formula'],
		);
      	$this->db->insert("formula", $formuladata);
      	$formula_id=$this->db->insert_id();
		
		return $formula_id;
	}
	public function editFormula($formula_id, $data) {
		$formuladata=array(
			"branch_id"=>$data['branch_id'],
			"code"=>$data['code'],
			"formula"=>$data['formula'],
		);
		$this->db->where("id",$formula_id);
      	$this->db->update("formula", $formuladata);
	}
	public function getFormulas($data = array()){
		$this->db->select("f.*,c.name as branch_name");
		$this->db->from("formula f");
		$this->db->join('branch c', 'c.id = f.branch_id','left');
		
		if (!empty($data['filter_search'])) {
			$this->db->where("
				c.name LIKE '%{$data['filter_search']}%'
				OR f.code LIKE '%{$data['filter_search']}%')"	
			);
		}
		
		if (!empty($data['branch_id'])) {
			$this->db->where("f.branch_id",$data['branch_id']);
		}
		
		if (isset($data['sort']) && $data['sort']) {
			$sort = $data['sort'];
		} else {
			$sort = "c.name";
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
	public function getTotalFormulas($data = array()) {
		$this->db->select("f.*,c.name as branch_name");
		$this->db->from("formula f");
		$this->db->join('branch c', 'c.id = f.branch_id','left');
		
		if (!empty($data['filter_search'])) {
			$this->db->where("
				c.name LIKE '%{$data['filter_search']}%'
				OR f.code LIKE '%{$data['filter_search']}%')"	
			);
		}
		if (!empty($data['branch_id'])) {
			$this->db->where("f.branch_id",$data['branch_id']);
		}
		$count = $this->db->count_all_results();
		return $count;
	}
	public function getFormula($id){
		$this->db->from('formula f');
		$this->db->where("f.id",$id);
		$res = $this->db->get()->row();
		return $res;	
	}
	
	public function deleteFormula($id){
		$this->db->where_in("id", $id);
		$this->db->delete("formula");
	}
	
}