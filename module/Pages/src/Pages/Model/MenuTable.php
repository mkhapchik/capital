<?php
namespace Pages\Model;

use Application\Model\AbstractTable;
 
class MenuTable extends AbstractTable
{ 
    protected $menu;
	
	public function __construct($table)
	{
		$this->table = $table;
	}

    public function getMenu($name=false)
	{
		if(!$this->hasMenu($name))
		{
			$data = $this->generateMenu($name);
			$this->setMenu($data, $name);
		}
		
		return $name===false ? $this->menu : $this->menu[$name];
	}
	
	public function setMenu($data, $name=false)
	{
		if(!is_array($this->menu)) $this->menu = array();
		$this->menu = array_merge_recursive($this->menu, $data);
	}
	
	public function hasMenu($name)
	{
		if(is_array($this->menu) && ($name===false || array_key_exists($name, $this->menu))) return true;
		else return false;
	}
	
	public function generateMenu($name)
	{
		$fetchData = $this->fetchMenu($name);
		$structuredList = $this->makeStructuredList($fetchData);
		
		$config = array();
		
		foreach($structuredList as $name=>$menu)
		{
			foreach($menu[0] as $item)
			{
				$config[$name][] = $this->makePages($name, $structuredList, $item);
			}
		}
		
		return $config;
	}
	
	public function fetchMenu($name)
    {
        $name_cond = $name ? "AND m.name = '$name'" : '';
		
		$query = "SELECT i.*, m.name as menu_name, IFNULL(p.route_name, t.route_name) as route_name, p.route_params
				  FROM pages_menu_items i 
				  INNER JOIN pages_menu m ON i.pages_menu_id=m.id 
				  LEFT JOIN pages p ON i.page_id=p.id 
				  LEFT JOIN pages_type t ON p.pages_type_id=t.id 
				  WHERE i.is_active=1 AND m.is_active=1 AND (p.is_active=1 OR p.is_active IS NULL) AND (p.is_delete=0 OR p.is_delete IS NULL)
				  $name_cond
				  ORDER BY i.ord asc, i.id asc
		";
		
		$resultSet = $this->query($query);
        $resultSet = $resultSet->toArray();
		
        return $resultSet;
    }
	
	private function makeStructuredList($data)
	{
		$list = array();
		
		foreach($data as $row)
		{
			$list[$row['menu_name']][$row['parent_item_id']][]=$row;
		}
		
		return $list;
	}
	
	private function makePages($name, $structuredList, $item, $deep=0)
	{
		$pages = array();
		$pages['label'] = $item['label'];
		if(!empty($item['uri']))
		{
			$pages['uri'] = $item['uri'];
		}
		else if(empty($item['route_name']))
		{
			$pages['uri'] = '';
		}
		else
		{
			$pages['route'] = $item['route_name'];
		}
		
		$pages['pages'] = array();
		
		if(isset($structuredList[$name][$item['id']]))
		{
			foreach($structuredList[$name][$item['id']] as $i)
			{
				
				$pages['pages'][]=$this->makePages($name, $structuredList, $i, $deep + 1);
			}
		}
			
		return $pages;
		
		
			
	}
}