<?php
namespace Transactions\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\AdapterAwareInterface;
use Categories\Model\Category;
use Zend\Db\Sql\Select;

abstract class AbstractTable extends AbstractTableGateway implements AdapterAwareInterface
{
	protected $table;
	protected $adapter;
	protected $connection;
	
    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
	
		$this->resultSetPrototype = new HydratingResultSet();
		$this->initialize();
		
		$driver = $this->adapter->getDriver();
		$this->connection = $driver->getConnection();
    }
  
	/**
	* Выборка всех записей из таблицы
	*/
    public function fetchAll($where=null)
	{
		$resultSet = $this->select($where);
		return $resultSet->toArray();
	}
    
	/**
	* Получение строки таблицы по id
	* @param $id - идентификатор строки
	* @throw Exception
	*/
    public function get($id)
	{
		$id  = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) throw new \Exception("Could not find row $id");
        
        return $row;
	}
 
 
	/**
	* Сохранение данных
	* @param $data - массив, ключи которого имена полей таблицы, а значения - их новые значения
	* @param $id - идентификатор обновляемой записи || 0 - добавление (по-умолчанию 0)
	* @throw Exception
	*/
    public function save($data, $id=0)
	{
		if ($id) 
		{
			if($this->get($id)) $this->update($data, array('id' => $id));
			else throw new \Exception('Form id does not exist');
        } 
		else 
		{
           	$this->insert($data);
        }
	}
     
	/**
	* Удаление записи из таблицы
	* @param $id - идентификатор строки
	*/	
    public function del($id)
	{
		$id = (int)$id;
		$this->delete(array('id' => $id));
	}
    
	/**
	* Получает справочник
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