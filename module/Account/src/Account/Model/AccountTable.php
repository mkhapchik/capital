<?php
namespace Account\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\AdapterAwareInterface;
use Account\Model\Account;

class AccountTable extends AbstractTableGateway implements AdapterAwareInterface
{
	protected $table = 'account';
 
	public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new HydratingResultSet();
		$this->initialize();
    }
 
    public function fetchAll()
    {
        $resultSet = $this->select(array('f_deleted'=>0));
		$resultSet->setObjectPrototype(new Account());
       	return $resultSet;
    }
 
    public function getAccount($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('id' => $id, 'f_deleted'=>0));
        $rowset->setObjectPrototype(new Account());
		$row = $rowset->current();
        if (!$row) throw new \Exception("Could not find row $id");
        
        return $row;
    }
 
    public function saveAccount(Account $account)
    {
		$data = array(
            'name' => $account->name,
            'comments'  => $account->comments,
			'amount' =>  $account->amount,
			'f_deleted'=>0
        );
 
        $id = (int)$account->id;
        if ($id == 0) 
		{
            $this->insert($data);
        } 
		else 
		{
            if ($this->getAccount($id)) $this->update($data, array('id' => $id));
			else  throw new \Exception('Form id does not exist');
        }
    }
 
    public function deleteAccount($id)
    {
        //$this->delete(array('id' => $id));
		$this->update(array('f_deleted'=>1), array('id' => $id));
    }
}