<?php
namespace Account\Model;

class Account
{
	protected $id;
	protected $user_id;
	protected $hash;
	protected $ip;
	protected $starttime;
	protected $endtime; 
	protected $closed;
	 
    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
		$this->user_id     = (isset($data['user_id'])) ? $data['user_id'] : null;
		$this->hash     = (isset($data['hash'])) ? $data['hash'] : null;
		$this->ip     = (isset($data['ip'])) ? $data['ip'] : null;
		$this->starttime     = (isset($data['starttime'])) ? $data['starttime'] : null;
		$this->endtime     = (isset($data['endtime'])) ? $data['endtime'] : null;
		$this->closed     = (isset($data['closed'])) ? $data['closed'] : null;
    }
	
}