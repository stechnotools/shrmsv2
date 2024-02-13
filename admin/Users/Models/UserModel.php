<?php
namespace Admin\Users\Models;

use Admin\Localisation\Models\BlockModel;
use Admin\Localisation\Models\DistrictModel;
use Admin\Localisation\Models\GrampanchayatModel;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class UserModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'user';
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
        'id' => 'is_natural_no_zero|permit_empty',
        'name' => array(
            'label' => 'Name',
            'rules' => 'trim|required|max_length[100]'
        ),

        'email' =>array(
            'label' => 'Email',
            'rules' => 'required|max_length[254]|valid_email|is_unique[user.email,id,{id}]',
        ),

        'username' =>array(
            'label' => 'Username',
            'rules' => "required|is_unique[user.username,id,{id}]"
        ),

    ];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
    protected $beforeInsert         = ['setPassword','resetAssign'];
	protected $afterInsert          = [];
	protected $beforeUpdate         = ['setPassword','resetAssign'];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

    public function getAll($data = array()){
        //printr($data);
        $builder=$this->db->table("{$this->table} u");

        $this->filter($builder,$data);

        $builder->select("u.*,ur.name role");

        if (isset($data['sort']) && $data['sort']) {
            $sort = $data['sort'];
        } else {
            $sort = "u.name";
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

    public function getTotal($data = array()) {
        $builder=$this->db->table("{$this->table} u");
        $this->filter($builder,$data);
        $count = $builder->countAllResults();
        return $count;
    }


	private function filter($builder,$data){
        $builder->join("user_role ur", "ur.id=u.user_role_id","left");
        $builder->where("u.{$this->deletedField}", null);
        if (!empty($data['filter_search'])) {
            $builder->where("
				u.name LIKE '%{$data['filter_search']}%'
                OR u.email LIKE '%{$data['filter_search']}%'
				OR u.username LIKE '%{$data['filter_search']}%'
				OR ur.name LIKE '%{$data['filter_search']}%'"
            );
        }

    }


    protected  function setPassword(array $data){
        $data['data']['show_password']=$data['data']['password'];
	    $data['data']['password']=password_hash($data['data']['password'], PASSWORD_DEFAULT);
        return $data;
    }


}
