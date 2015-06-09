<?php
namespace Transactions\Model;

use Transactions\Model\AbstractTable;
use Transactions\Entity\Transaction;
use Zend\Db\Adapter\Adapter;

class TransactionTable extends AbstractTable
{
	protected $table = 'transactions';
	
	/**
	* Тип 1 - доход, 0 - расход
	*/
	private $type;
 
	public function setType($type)
	{
		$this->type = $type;
	}
    	
    public function save(Transaction $transaction)
    {
		$query = "CALL transactions('{$transaction->date}', '{$transaction->amount}', '{$transaction->categories_id}', '{$transaction->account_id}', '{$transaction->comment}')";
		$r = $this->adapter->query($query, Adapter::QUERY_MODE_EXECUTE);
		$result = $r->toArray();
		
		$id = 0;
		if(is_array($result))
		{
			$row = array_shift($result); 
			$id = $row['id'];
		}			
		return $id;
    }

	public function getGuide()
	{
		throw new \Exception('Function can not be used!');
	}
}
?>