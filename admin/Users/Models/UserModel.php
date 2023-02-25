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
        'firstname' => array(
            'label' => 'Name',
            'rules' => 'trim|required|max_length[100]'
        ),

        'email' =>array(
            'label' => 'Email',
            'rules' => 'required',
            'rules' => "trim|required|valid_email|max_length[255]|is_unique[user.email,id,{id}]"
        ),

        'username' =>array(
                'label' => 'Username',
                'rules' => "required|is_unique[user.username,id,{id}]"
        ),
        'password' =>array(
            'label' => 'Password',
            'rules' => 'required'
        )
    ];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
    protected $beforeInsert         = ['setPassword','gparray','localisation','resetAssign'];
	protected $afterInsert          = [];
	protected $beforeUpdate         = ['setPassword','gparray','localisation','resetAssign'];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

    public function getAll($filter=[]) {
        $sql = "SELECT
  u.*,d.name district,b.name block,(select GROUP_CONCAT(g.name SEPARATOR '<br>') from grampanchayat g where FIND_IN_SET(g.id,u.gp_id)) as gp,ug.name role
FROM user u
  LEFT JOIN district d
    ON d.code = u.district
  LEFT JOIN block b
    ON b.code = u.block
  LEFT JOIN user_group ug
    ON ug.id = u.user_group_id
WHERE  u.deleted_at IS NULL";

        if (!empty($filter['filter_search'])) {
            $sql .= " AND (concat_ws(' ', u.firstname, u.lastname) LIKE '%{$filter['filter_search']}%'
                OR u.email LIKE '%{$filter['filter_search']}%'
				OR u.username LIKE '%{$filter['filter_search']}%'
				OR d.name LIKE '%{$filter['filter_search']}%'
				OR b.name LIKE '%{$filter['filter_search']}%'
				OR ug.name LIKE '%{$filter['filter_search']}%'
            )";
        }

        if (isset($filter['sort']) && $filter['sort']) {
            $sort = $filter['sort'];
        } else {
            $sort = "d.name,b.name";
        }

        if (isset($filter['order']) && ($filter['order'] == 'desc')) {
            $order = "desc";
        } else {
            $order = "asc";
        }
        $sql .= " ORDER BY $sort $order ";

        if (isset($filter['start']) || isset($filter['limit'])) {
            if ($filter['start'] < 0) {
                $filter['start'] = 0;
            }

            if ($filter['limit'] < 1) {
                $filter['limit'] = 10;
            }
        }

        $sql .= " LIMIT ".$filter['start'].', '.$filter['limit'];

        return $this->db->query($sql)->getResult();
//        return
	}

    public function getTotal($filter=[]) {
        $sql = "SELECT
              COUNT(u.id) total
            FROM user u
              LEFT JOIN district d
                ON d.id = u.district_id
              LEFT JOIN cluster c
                ON c.id = u.cluster_id
              LEFT JOIN user_group ug
                ON ug.id = u.user_group_id
            WHERE user_group_id != 1 AND u.deleted_at IS NULL";

        if (!empty($filter['filter_search'])) {
            $sql .= " AND (concat_ws(' ', u.firstname, u.lastname) LIKE '%{$filter['filter_search']}%'
                OR u.email LIKE '%{$filter['filter_search']}%'
				OR u.username LIKE '%{$filter['filter_search']}%'
				OR d.name LIKE '%{$filter['filter_search']}%'
				OR c.name LIKE '%{$filter['filter_search']}%'
				OR ug.name LIKE '%{$filter['filter_search']}%'
            )";
        }

        $count = $this->db->query($sql)->getRow()->total;

//        $count = $this->countAllResults();

        return $count;
	}

    protected  function setPassword(array $data){
        $data['data']['show_password']=$data['data']['password'];
	    $data['data']['password']=password_hash($data['data']['password'], PASSWORD_DEFAULT);

        return $data;
    }
	
	protected function localisation(array $data){

        if($data['data']['district']) {
            $districtModel=new DistrictModel();
            $district=$districtModel->getDistrictByCode($data['data']['district']);
            $data['data']['district_id']=$district?$district['id']:0;
        }
        if($data['data']['block']) {
            $blockModel=new BlockModel();
            $block=$blockModel->getBlockByCode($data['data']['block']);
            $data['data']['block_id']=$block?$block['id']:0;
        }
        if(isset($data['data']['gp'])){
            $gpModel=new GrampanchayatModel();
			$gp=$gpModel->getGPByCode($data['data']['gp']);
			$data['data']['gp_id']=$gp?$gp['ids']:'';
		}

		return $data;
		
	}
}
