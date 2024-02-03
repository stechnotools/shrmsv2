<?php

namespace Admin\Permission\Models;

use CodeIgniter\Model;

class PermissionModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'permission';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'object';
    protected $useSoftDeletes       = false;
    protected $protectFields        = false;
    //protected $allowedFields        = [];

    // Dates
    protected $useTimestamps        = false;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'id'    => 'permit_empty|integer|greater_than[0]',
        'route' => array(
            'label' => 'route',
            'rules' => 'trim|required|max_length[100]|is_unique[permission.route,id,{id}]'
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
        $builder=$this->db->table($this->table);
        $this->filter($builder,$data);

        $builder->select("*");

        if (isset($data['sort']) && $data['sort']) {
            $sort = $data['sort'];
        } else {
            $sort = "route";
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
                route LIKE '%{$data['filter_search']}%'"
            );
        }
    }

    public function get_modules_with_permission($id=null){
        $query = "Select p1.id,p1.route,p1.module,p1.action,p1.description, (case when p2.user_role_id = $id then 'yes' else 'no' end) as active From permission p1 left join user_role_permission p2 ON p1.id = p2.permission_id and p2.user_role_id =$id";
        return $this->db->query($query)->getResult();
    }

    public function get_modules_with_permission_old($id=null){
        $query = "Select p1.id,p1.name,p1.description, (case when p2.user_role_id = $id then 'yes' else 'no' end) as active From permission p1 left join user_role_permission p2 ON p1.id = p2.permission_id and p2.user_role_id =$id";
        return $this->db->query($query)->getResult();
    }

    public function addUserGroupPermission($id,$data){

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

    public function addUserPermission($user_id,$data){
        $builder=$this->db->table("user_permission");
        $builder->where("user_id",$user_id);
        $builder->delete();

        if (isset($data)) {
            foreach ($data as $key => $value) {
                $array = array(
                    'permission_id'=>$value,
                    'user_id'=>$user_id
                );
                $builder->insert($array);
            }
        }
    }
}
