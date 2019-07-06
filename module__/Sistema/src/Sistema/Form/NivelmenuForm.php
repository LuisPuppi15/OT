<?php
namespace Sistema\Form;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element as ZendElement;
use Zend\Form\Form;

class NivelmenuForm extends Form {
	protected $em;

	function __construct($em) {
		parent::__construct();

		$this->em = $em;

		$hydrator = new DoctrineHydrator($this->em, 'Sistema\Entity\Nivelmenu');
		$this->setHydrator($hydrator);

		$this->init();
	}

	public function init() {
		// cnivmennombre
		$cnivmennombre = new ZendElement\Text('cnivmennombre');
		$cnivmennombre->setLabel('Nombre');
		$cnivmennombre->setAttributes(array(
			'required' => true
		));
		$this->add($cnivmennombre);

		// cnivmendescripcion
		$cnivmendescripcion = new ZendElement\Textarea('cnivmendescripcion');
		$cnivmendescripcion->setLabel('Descripción');
		$this->add($cnivmendescripcion);

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