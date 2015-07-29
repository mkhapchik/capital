<?php
namespace Auth\Model;

class User
{
	public $id;
    public $login;
    public $pwd;
	public $counter_failures;
	public $blocked;
	public $name;
	 
    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
		$this->login     = (isset($data['login'])) ? $data['login'] : null;
		$this->pwd     = (isset($data['pwd'])) ? $data['pwd'] : null;
		$this->counter_failures     = (isset($data['counter_failures'])) ? $data['counter_failures'] : null;
		$this->blocked     = (isset($data['blocked'])) ? $data['blocked'] : null;
		$this->name     = (isset($data['name'])) ? $data['name'] : null;
    }
	
	public function isBlocked()
	{
		return (bool)$this->blocked;
	}
	
}