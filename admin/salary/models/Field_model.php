<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */
class Field_model extends MY_Model
{	
	protected $_table="salary_field";
	public function __construct(){
		parent::__construct();
	}
	public function addField($data) {
		//printr($_POST);
		//exit;
		$fielddata=array(
			"name"=>$data['name'],
			"field"=>underscore($data['name']),
			"type"=>$data['type'],
			"status"=>$data['status'],
		);
      	$this->db->insert("salary_field", $fielddata);
      	$field_id=$this->db->insert_id() ;
		return $field_id;
	}
	public function editField($field_id, $data) {
		$fielddata=array(
			"name"=>$data['name'],
			"field"=>underscore($data['name']),
			"type"=>$data['type'],
			"status"=>$data['status'],
		);
		$this->db->where("id",$field_id);
      	$this->db->update("salary_field", $fielddata);
		
		
	}
	public function getFields($data = array()){
		$this->db->select("ep.*");
		$this->db->from("salary_field ep");
		
		if (!empty($data['filter_search'])) {
			$this->db->where("
				ep.name LIKE '%{$data['filter_search']}%'"				
			);
		}
		
		if(!empty($data['type'])){
			$this->db->where("ep.type", $data['type']);
		}
		
		if (isset($data['sort']) && $data['sort']) {
			$sort = $data['sort'];
		} else {
			$sort = "ep.name";
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
	public function getTotalFields($data = array()) {
		$this->db->from("salary_field ep");
		
		if (!empty($data['filter_search'])) {
			$this->db->where("
				ep.name LIKE '%{$data['filter_search']}%'"				
			);
		}
		if(!empty($data['type'])){
			$this->db->where("ep.type", $data['type']);
		}
		$count = $this->db->count_all_results();
		return $count;
	}
	public function getField($field_id){
		$this->db->from('salary_field ep');
		$this->db->where("ep.id",$field_id);
		$res = $this->db->get()->row();
		return $res;	
	}
	
	
	public function deleteField($field_id){
		$this->db->where_in("id", $field_id);
		$this->db->delete("salary_field");
		
	}
}