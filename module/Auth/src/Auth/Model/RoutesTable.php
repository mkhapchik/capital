<?php
namespace Auth\Model;

use Application\Model\AbstractTable;
use Zend\Db\Sql\Select;

class RoutesTable extends AbstractTable
{
	protected $table;
	
	public function __construct($table)
	{
		$this->table = $table;
	}
	
	public function getRouterIdByRoute($route_name, $route_params = null)
	{
		if(!empty($route_params)) $route_params = $this->quoteValue(json_encode($route_params));
		else $route_params = 'null';
		
		$route_name = $this->quoteValue($route_name);
		
		$query = "SELECT S.id FROM (SELECT r.id, IFNULL(a.name, IFNULL(r.route_name, t.route_name)) as route_name FROM routes r 
			LEFT JOIN resource_type t ON r.resource_type_id = t.id 
			LEFT JOIN aliases a ON a.route_id=r.id
			WHERE route_params = $route_params) AS S WHERE S.route_name = $route_name";
		
		
		$rowset=$this->query($query);
		
		$result = $rowset->current();
		
		if($result) return $result->id;
		else return false;
		
		/*
		$select = new Select();
		
		$select->from(array('p' => $this->table));
		$select->join(array('r' => 'users_roles'), 'r.id = p.role', array('role_name'=>'name'), Select::JOIN_INNER);
		$select->where
			->equalTo('p.role', $roles)
			->or
			->equalTo('p.user', $userId);
			
		$select->order(array('p.allow'=>'DESC'));	
			
			
		$rowset = $this->selectWith($select);
		*/
		//return $rowset->toArray();
		
	}
}