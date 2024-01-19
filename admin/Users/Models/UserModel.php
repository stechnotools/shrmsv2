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
    protected $beforeInsert         = ['setPassword'];
	protected $afterInsert          = [];
	protected $beforeUpdate         = ['setPassword'];
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
        $builder->join("user_group ur", "ur.id=u.user_group_id","left");
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


	//rakesh
    public function getUserUploadMonths($filter=[]) {

        $sql = "SELECT
  users.*,
  sau.id upload_id,
  users.district_id,
  users.district,
  users.block_id,
  users.block,
  sau.month month_id,
  sau.year,
  sau.from_date,
  sau.to_date,
  saub.to_date extended_date
FROM allow_uploads sau
  LEFT JOIN (SELECT
      u.id user_id,
      u.user_group_id agency_type_id,
      u.username,
      u.firstname,
      u.block_id,
      sb.name block,
      u.district_id,
      sd.name district
    FROM user u
      LEFT JOIN block sb
        ON u.block_id = sb.id
      LEFT JOIN district sd
        ON u.district_id = sd.id
    WHERE u.deleted_at IS NULL";
        if(!empty($filter['agency_type_id'])){
            $sql .= " AND u.user_group_id = ".$filter['agency_type_id'];
        } else {
            $sql .= " AND u.user_group_id IN (5, 7, 8, 9)";
        }

        $sql .= ") users
    ON sau.agency_type_id = users.agency_type_id
  LEFT JOIN allow_upload_extension saub
    ON sau.id = saub.upload_id AND saub.user_id = users.user_id WHERE 1=1";

        if(!empty($filter['district_id'])){
            $sql .= " AND users.district_id = ".$filter['district_id'];
        }
        if(!empty($filter['month'])){
            $sql .= " AND sau.month = ".$filter['month'];
        }
        if(!empty($filter['year'])){
            $sql .= " AND sau.year = ".$filter['year'];
        }

        return $this->db->query($sql)->getResult();
    }

    //rakesh
    public function getUploadStatus($filter) {
        if(empty($filter['year'])){
            return [];
        }
        if(empty($filter['month'])){
            return [];
        }
        if(empty($filter['district_id'])){
            return [];
        }

        $year = $filter['year'];
        $month = $filter['month'];
        $district_id = $filter['district_id'];

        $sql = "SELECT
  umym.*,sts.id,COALESCE(sts.status,3)status,created_at
FROM (SELECT
    *
  FROM vw_user_modules um
    JOIN vw_all_year_month vaym
  WHERE vaym.year_id = $year
  AND vaym.month_id = $month
  AND district_id = $district_id) umym
  LEFT JOIN (SELECT
  ms.id,
  ms.status,
  ms.district_id,
  ms.block_id,
  ms.agency_type_id,
  created_at,
  'mis' modulecode
FROM mis_submissions ms
WHERE ms.deleted_at IS NULL
AND ms.month = $month
AND ms.year = $year
AND ms.district_id = $district_id
UNION ALL
SELECT
  st.id,
  st.status,
  st.district_id,
  st.block_id,
  st.agency_type_id,
  date_added created_at,
  'expense' modulecode
FROM transactions st
WHERE st.deleted_at IS NULL
AND st.transaction_type = 'expense'
AND st.month = $month
AND st.year = $year
AND st.district_id = $district_id
UNION ALL
SELECT
  st.id,
  st.status,st.district_id,st.block_id,
  st.agency_type_id,date_added created_at,
  'fund_receipt' modulecode
FROM transactions st
WHERE st.deleted_at IS NULL
AND st.transaction_type = 'fund_receipt'
AND st.month = $month
AND st.year = $year
AND st.district_id = $district_id
UNION ALL
SELECT
  smt.id,
  smt.status,smt.district_id,smt.block_id,
  smt.agency_type_id,created_at,
  'other_receipt' modulecode
FROM misc_transactions smt
WHERE smt.deleted_at IS NULL
AND smt.year = $year
AND smt.month = $month
AND smt.district_id = $district_id
UNION ALL
SELECT
  scb.id,
  scb.status, scb.district_id,scb.block_id,
  scb.agency_type_id,created_at,
  'closing_balance' modulecode
FROM closing_balances scb
WHERE scb.deleted_at IS NULL
AND scb.month = $month
AND scb.year = $year
AND scb.district_id = $district_id) sts
    ON sts.modulecode = umym.modulecode
    AND sts.district_id = umym.district_id
    AND sts.block_id = umym.block_id
    AND sts.agency_type_id = umym.user_group_id";

        return $this->db->query($sql)->getResult();
    }
}
