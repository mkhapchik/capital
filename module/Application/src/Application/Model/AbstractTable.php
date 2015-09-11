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
	
	public function query($query, $mode = Adapter::QUERY_MODE_EXECUTE)
	{
		return $this->adapter->query($query, $mode);
	}
  
	/**
	* Fetch all records from the table
	*/
    public function fetchAll($where=null, $paginated=false)
	{
		if($paginated) 
		{
            $select = new Select($this->table);
			$select->where($where);
            $paginator = $this->getPaginator($select);
			return $paginator;
        }
		else
		{
			$resultSet = $this->select($where);
			$resultSet->setObjectPrototype($this->objectPrototype);
			return $resultSet->toArray();
		}
	}
	
	protected function getPaginator(Select $select)
	{
		$paginatorAdapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->getAdapter(), $this->resultSetPrototype);
		$paginator = new \Zend\Paginator\Paginator($paginatorAdapter);
            
		return $paginator;
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
	
	/**
	*  quote value
	*  @param $val
	*  @return quote val
	*/
	public function quoteValue($val)
	{
		return $this->adapter->platform->quoteValue($val);
	}
	
	/**
	* Call procedure
	* @param $name - procedure's name
	* @param $params - procedure's params
	* @return array
	*/
	public function callProcedure($name, $params=false)
	{
		if(is_array($params) && count($params)>0)
		{
			
			foreach($params as &$param)
			{
				if($param==null) $param = 'null';
				else $param = $this->quoteValue($param);
			}
			
		}
		else
		{
			$params = array();
		}

		$query = "CALL $name(".implode(',', $params).")";
		
		return  $this->query($query);
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