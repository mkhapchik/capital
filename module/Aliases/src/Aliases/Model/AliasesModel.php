<?php
namespace Aliases\Model;
use Application\Model\AbstractTable;

class AliasesModel extends AbstractTable
{
	public function __construct($table)
	{
		$this->table = $table;
	}
	
	public function add()
	{
		
	}
		
	public function match($hfu)
	{
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
