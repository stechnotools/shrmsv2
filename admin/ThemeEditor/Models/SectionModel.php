<?php
namespace Admin\Section\Models;
use CodeIgniter\Model;

class SectionModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'section';
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

   


}
