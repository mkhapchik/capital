<?php
namespace Application\Service;

use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

class SendMailService
{
	protected $options;
	/**
	*  send email
	*  @param ARRAY $options
			'smtp'=>array(
				'server'=>SMTP_SERVER,
				'login'=>'SMTP_LOGIN',
				'pswd'=>SMTP_PSWD,
				'port'=>SMTP_PORT,
			),
			'message'=>array(
				'to'=>email to,
				'from'=>from,
				'subject'=>'subject';
				'encoding'=>'utf-8',
				'body'=>"text"
			)
	
	*/
	public function setOptions($options)
	{
		$this->options = $options;
	}
	
	public function getOptions()
	{
		return $this->options;
	}
	
	public function setOption($key, $value)
	{
		$this->options[$key] = $value;
	}
	
	public function getOption($key)
	{
		if($this->hasOption($key)) return $this->options[$key];
		else return null;
	}
	
	public function hasOption($key)
	{
		if(is_array($this->options) && array_key_exists($key, $this->options)) return true;
		else return false;
	}
	
	public function send()
	{
		$options = $this->getOptions();
		
		$message = new Message();
		$message->addTo($options['message']['to'])
				->addFrom($options['smtp']['login'])
				->setSubject($options['message']['subject'])
				->setBody($options['message']['body'])
				->setEncoding($options['message']['encoding']);

		$transport = new SmtpTransport();
		$smtp_options   = new SmtpOptions(array(
			'host'              => $options['smtp']['server'],
			'connection_class'  => 'login',
			'connection_config' => array(
				'username' => $options['smtp']['login'],
				'password' => $options['smtp']['pswd'],
			),
			'port'=>$options['smtp']['port']
		));
		$transport->setOptions($smtp_options);
		
		
		$transport->send($message);
	}
}

?> 