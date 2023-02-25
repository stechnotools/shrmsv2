<?php
/**
 * AIO ADMIN
 *
 * @author      Niranjan
 * @copyright   Copyright (c) 2012
 * @license     MIT License
 * @link        http://aioadmin.com
 */
namespace Admin\Setting\Models;
use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'config';
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
        'config_site_title' => array(
            'label' => 'Site Title',
            'rules' => 'trim|required'
        ),
        'config_site_tagline' => array(
            'label' => 'Site Tagline',
            'rules' => "trim|required"
        ),
        'config_meta_title' => array(
            'label' => 'Meta Title',
            'rules' => "trim|required"
        ),
        'config_site_owner' => array(
            'label' => 'Site Owner',
            'rules' => "trim|required"
        ),
        'config_address' => array(
            'label' => 'Site Address',
            'rules' => "trim|required"
        ),
        'config_email' => array(
            'label' => 'Email',
            'rules' => "trim|required|valid_email"
        ),
        'config_telephone' => array(
            'label' => 'Telephone',
            'rules' => "trim|required|numeric"
        ),
        'config_pagination_limit_front' => array(
            'label' => 'Pagination limit For front',
            'rules' => "trim|required|numeric"
        ),
        'config_pagination_limit_admin' => array(
            'label' => 'pagination limit for admin',
            'rules' => "trim|required|numeric"
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


	public function group(){
      $this->db->select("id,name");
		$this->db->from("groups");
		if ($this->Group_session->type != SUPERADMIN)
        {
			$this->db->where("type != ".SUPERADMIN);
		}
		$res = $this->db->get()->result();
        return $res;
	}
	public function getUsers($data = array())
	{
		$this->db->from("users u");
		$this->db->join('groups gp', 'gp.id = u.group_id');
		if ($this->Group_session->type != SUPERADMIN)
        {
			$this->db->where("gp.type != ".SUPERADMIN);
		}
		if (!empty($data['filter_search'])) {
			$this->db->where("(concat_ws(' ', u.first_name, u.last_name) LIKE '%{$data['filter_search']}%' OR u.email LIKE '%{$data['filter_search']}%')");
		}

		if (!empty($data['filter_groupid'])) {
			$this->db->where("u.group_id", $data['filter_groupid']);
		}


		$sort_data = array(
			'u.first_name',
			'u.last_name',
			'u.email',
			'gp.name',
			'u.last_login'
		);
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			//echo "ok";
			$sort = $data['sort'];
		} else {
			$sort = "u.first_name";
		}

		if (isset($data['order']) && ($data['order'] == 'desc')) {
			$order = "desc";
		} else {
			$order = "asc";
		}
		$this->db->order_by($sort, $order); 
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 10;
			}
			$this->db->limit((int)$data['limit'],(int)$data['start']);
		}

		$res = $this->db->get()->result();

		return $res;
	}
	public function getTotalUsers($data = array())
	{
		$this->db->from("users u");
		$this->db->join('groups gp', 'gp.id = u.group_id');
		if ($this->Group_session->type != SUPERADMIN)
        {
			$this->db->where("gp.type != ".SUPERADMIN);
		}
		if (!empty($data['filter_search'])) {
			$this->db->where("(concat_ws(' ', u.first_name, u.last_name) LIKE '%{$data['filter_search']}%' OR u.email LIKE '%{$data['filter_search']}%')");
		}

		if (!empty($data['filter_groupid'])) {
			$this->db->where("u.group_id", $data['filter_groupid']);
		}

		$count = $this->db->count_all_results();

		return $count;
	}
	public function getGroups($data = array())
	{
		$this->db->from("groups");
		if ($this->Group_session->type != SUPERADMIN)
        {
			$this->db->where("type != ".SUPERADMIN);
		}

		$sort_data = array(
			'name'
		);
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			//echo "ok";
			$sort = $data['sort'];
		} else {
			$sort = "name";
		}

		if (isset($data['order']) && ($data['order'] == 'desc')) {
			$order = "desc";
		} else {
			$order = "asc";
		}
		$this->db->order_by($sort, $order); 
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 10;
			}
			$this->db->limit((int)$data['limit'],(int)$data['start']);
		}

		$res = $this->db->get()->result();

		return $res;
	}
	public function getTotalGroups($data = array())
	{
		$this->db->from("groups");
		if ($this->Group_session->type != SUPERADMIN)
        {
			$this->db->where("type != ".SUPERADMIN);
		}
		$count = $this->db->count_all_results();

		return $count;
	}
	public function getUserGroup($user_group_id) {
		$this->db->distinct();
		$this->db->where("id",$user_group_id);
		$query=$this->db->get('groups')->row();
		
		$user_group = array(
			'name'      	=> $query->name,
			'type'			=> $query->type,
			'permissions'	=> unserialize($query->permissions)
		);

		return $user_group;
	}
	public function groupname_check($name)
	{
		$this->db->where('name', $name);
		$query = $this->db->get('groups');
		$Group=$query->row();
		return $Group;
	}
	public function editUserGroup($user_group_id, $data) {
		$this->db->where("id",$user_group_id);
        $status=$this->db->update("groups", $data);
        
        if($status) 
        return "success";
	}
	public function getCountries($data = array()) {
		$this->db->from("country");
			
		$sort_data = array(
			'country_name',
			'country_code',
		);
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			//echo "ok";
			$sort = $data['sort'];
		} else {
			$sort = "country_name";
		}
		if (isset($data['order']) && ($data['order'] == 'desc')) {
			$order = "desc";
		} else {
			$order = "asc";
		}
		$this->db->order_by($sort, $order); 	
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 10;
			}
			$this->db->limit((int)$data['limit'],(int)$data['start']);
		}

		$res = $this->db->get()->result();
	
		return $res;
		 
	}
	
	public function getCityByStateId($state_id) {
		$this->db->from("city");
		$this->db->where("city_state_id",$state_id);
		$res = $this->db->get()->result();
		return $res;
	}
	public function getPages() {
		$this->db->from("pages");
		$this->db->where("status","published");
		$res = $this->db->get()->result();
		return $res;
	}
	public function getBanners() {
		$this->db->from("banners");
		$this->db->where("status",1);
		$res = $this->db->get()->result();
		return $res;
	}
	public function getSliders() {
		$this->db->from("sliders");
		$this->db->where("status",1);
		$res = $this->db->get()->result();
		return $res;
	}
	public function editSetting($module, $data) {
        $builder=$this->db->table("{$this->table}");
        $builder->where("module",$module);
	    $builder->delete();

		foreach ($data as $key => $value) {
			//echo substr($key, 0, strlen($module));
			if (substr($key, 0, strlen($module)) == $module) {
				
				if (!is_array($value)) {
					$builder->insert(array("key"=>$key,"value"=>$value,"module"=>$module));
				} else {
                    $builder->insert(array("key"=>$key,"value"=>json_encode(array_values($value), true),"module"=>$module,"serialized"=>1));
				}
			}
		}
		//exit;	
	}
    public function getDashboardReports($dashboard=1) {
        $builder=$this->db->table("dashboard_report");

        //$builder->where("status",1);
        $builder->where("dashboard",$dashboard);

        $res=$builder->get()->getResult();
        return $res;
    }
    public function saveDashboard($id,$data){
        $builder=$this->db->table("dashboard_report");
        $builder->where("id",$id);
        $builder->update($data);
    }
}
