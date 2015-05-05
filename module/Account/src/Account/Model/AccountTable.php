<?php
namespace Account\Model;
use Zend\Db\TableGateway\TableGateway;

class AccountTable
{
	protected $tableGateway;
 
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
 
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }
 
    public function getAccount($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
 
    public function saveAccount(Account $account)
    {
		
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
    }
 
    public function deleteAccount($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}