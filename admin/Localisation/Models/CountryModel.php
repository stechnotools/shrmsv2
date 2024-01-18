<?php

namespace Admin\Localisation\Models;

use CodeIgniter\Model;

class CountryModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'country';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'object';
	protected $useSoftDeletes       = true;
	protected $protectFields        = false;
	protected $allowedFields        = [];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	// Validation
	protected $validationRules      = [
		'iso_code_2' => array(
			'label' => 'Code', 
			'rules' => 'trim|required|max_length[100]'
		),
		
		'name' => array(
			'label' => 'Name', 
			'rules' => "trim|required|max_length[255]"
		)
	];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];
	
	
	public function getAll($data = array()){
		//printr($data);
		$builder=$this->db->table($this->table);
		$this->filter($builder,$data);
		
		$builder->select("*");
		
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
		$builder->orderBy($sort, $order); 
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 10;
			}
			$builder->limit((int)$data['limit'],(int)$data['start']);
		}


		$res = $builder->get()->getResult();

		return $res;
	}
	
	public function getTotals($data = array()) {
		$builder=$this->db->table($this->table);
		$this->filter($builder,$data);
		$count = $builder->countAllResults();
		return $count;
	}
	
	private function filter($builder,$data){
        $builder->where($this->deletedField, null);
        if (!empty($data['filter_search'])) {
			$builder->where("
				name LIKE '%{$data['filter_search']}%'"				
			);
		}
    }
	public function getDistrictByCode($code){
		$builder=$this->db->table($this->table);
		$builder->where('code',$code);
		$res = $builder->get()->getRowArray();

		return $res;	
	}
	
}
