<?php
namespace Aliases\Model;
use Application\Model\AbstractTable;
use Zend\Db\Sql\Select;

class AliasesModel extends AbstractTable
{
	public function __construct($table)
	{
		$this->table = $table;
	}
	
	public function add()
	{
		
	}
	
	public function getAliases()
	{
		$select = new Select();
		$select->columns(array(
			'uri',
			'name',
			'route_name' => new \Zend\Db\Sql\Expression("IFNULL(r.route_name, t.route_name)"),
			
		));
		$select->from(array('a' => $this->table));
		$select->join(array('r' => 'routes'), 'a.route_id = r.id', array('route_params'), Select::JOIN_INNER);
		$select->join(array('t' => 'resource_type'), 'r.resource_type_id = t.id', array(), Select::JOIN_LEFT);
				
		$rowset = $this->selectWith($select);
				
		$aliases = $rowset->toArray();
		if(count($aliases)>0)
		{
			//if(!empty($aliases['route_params'])) $aliases['route_params'] = (array)json_decode($aliases['route_params']);
			return $aliases;
		}
		else return false;
	}
	
	public function match($uri)
	{
		
		
		$select = new Select();
		$select->columns(array(
			'route_name' => new \Zend\Db\Sql\Expression("IFNULL(r.route_name, t.route_name)"),
			'uri'
		));
		$select->from(array('a' => $this->table));
		$select->join(array('r' => 'routes'), 'a.route_id = r.id', array('route_params'), Select::JOIN_INNER);
		$select->join(array('t' => 'resource_type'), 'r.resource_type_id = t.id', array(), Select::JOIN_LEFT);
		
		$select->where->equalTo('a.uri', $uri);
		
		$rowset = $this->selectWith($select);
				
		$aliases = $rowset->current();
		if(count($aliases)>0)
		{
			if(!empty($aliases['route_params'])) $aliases['route_params'] = (array)json_decode($aliases['route_params']);
			return $aliases;
		}
		else return false;
		/*
		$hfu = $this->trim($hfu);
		$rowset = $this->select(array('hfu' => $hfu));
		$result = $rowset->toArray();
		if(count($result)>0)
		{
			$row = $result[0];
			$row['params'] = unserialize($row['params']);
			return $row;
		}
		else return false;
		*/
	}
	
	private function trim($val)
	{
		$val = '/'.trim($val, '/');
		return $val;
	}
	
	public function getRouteNameByAliasId($aliasId)
	{
		return "route_".$aliasId;
	}
}
