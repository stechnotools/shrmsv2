<?php
namespace Admin\Site\Models;
use CodeIgniter\Model;

class SiteModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'site';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'object';
    protected $useSoftDelete        = false;
    protected $protectFields        = false;
    protected $allowedFields        = [];

    // Dates
    protected $useTimestamps        = true;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'name' => array(
            'label' => 'name',
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


    public function __construct(){
		parent::__construct();
	}

    public function getAll($data = array()){
        $builder=$this->db->table($this->table);
        $this->filter($builder,$data);

        $builder->select("*");

        if (isset($data['sort']) && $data['sort']) {
            $sort = $data['sort'];
        } else {
            $sort = "id";
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
        $builder=$this->db->table($this->table);
        $this->filter($builder,$data);
        $count = $builder->countAllResults();
        return $count;
    }

    private function filter($builder,$data){
        $builder->where($this->deletedField, null);
        if (!empty($data['filter_search'])) {
            $builder->where("
				name LIKE '%{$data['filter_search']}%'
				OR code LIKE '%{$data['filter_search']}%'
				OR short LIKE '%{$data['filter_search']}%')"
            );
        }
    }

    public function addSite($data){
        $sitedata=array(
			"name"=>$data['name'],
			"code"=>$data['code'],
			"address"=>$data['address'],
			"status"=>$data['status']
		);
        $builder=$this->db->table($this->table);
        $builder->insert($sitedata);

        $site_id=$this->db->insertID();
        if (isset($data['site_salary'])) {
            $builder=$this->db->table("site_salaries");
			foreach ($data['site_salary'] as $site_salary) {
				$site_salary_data=array(
					"site_id"=>$site_id,
					"designation_id"=>$site_salary['designation_id'],
					"type"=>$site_salary['type'],
					"salary"=>$site_salary['salary'],
				);
                $builder->insert($site_salary_data);
			}
		}

    }

    public function editSite($id,$data){
        $sitedata=array(
            "name"=>$data['name'],
            "code"=>$data['code'],
            "address"=>$data['address'],
            "status"=>$data['status']
        );

        $builder=$this->db->table($this->table);
        $builder->where("id",$id);
        $builder->update($sitedata);

        $builder=$this->db->table("site_salaries");
        $builder->where("site_id",$id);
        $builder->delete();

        if (isset($data['site_salaries'])) {
            $builder=$this->db->table("site_salaries");
            foreach ($data['site_salaries'] as $site_salary) {
                $site_salary_data=array(
                    "site_id"=>$id,
                    "designation_id"=>$site_salary['designation_id'],
                    "type"=>$site_salary['type'],
                    "salary"=>$site_salary['salary'],
                );
                $builder->insert($site_salary_data);

            }
        }
    }

    public function getSiteSalaries($id) {
        $builder=$this->db->table("site_salaries");
        $builder->select("*");
        $builder->where("site_id",$id);
        $res = $builder->get()->getResultArray();
        return $res;
    }





}
