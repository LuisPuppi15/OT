<?php
namespace Sistema\Form;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element as ZendElement;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class DatosespacialesForm extends Form implements InputFilterProviderInterface {
	protected $em;

	function __construct($em) {
		parent::__construct();

		$this->em = $em;

		$hydrator = new DoctrineHydrator($this->em, 'Sistema\Entity\Datosespaciales');
		$this->setHydrator($hydrator);

		$this->init();
	}

	public function init() {
		// cdatespnombre
		$cdatespnombre = new ZendElement\Text('cdatespnombre');
		$cdatespnombre->setLabel('Nombre');
		$cdatespnombre->setAttributes(array(
			'required' => true
		));
		$this->add($cdatespnombre);

		// cdatespcontenido
		$cdatespcontenido = new ZendElement\Textarea('cdatespcontenido');
		$cdatespcontenido->setLabel('Contenido');
		$cdatespcontenido->setAttributes(array(
			'required' => false,
			'class' => 'ckeditor'
		));
		$this->add($cdatespcontenido);

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

	public function getInputFilterSpecification() {
		return array();
	}
}
?>