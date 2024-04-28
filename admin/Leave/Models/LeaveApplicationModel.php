<?php
namespace Admin\Leave\Models;
use CodeIgniter\Model;

class LeaveApplicationModel extends Model
{

    protected $DBGroup              = 'default';
    protected $table                = 'leave_application';
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
        'branch_id' => array(
            'label' => 'Branch',
            'rules' => 'trim|required|max_length[100]'
        ),
        'user_id' => array(
            'label' => 'User',
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


	public function editLeaveApplication($leave_opening_id, $data) {
        $builder = $this->db->table($this->table);

		$fyear=$data['fyear'];
		$fyear=explode("-",$fyear);
        $leave_openingdata=array(
			"type"=>$data['type'],
			"year_from"=>$fyear[0].'-04-01',
			"year_to"=>$fyear[1].'-03-31',
			"user_id"=>isset($data['user_id']) ?  $data['user_id']:'',
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
        $builder=$this->db->table("{$this->table} la");
        $this->filter($builder,$data);


        if (isset($data['sort']) && $data['sort']) {
            $sort = $data['sort'];
        } else {
            $sort = "la.paycode";
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
        $builder=$this->db->table("{$this->table} la");
        $this->filter($builder,$data);
        $count = $builder->countAllResults();
        return $count;
    }

    private function filter($builder,$data){

        $builder->where("la.{$this->deletedField}", null);
        if (!empty($data['filter_search'])) {
            $builder->where("
				la.paycode LIKE '%{$data['filter_search']}%'
                OR la.leave_code LIKE '%{$data['filter_search']}%'"
            );
        }
    }

    public function getLeaveApplicationValues($id){
        $builder=$this->db->table("leave_opening_value");
		$builder->where("leave_opening_id",$id);
		$res = $builder->get()->getResult();
		return $res;
	}

    public function getLeaveTakenByUser($filter=[]){
		$query = $this->db->table('leave_application')
                  ->select('leave_id')
                  ->select("SUM(DATEDIFF(LEAST(leave_to, '" . $filter['to_date'] . "'), GREATEST(leave_from, '" . $filter['from_date'] . "')) + 1) AS leave_taken_total")
                  ->where('user_id', $filter['user_id'])
                  ->where('leave_from <=', $filter['to_date'])
                  ->where('leave_to >=', $filter['from_date'])
                  ->groupBy('leave_id')
                  ->get()
                  ->getResultArray();

        return $query;
	}

   

}
