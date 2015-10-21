<?php
namespace Pages\Model;

use Application\Model\AbstractTable;
use Zend\Db\Sql\Select;
 
class PageModel extends AbstractTable
{ 
	protected $pagesTypeTable;
		
	public function __construct($table)
	{
		$this->table = $table;
	}

	protected function setObjectPrototype()
	{
		$this->objectPrototype = new \Pages\Entity\Page();
	}
	
	public function getPageById($pageId, $is_system=0)
	{
		$pageId  = (int) $pageId;
        $rowset = $this->select(array('id'=>$pageId, 'is_system'=>$is_system));
		
		$rowset->setObjectPrototype($this->objectPrototype);
		$row = $rowset->current();
	
		return $row;
	}
	
	public function getPageByRoute($route_name, $route_params=null)
	{
		if($route_params) $route_params = serialize($route_params);
		else $route_params = null;
		
		$select = new Select();
		$select->columns(array(
		'id', 'name', 'title', 'header', 'content', 
			'route_name'=>new \Zend\Db\Sql\Expression("IFNULL(p.route_name, t.route_name)"), 'route_params', 
			'is_active', 'is_delete', 
			'author_id', 'date_creation', 'date_last_modification', 
			'is_system', 'pages_type_id'
		));
		$select->from(array('p' => $this->table));
		$select->join(array('t' => 'pages_type'), 'p.pages_type_id = t.id', array(), Select::JOIN_LEFT);
		$select->where
			->equalTo('p.route_params', $route_params)
			->nest
				->nest
				->isNull('p.route_name')
				->and
				->equalTo('t.route_name', $route_name)
				->unnest
				->or
				->equalTo('p.route_name', $route_name)
			->unnest;
			
		$rowset = $this->executeSelect($select);
	
		
        $rowset->setObjectPrototype($this->objectPrototype);
		$row = $rowset->current();
        if (!$row) throw new \Exception("Could not find page $route_name $route_params");
        return $row;
	}
	
	public function getPageIdByRoute($route_name, $route_params=null)
	{
		if($route_params) $route_params = serialize($route_params);
		else $route_params = null;
		
		$select = new Select();
		$select->columns(array('id'));
		$select->from(array('p' => $this->table));
		$select->join(array('t' => 'pages_type'), 'p.pages_type_id = t.id', array(), Select::JOIN_LEFT);
		$select->where
			->equalTo('p.route_params', $route_params)
			->nest
				->nest
				->isNull('p.route_name')
				->and
				->equalTo('t.route_name', $route_name)
				->unnest
				->or
				->equalTo('p.route_name', $route_name)
			->unnest;
			
		$rowset = $this->executeSelect($select);
	
		$rowset = $this->executeSelect($select);
        $row = $rowset->current();
        if (!$row) return false;
        else return $row['id'];
	}
	
}