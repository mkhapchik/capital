<?php
namespace Application\Model;
 
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\AdapterAwareInterface;
 
class MenuTable extends AbstractTableGateway implements AdapterAwareInterface
{
    protected $table = 'menu';
 
    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new HydratingResultSet();
 
        $this->initialize();
    }
 
    public function fetchAll()
    {
        $resultSet = $this->select(function (Select $select){
            $select->where(array('is_active', 1));
			$select->order(array('ord asc', 'id asc'));
        });
 
        $resultSet = $resultSet->toArray();
 
        return $resultSet;
    }
}