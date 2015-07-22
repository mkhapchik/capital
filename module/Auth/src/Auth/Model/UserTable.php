<?php
namespace Auth\Model;

use Application\Model\AbstractTable;
use Zend\Db\Sql\Expression;
use Auth\Model\User;

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
			array('login' => $login, 'blocked'=>0)
		);
	}
	
	public function unlock($user_id)
	{
		$this->update(array('blocked' => 0, 'counter_failures' => 0), array('id' => $user_id));
	}
	
	public function lock($user_id)
	{
		$this->update(array('blocked' => 1, 'counter_failures' => 0), array('id' => $user_id));
	}
	
	public function getUserByLogin($login)
	{
		$rowset = $this->select(array('login' => $login));
        $rowset->setObjectPrototype($this->objectPrototype);
		$row = $rowset->current();
        if (!$row) throw new \Exception("Could not find row");
        
        return $row;
	}

}