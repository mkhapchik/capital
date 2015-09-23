<?php
namespace AbstructReport\Form;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class FilterForm extends Form implements ServiceLocatorAwareInterface
{	
	protected $sm;
	
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
	{
		$this->sm = $serviceLocator;
	}

    public function getServiceLocator()
	{
		return $this->sm;
	}
	
	public function __construct($name)
    {
        parent::__construct($name);
    }
	
	abstract protected function setFields($filter);
	
	public function initForm($filter)
	{
		$this->setAttribute('method', 'get');

		$this->setFields($filter);
		$this->setSubmit();
	}
	
	protected function setSubmit()
	{
		$this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Обновить',
                'id' => 'submit_filter',
				'name' => 'submit',
            ),
        ));
	}
	
	
}
