<?php
namespace Sistema\Form;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element as ZendElement;
use Zend\Form\Form;

class InfoterritorialForm extends Form {
	protected $em;

	function __construct($em) {
		parent::__construct();

		$this->em = $em;

		$hydrator = new DoctrineHydrator($this->em, 'Sistema\Entity\Infoterritorial');
		$this->setHydrator($hydrator);

		$this->init();
	}

	public function init() {
		// cinfternombre
		$cinfternombre = new ZendElement\Text('cinfternombre');
		$cinfternombre->setLabel('Nombre');
		$cinfternombre->setAttributes(array(
			'required' => true
		));
		$this->add($cinfternombre);

		// cinfterurl
		$cinfterurl = new ZendElement\Text('cinfterurl');
		$cinfterurl->setLabel('Url');
		$cinfterurl->setAttributes(array(
			'required' => true
		));
		$this->add($cinfterurl);

		// submit
		$submit = new ZendElement\Button('enviar');
		$submit->setLabel('Guardar');
		$submit->setAttributes(array(
			'type' => 'submit',
			'class' => 'btn btn-modal enviar'
		));
		$submit->setOptions(array(
			'label' => 'Guardar'
		));
		$this->add($submit);
	}

	public function getInputFilterSpecificiation() {
		return array();
	}
}
?>