<?php
namespace Auth\Model;

use Application\Model\AbstractTable;
use Zend\Db\Sql\Expression;
use Auth\Model\Session;

class SessionTable extends AbstractTable
{
	const METHOD_CLOSE_AUTOMATIC = 'automatic';
	const METHOD_CLOSE_MANUALLY = 'manually';
	
	protected $table = 'session';
 
	protected function setObjectPrototype()
	{
		$this->objectPrototype = new Session();
	}
	
	public function closeAll($user_id, $method_close=self::METHOD_CLOSE_AUTOMATIC)
	{
		$this->update(
			array('closed' => 1, 'method_close' => $method_close, 'endtime'=>new Expression('NOW()')), 
			array('user_id' => $user_id, 'closed'=>0)
		);
	}
	
	public function close($token, $method_close=self::METHOD_CLOSE_AUTOMATIC)
	{
		$this->update(
			array('closed' => 1, 'method_close' => $method_close, 'endtime'=>new Expression('NOW()')), 
			array('token' => $token, 'closed'=>0)
		);
	}
	
	public function getSession($token, $ip)
	{
		$rowset = $this->select(array('token' => $token, 'closed'=>0, 'ip'=>$ip));
        $rowset->setObjectPrototype($this->objectPrototype);
		$row = $rowset->current();
		return $row;
	}
	
	
}