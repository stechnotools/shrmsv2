<?php

namespace Admin\Users\Models;

use CodeIgniter\Model;

class UserGroupModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'user_group';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'object';
    protected $useSoftDeletes        = true;
    protected $protectFields        = false;
//	protected $allowedFields        = [];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
    protected $validationRules      = [
        'name' => array(
            'label' => 'Name',
            'rules' => 'trim|required|max_length[100]'
        ),
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
        $builder=$this->db->table("{$this->table} ug");
       
        $this->filter($builder,$data);

        $builder->select("ug.*");

        if (isset($data['sort']) && $data['sort']) {
            $sort = $data['sort'];
        } else {
            $sort = "ug.name";
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

        $res = $builder->get()->getResult();

        return $res;
    }

    public function getTotal($data = array()) {
        $builder=$this->db->table($this->table);
        $this->filter($builder,$data);
        $count = $builder->countAllResults();
        return $count;
    }

    private function filter($builder,$data){
        
        if (!empty($data['filter_search'])) {
            $builder->where("
				name LIKE '%{$data['filter_search']}%'"
            );
        }

    }

    public function addUserRolePermission($id,$data){
        $builder=$this->db->table("user_role_permission");
        $builder->where("user_role_id",$id);
        $builder->delete();

        if (isset($data)) {
            foreach ($data as $key => $value) {
                $array = array(
                    'permission_id'=>$value,
                    'user_role_id'=>$id
                );
                $builder->insert($array);
            }
        }
        return "success";
    }

    public function getAgencyTypes($filter=[]){
        $builder=$this->db->table($this->table);
        $builder->where('agency', 1);
        if(isset($filter['agency_type_id'])){
            $builder->where("id",$filter['agency_type_id']);
        }
        $res = $builder->get()->getResult();
        return $res;
    }
}
