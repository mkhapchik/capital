<?php
namespace Auth\Model;

class Session
{
	public $id;
	public $user_id;
	protected $token;
	protected $ip;
	protected $starttime;
	protected $endtime; 
	protected $closed;
	public $lastActivity;
	 
    public function exchangeArray($data)
    {
        $this->id     	 = (isset($data['id'])) 	? $data['id'] : null;
		$this->user_id   = (isset($data['user_id']))? $data['user_id'] : null;
		$this->token      = (isset($data['token'])) 	? $data['token'] : null;
		$this->ip     	 = (isset($data['ip'])) 	? $data['ip'] : null;
		$this->starttime = (isset($data['starttime'])) ? $data['starttime'] : null;
		$this->endtime   = (isset($data['endtime']))   ? $data['endtime'] : null;
		$this->closed    = (isset($data['closed']))    ? $data['closed'] : null;
		$this->lastActivity    = (isset($data['last_activity']))    ? $data['last_activity'] : null;
    }
	
}