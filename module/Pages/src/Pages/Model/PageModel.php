<?php
namespace Pages\Model;

use Application\Model\AbstractTable;
 
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
        $rowset = $this->select(array('id' => $pageId, 'is_system'=>$is_system));
        $rowset->setObjectPrototype($this->objectPrototype);
		$row = $rowset->current();
        if (!$row) throw new \Exception("Could not find page");
        
        return $row;
	}
	
	public function getPageByRoute($route_name, $route_params=null)
	{
		if($route_params) $route_params = serialize($route_params);
		else $route_params = null;
		
		$rowset = $this->select(array('route_name' => $route_name, 'route_params'=>$route_params));
        $rowset->setObjectPrototype($this->objectPrototype);
		$row = $rowset->current();
        if (!$row) throw new \Exception("Could not find page");
        
        return $row;
	}
}