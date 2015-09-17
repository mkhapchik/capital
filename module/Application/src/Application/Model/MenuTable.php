<?php
namespace Application\Model;

use Zend\Db\Sql\Select;
 
class MenuTable extends AbstractTable
{ 
    public function __construct($table)
	{
		$this->table = $table;
	}

    public function fetchAllItem()
    {
        $resultSet = $this->select(function (Select $select){
            $select->where(array('is_active', 1));
			$select->order(array('ord asc', 'id asc'));
        });
 
        $resultSet = $resultSet->toArray();
 
        return $resultSet;
    }
}