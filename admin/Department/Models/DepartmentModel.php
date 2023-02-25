<?php
namespace Admin\Department\Models;
use CodeIgniter\Model;

class DepartmentModel extends Model
{	
	
	public $_table = 'department';
	
	public function __construct(){
		parent::__construct();
	}
	
	public function addDepartment($data) {
		$departmentdata=array(
			"name"=>$data['name'],
			"code"=>$data['code'],
			"hod"=>$data['hod'],
			"email"=>$data['email'],
			"status"=>1,
		);
      	$this->db->insert("department", $departmentdata);
      	$department_id=$this->db->insert_id() ;
		
		return $department_id;
	}
	
	public function editDepartment($department_id, $data) {
		$departmentdata=array(
			"name"=>$data['name'],
			"code"=>$data['code'],
			"hod"=>$data['hod'],
			"email"=>$data['email'],
			"status"=>1,
		);
		$this->db->where("id",$department_id);
      	$this->db->update("department", $departmentdata);	
	}
	public function getDepartments($data = array()){
		$this->db->from("department");
		
		if (!empty($data['filter_search'])) {
			$this->db->where("
				name LIKE '%{$data['filter_search']}%'
				OR code LIKE '%{$data['filter_search']}%')"				
			);
		}
		
		if (!empty($data['filter_department_id'])) {
			$this->db->where("id",$data['filter_department_id']);
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
	public function getTotalDepartments($data = array()) {
		$this->db->from("department");
		
		if (!empty($data['filter_search'])) {
			$this->db->where("
				name LIKE '%{$data['filter_search']}%'
				OR code LIKE '%{$data['filter_search']}%')"				
			);
		}
		
		$count = $this->db->count_all_results();

		return $count;	
	}
	public function getDepartment($id){
		$this->db->where("id",$id);
		$res=$this->db->get('department')->row();
		return $res;
	}
	
	public function getDepartmentByEmail($email) {
		$this->db->where('email',$email);
		$query = $this->db->get('department');
		$Department=$query->row();
		
		return $Department;
	}
	
	public function getDepartmentByName($departmentname) {
		$this->db->where('name', $departmentname);
		$query = $this->db->get('department');
		$Department=$query->row();
		return $Department;
	}
	
	
	public function deleteDepartment($id){
		$this->db->where_in("id", $id);
		$this->db->delete("department");
	}
	public function updateAccount($id, $data) {
		$this->db->where("id",$id);
        $status=$this->db->update("department", $data);
        
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
