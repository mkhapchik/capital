<?php
namespace Auth\Model;

use Application\Model\AbstractTable;
use Zend\Db\Sql\Select;

class PermissionsTable extends AbstractTable
{
	protected $table;
	
	public function __construct($table)
	{
		$this->table = $table;
	}
	
	public function getPermissions($roles, $userId=false)
	{
		$select = new Select();
		
		$select->from(array('p' => $this->table));
		$select->join(array('r' => 'users_roles'), 'r.id = p.role', array('role_name'=>'name'), Select::JOIN_LEFT);
		$select->join(array('pr' => 'privileges'), 'p.privilege = pr.id', array('privilege_name'=>'name'), Select::JOIN_LEFT);
		$select->where
			->equalTo('p.role', $roles)
			->or
			->equalTo('p.user', $userId);
			
		$select->order(array('p.user'=>'ASC', 'p.allow'=>'DESC'));	
			
			
		$rowset = $this->selectWith($select);
		return $rowset->toArray();
		
	}
}