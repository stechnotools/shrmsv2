<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * AIO ADMIN
 *
 * @author      Mark Price
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */
class Tax_model extends MY_Model
{	
   public function __construct(){
		parent::__construct();
	}
	public function getTaxs($data = array()){
		$this->db->select("*");
		$this->db->from("tax_class");
		
		if (!empty($data['filter_search'])) {
			$this->db->where("name LIKE '%{$data['filter_search']}%'");
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
	
	public function getTotalTaxs($data = array()) {
		$this->db->from("tax_class");

		if (!empty($data['filter_search'])) {
			$this->db->where("
				name LIKE '%{$data['filter_search']}%'"				
			);
		}
		$count = $this->db->count_all_results();
		return $count;
	}
	
	public function getTax($id) {
		$this->db->where("id",$id);
		$result=$this->db->get('tax_class')->row();
		return $result;
	}
	
	public function getTaxRates($id) {
		
		$this->db->from("tax_rate");
		$this->db->where("tax_class_id",$id);
		$this->db->order_by("id", "asc");
		$tax_rate_data = $this->db->get()->result();
		return $tax_rate_data;
	}
	
	public function editTax($id, $data) {
		
		$taxdata=array(
			"name"=>$data['name'],
			"field"=>underscore($data['name']),
			"description"=>$data['description'],
			"to_date"=>date("Y-m-d",strtotime($data['to_date'])),
			"from_date"=>date("Y-m-d",strtotime($data['from_date'])),
			"date_added"=>date("Y-m-d"),
			"status"=>$data['status']
		);
		$this->db->where("id",$id);
		$this->db->update("tax_class", $taxdata);
		
		$this->db->where("tax_class_id",$id);
		$this->db->delete("tax_rate");
		
      if (isset($data['tax_rate'])) {
			foreach ($data['tax_rate'] as $tax_rate) {
				$tax_rate_data=array(
					"tax_class_id"=>$id,
					"to_amount"=>$tax_rate['to_amount'],
					"from_amount"=>$tax_rate['from_amount'],
					"type"=>$tax_rate['type'],
					"rate"=>$tax_rate['rate'],
					"additional"=>$tax_rate['additional'],
					
				);
				$this->db->insert("tax_rate", $tax_rate_data);
				
			}
		}	
       
      return "success";
	}
	
	public function addTax($data) {
      $taxdata=array(
			"name"=>$data['name'],
			"field"=>underscore($data['name']),
			"description"=>$data['description'],
			"to_date"=>date("Y-m-d",strtotime($data['to_date'])),
			"from_date"=>date("Y-m-d",strtotime($data['from_date'])),
			"date_added"=>date("Y-m-d"),
			"status"=>$data['status']
		);
      $this->db->insert("tax_class", $taxdata);
      $id=$this->db->insert_id() ;
		
		if (isset($data['tax_rate'])) {
			foreach ($data['tax_rate'] as $tax_rate) {
				$tax_rate_data=array(
					"tax_class_id"=>$id,
					"to_amount"=>$tax_rate['to_amount'],
					"from_amount"=>$tax_rate['from_amount'],
					"type"=>$tax_rate['type'],
					"rate"=>$tax_rate['rate'],
					"additional"=>$tax_rate['additional'],
					
				);
				$this->db->insert("tax_rate", $tax_rate_data);
				
			}
		}
	}
	
	public function deleteTax($selected){
		$this->db->where_in("id", $selected)
					->delete("tax_class");
		
		$this->db->where_in("tax_class_id", $selected)
					->delete("tax_rate");
		
	}
	
	public function getTaxRateByField($field){
		$this->db->from("tax_class tc");
		$this->db->join("tax_rate tr","tc.id=tr.tax_class_id");
		$this->db->where("tc.field",$field);
		$tax_rate_data = $this->db->get()->result();
		return $tax_rate_data;
	}
}
