<?php
namespace Menu\Model;

use Application\Model\AbstractTable;

class MenuTable extends AbstractTable
{ 
	public function __construct($table)
	{
		$this->table = $table;
	}

	public function fetchMenu($name)
    {
		$name_cond = $name ? "AND m.name = '$name'" : '';
		
		try
		{
			$alias_table = $this->sm->get('Aliases\Model\AliasesModel')->getTable();
			
			$query = "SELECT i.*, m.name as menu_name, p.route_name as route_name, p.route_params, a.id as alias_id
				  FROM pages_menu_items i 
				  INNER JOIN pages_menu m ON i.pages_menu_id=m.id 
				  LEFT JOIN pages p ON i.page_id=p.id
				  LEFT JOIN $alias_table a ON p.route_name = a.route AND p.route_params=a.params
				  WHERE i.is_active=1 AND m.is_active=1 AND (p.is_active=1 OR p.is_active IS NULL) AND (p.is_delete=0 OR p.is_delete IS NULL)
				  $name_cond
				  ORDER BY i.ord asc, i.id asc
			";
		}
		catch(\Exception $e)
		{
			$query = "SELECT i.*, m.name as menu_name, p.route_name as route_name, p.route_params, null as alias_id
				  FROM pages_menu_items i 
				  INNER JOIN pages_menu m ON i.pages_menu_id=m.id 
				  LEFT JOIN pages p ON i.page_id=p.id
				  WHERE i.is_active=1 AND m.is_active=1 AND (p.is_active=1 OR p.is_active IS NULL) AND (p.is_delete=0 OR p.is_delete IS NULL)
				  $name_cond
				  ORDER BY i.ord asc, i.id asc
			";
		}
		
		$resultSet = $this->query($query);
        $resultSet = $resultSet->toArray();
		
        return $resultSet;
    }
	
}