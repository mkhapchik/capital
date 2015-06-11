<?php
namespace Transactions\Model;

use Transactions\Model\AbstractTable;
use Transactions\Entity\Transaction;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
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
	
	public function getComments($tmp)
	{
		$op_sign = $this->type==1 ? 1 : -1;
		
		$resultSet=$this->select(function(Select $select) use($tmp, $op_sign){
			$select->quantifier(SELECT::QUANTIFIER_DISTINCT);
			$select->columns(array('comment'));
			$select->order(array('date '.SELECT::ORDER_DESCENDING));
			$select->where(function(Where $where) use($tmp, $op_sign) 
			{
				$where->like("comment", "%$tmp%");
				$where->equalTo('op_sign', $op_sign);
			});
			
			$select->limit(10);
		});
		
		return $resultSet->toArray();
	}
}
?>