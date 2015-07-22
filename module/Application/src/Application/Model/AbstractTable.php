<?php
namespace Application\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\AdapterAwareInterface;
use Categories\Model\Category;
use Zend\Db\Sql\Select;
use ArrayObject;

abstract class AbstractTable extends AbstractTableGateway implements AdapterAwareInterface
{
	protected $table;
	protected $adapter;
	protected $connection;
	protected $objectPrototype;
	
	protected function setObjectPrototype()
	{
		$this->objectPrototype = new ArrayObject();
	}
		
    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
	
		$this->resultSetPrototype = new HydratingResultSet();
		$this->initialize();
		
		$driver = $this->adapter->getDriver();
		$this->connection = $driver->getConnection();
		
		$this->setObjectPrototype();
    }
  
	/**
	* Fetch all records from the table
	*/
    public function fetchAll($where=null)
	{
		$resultSet = $this->select($where);
		$resultSet->setObjectPrototype($this->objectPrototype);
		return $resultSet->toArray();
	}
    
	/**
	* Getting a table row by id
	* @param $id - ID string
	* @throw Exception
	*/
    public function get($id)
	{
		$id  = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $rowset->setObjectPrototype($this->objectPrototype);
		$row = $rowset->current();
        if (!$row) throw new \Exception("Could not find row $id");
        
        return $row;
	}
 
 
	/**
	* Saving data
	* @param $data - an array whose keys are field names of the table, and values - their new values
	* @param $id - ID-date records || false - addition (by default false)
	* @throw Exception
	*/
    public function save($data, $id=false)
	{
		if ($id===false) 
		{
			$this->insert($data);
        } 
		else 
		{
           	if($this->get($id)) $this->update($data, array('id' => $id));
			else throw new \Exception('Form id does not exist');
        }
	}
     
	/**
	* Removing records from the table
	* @param $id - row identifier
	*/	
    public function del($id)
	{
		$id = (int)$id;
		$this->delete(array('id' => $id));
	}
    
	/**
	* Gets guide
	* @return array[array('id'=>'id', 'name'=>'name')]
	*/
	public function getGuide()
	{
		$resultSet = $this->select(function(Select $select)
		{
			$select->columns(array('id','name'));
		});
		
		return $resultSet->toArray();
	}
	
	public function beginTransaction()
	{
		return $this->connection->beginTransaction();
	}
	
	public function commit()
	{
		return $this->connection->commit();
	}
		
	public function rollBack()
	{
		return $this->connection->rollBack();
	}
		
}
?>