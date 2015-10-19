<?php
namespace Application\Service;

use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Mime as Mime;

class SendMailService
{
	const TYPE_INTERNAL = 'INTERNAL';
	const TYPE_EXTERNAL = 'EXTERNAL';
	
	protected $options;
	protected $transport;
	
	/**
	 *  Тип отправки сообщений
	 *  1 - php mail
	 *  0 - smtp
	 */
	protected $type;
	/**
	*  send email
	*  @param ARRAY $options
			'smtp'=>array(
				'server'=>SMTP_SERVER,
				'login'=>'SMTP_LOGIN',
				'pswd'=>SMTP_PSWD,
				'port'=>SMTP_PORT,
			),
			'messages'=>array(array(
				'to'=>email to,
				'from'=>from,
				'subject'=>'subject';
				'body'=>"text"
			))
	
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
		
		$this->init();
		
		foreach($options['messages'] as $m)
		{
			$this->sendMessage($m);
		}
	}
	
	public function init()
	{
		$options = $this->getOptions();
		$this->type = $options['type'];
		
		$this->transport = new SmtpTransport();
		
		if($this->type == self::TYPE_EXTERNAL)
		{
			$smtp_options   = new SmtpOptions(array(
				'host'              => $options['smtp']['server'],
				'connection_class'  => 'login',
				'connection_config' => array(
					'username' => $options['smtp']['login'],
					'password' => $options['smtp']['pswd'],
				),
				'port'=>$options['smtp']['port']
			));
			$this->transport->setOptions($smtp_options);
		}
	}
	
	/**
	 *  @param ARRAY $m = array(
	 *  	'to'
	 *  	'from'
	 *  	'subject'
	 *  	'body'
	 *  	'encoding'
	 *  )
	 */
	public function sendMessage($m)
	{
		$message = new Message();
		
		if(is_array($m['to'])) foreach($m['to'] as $email) $message->addTo($email);
		else $message->addTo($m['to']);
	
		$message->addFrom($m['from']);
		$message->setSubject($m['subject']);
		
		$body = $this->getBody($m['body']);
		$message->setBody($body);
		
		$message->setEncoding('utf-8');
		
		$this->transport->send($message);
	}
	
	protected function getBody($text)
	{
		$text = new MimePart($text);
		$text->type = Mime::TYPE_TEXT;
		$text->encoding = Mime::ENCODING_QUOTEDPRINTABLE;
		$text->charset = 'utf-8';
		$body = new MimeMessage();
		$body->setParts(array($text));
		
		return $body;
	}
}

?> 