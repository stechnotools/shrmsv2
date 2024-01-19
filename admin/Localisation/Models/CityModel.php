<?php

namespace Admin\Localisation\Models;

use CodeIgniter\Model;

class GrampanchayatModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'grampanchayat';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'object';
	protected $useSoftDeletes       = false;
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
		'district_id' => array(
			'label' => 'District', 
			'rules' => 'trim|required|max_length[100]'
		),
		'block_id' => array(
			'label' => 'Block', 
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
	protected $beforeInsert         = ['getGrampanchayatCode'];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];
	
	
	public function getAll($data = array()){
		//printr($data);
		$builder=$this->db->table("{$this->table} g");
		$this->filter($builder,$data);
		
		$builder->select("g.*,d.name as district,b.name as block,b.code as bcode,(SELECT c.code FROM cluster c LEFT JOIN cluster_to_gp ctg ON c.id = ctg.cluster_id WHERE ctg.gp_id=g.id ) as ccode");
		
		if (isset($data['sort']) && $data['sort']) {
			$sort = $data['sort'];
		} else {
			$sort = "g.name";
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
		//$builder->where($this->deletedField, null);
        //$builder->where('g.tcode', null);
		$res = $builder->get()->getResult();

		return $res;
	}
	
	public function getTotals($data = array()) {
		$builder=$this->db->table("{$this->table} g");
		$this->filter($builder,$data);
		$count = $builder->countAllResults();
		return $count;
	}
	
	private function filter($builder,$data){
		$builder->join('district d', 'g.district_id = d.id','left');
        $builder->join('block b', 'g.block_id = b.id','left');
        
		if(!empty($data['filter_district'])){
            $builder->where("g.district_id  = '".$data['filter_district']."'");
        }

        if(!empty($data['filter_block'])){
            $builder->where("g.block_id  = '".$data['filter_block']."'");
        }
		
		if(!empty($data['filter_grampanchayat'])){
            $builder->where("g.name  LIKE '%{$data['filter_grampanchayat']}%'");
        }
		if (!empty($data['filter_search'])) {
			$builder->where("
				b.name LIKE '%{$data['filter_search']}%'	
				OR b.code LIKE '%{$data['filter_search']}%'"
			);
		}
    }

	protected  function getGrampanchayatCode(array $data){
		//printr($data);
		$builder=$this->db->table("{$this->table} g");
		$builder->select("g.code");
		$builder->where("g.district_id  = '".$data['data']['district_id']."'");
        $builder->where("g.block_id  = '".$data['data']['block_id']."'");
		$builder->orderBy('g.code', 'desc');
		$builder->limit(1);
		$res = $builder->get()->getRow();

		
		if($res){
			$laststr=$res->code;
			$larr=str_split($laststr, strlen($laststr) - 2);
			$inumber=sprintf("%02d", $larr[1]+1);
			$data['data']['code']=$larr[0].$inumber;
		}else{
            $blockModel=new BlockModel();
            $block=$blockModel->find($data['data']['block_id']);

            $data['data']['code']=$block->code."G01";
		}

		return $data;
	}

    public function getGPsByBlock($block) {
        $builder=$this->db->table("{$this->table} b");
        $builder->where("block_id",$block);
        $res = $builder->get()->getResult();
        return $res;
    }

    public function getGPsByCluster($cluster) {
        $builder=$this->db->table("cluster_to_gp cg");
        $builder->join('cluster c', 'cg.cluster_id = c.id');
        $builder->join('grampanchayat g', 'cg.gp_id = g.id');
        $builder->select("g.*");
        $builder->where("c.code",$cluster);
        $res = $builder->get()->getResult();
        return $res;
    }

    public function getGPByCode($code){

        $builder=$this->db->table("{$this->table} g");
        $builder->select("GROUP_CONCAT(g.id) AS ids");
        $builder->whereIn("g.code",explode(",",$code));
        $res = $builder->get()->getRowArray();
        //echo $this->db->getLastQuery();
        //exit;
        return $res;
    }
	
}
