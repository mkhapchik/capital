<?php
namespace Auth\Model;

use Application\Model\AbstractTable;
use Zend\Db\Sql\Expression;
use Auth\Model\User;
use ArrayObject;

class UserTable extends AbstractTable
{
	protected $table = 'users';
	
	protected function setObjectPrototype()
	{
		$this->objectPrototype = new User();
	}
	
	public function incrementCounterFailures($login, $max_counter_failures=false)
	{
		$this->update(
			array(
				'counter_failures' => new Expression('counter_failures + 1'),
				'blocked' => new Expression("counter_failures>$max_counter_failures")
			), 
			array('login' => $login, 'blocked'=>0));
	}
	
	public function getUserByLogin($login)
	{
		$rowset = $this->select(array('login' => $login));
        //$rowset->setObjectPrototype(new User());
		$rowset->setObjectPrototype($this->objectPrototype);
		$row = $rowset->current();
        if (!$row) throw new \Exception("Could not find row $id");
        
        return $row;
	}

}