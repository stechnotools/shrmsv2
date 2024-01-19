<?php
namespace Admin\LeaveOpening\Models;
use CodeIgniter\Model;

class LeaveOpeningModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'leave_opening';
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
        'leave_field.*' => array(
            'label' => 'leave_field',
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

    public function addLeaveOpening($data) {
        $builder = $this->db->table("leave_opening");
		$fyear=$data['fyear'];
		$fyear=explode("-",$fyear);
		$leave_openingdata=array(
			"type"=>$data['type'],
			"year_from"=>$fyear[0].'-04-01',
			"year_to"=>$fyear[1].'-03-31',
			"paycode_from"=>isset($data['paycode_from']) ?  $data['paycode_from']:'',
			"paycode_to"=>isset($data['paycode_to']) ? $data['paycode_to']:'',
			"department_id"=>isset($data['department_id']) ? $data['department_id']:0,
			"branch_id"=>isset($data['branch_id']) ? $data['branch_id']:0,
			"created_at"=>date('Y-m-d'),
		);
        $builder->insert($leave_openingdata);
        $leave_opening_id=$this->db->insertID() ;

        $builder = $this->db->table("leave_opening_value");
		foreach($data['leave_field'] as $leave_id=>$value){
			$leave_field=array(
				'leave_opening_id'=>$leave_opening_id,
				'leave_id'=>$leave_id,
				'value'=>$value
			);
			$builder->insert($leave_field);
		}

		return $leave_opening_id;
	}
	public function editLeaveOpening($leave_opening_id, $data) {
        $builder = $this->db->table("leave_opening");

		$fyear=$data['fyear'];
		$fyear=explode("-",$fyear);
        $leave_openingdata=array(
			"type"=>$data['type'],
			"year_from"=>$fyear[0].'-04-01',
			"year_to"=>$fyear[1].'-03-31',
			"paycode_from"=>isset($data['paycode_from']) ?  $data['paycode_from']:'',
			"paycode_to"=>isset($data['paycode_to']) ? $data['paycode_to']:'',
			"department_id"=>isset($data['department_id']) ? $data['department_id']:0,
			"branch_id"=>isset($data['branch_id']) ? $data['branch_id']:0,
			"updated_at"=>date('Y-m-d'),
		);
        $builder->where("id",$leave_opening_id);
		$builder->update($leave_openingdata);

		//delete
        $builder = $this->db->table("leave_opening_value");
		$builder->where("leave_opening_id", $leave_opening_id);
		$builder->delete();

		foreach($data['leave_field'] as $leave_id=>$value){
			$leave_field=array(
				'leave_opening_id'=>$leave_opening_id,
				'leave_id'=>$leave_id,
				'value'=>$value
			);
			$builder->insert($leave_field);
		}
	}

    public function getAll($data = array()){
        $builder=$this->db->table($this->table);
        $this->filter($builder,$data);

        $builder->select("*");

        if (isset($data['sort']) && $data['sort']) {
            $sort = $data['sort'];
        } else {
            $sort = "type";
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
				type LIKE '%{$data['filter_search']}%'
				OR code LIKE '%{$data['filter_search']}%'
				OR short LIKE '%{$data['filter_search']}%')"
            );
        }
    }




}
