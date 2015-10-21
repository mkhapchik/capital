<?php
namespace Auth\Model;

use Application\Model\AbstractTable;
use Zend\Db\Sql\Expression;
use Auth\Model\Role;
use Zend\Db\Sql\Select;

class RoleTable extends AbstractTable
{
	protected $table;
	
	public function __construct($table)
	{
		$this->table = $table;
	}
	
	protected function setObjectPrototype()
	{
		$this->objectPrototype = new Role();
	}
	
	public function getRolesByUserId($userId)
	{
		$select = new Select();
		
		$select->from(array('r' => $this->table));
		$select->join(array('m' => 'users_roles_map'), 'r.id = m.role_id', array(), Select::JOIN_LEFT);
		$select->where
			->equalTo('m.user_id', $userId)
			->or
			->equalTo('r.is_guest', 1);
			
		$select->order(array('r.is_guest'=>'DESC'));	
			
			
		$rowset = $this->executeSelect($select);
	
	    $rowset->setObjectPrototype($this->objectPrototype);
		
		$roles = array();
		foreach($rowset as $role)  $roles[$role->id]=$role;
		
        return $roles;
	}

}