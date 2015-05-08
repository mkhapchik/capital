<?php
namespace Categories\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\AdapterAwareInterface;
use Categories\Model\Category;


class CategoryTable extends AbstractTableGateway implements AdapterAwareInterface
{
	protected $table = 'categories';
	
	/**
	* Тип 1 - доход, 0 - расход, null - все типы
	*/
	private $type;
 
    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new HydratingResultSet();
		
        $this->initialize();
    }
 
    public function fetchAll()
    {
		
		//$r = $this->adapter->query("CALL getOverflow('2015-03-01', null);", Adapter::QUERY_MODE_EXECUTE);
		//$r = $this->adapter->query("SELECT * FROM categories", Adapter::QUERY_MODE_EXECUTE);
		
		$query = "SELECT t.categories_id, c.name, c.amount_limit, SUM(t.amount*t.op_sign) AS sum, ABS(SUM(t.amount*t.op_sign))-c.amount_limit AS overflow
		FROM categories c 
		INNER JOIN transactions t ON c.id=t.categories_id 
		WHERE t.date >=DATE_FORMAT('2015-03-01' ,'%Y-%m-01 00.00.00') AND t.date < LAST_DAY('2015-03-01')
		GROUP BY c.id;";
		
		
		$r = $this->adapter->query($query, Adapter::QUERY_MODE_EXECUTE);
		$this->adapter->closeCursor();		
		$result = $r->setArrayObjectPrototype(new Category());
	
		return $result;
	}
 
   
 
    public function getCategory($id)
    {
        /*
		$id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
		*/
    }
 
    public function saveCategory(Category $category)
    {
		/*
		$data = array(
            'name' => $account->name,
            'comments'  => $account->comments,
			'amount' =>  $account->amount
        );
 
        $id = (int)$account->id;
        if ($id == 0) 
		{
            $this->tableGateway->insert($data);
        } 
		else 
		{
            if ($this->getAccount($id)) $this->tableGateway->update($data, array('id' => $id));
			else  throw new \Exception('Form id does not exist');
        }
		*/
    }
 
    public function deleteCategory($id)
    {
        //$this->tableGateway->delete(array('id' => $id));
    }
	
	public function setType($type)
	{
		$this->type = $type;
	}
	
}
?>