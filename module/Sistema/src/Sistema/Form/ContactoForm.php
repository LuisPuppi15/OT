<?php
namespace Sistema\Form;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element as ZendElement;
use Zend\Form\Form;

class ContactoForm extends Form {
	protected $em;
	protected $tipo;

	function __construct($em, $tipo = null) {
		parent::__construct();

		$this->em = $em;
		$this->tipo = $tipo;

		$hydrator = new DoctrineHydrator($this->em, 'Sistema\Entity\Contacto');
		$this->setHydrator($hydrator);

		$this->init();
	}

	public function init() {
		// titulo
		$titulo = new ZendElement\Text('titulo');
		$titulo->setLabel('Título');
		$titulo->setAttributes(array(
			'required' => "true"
		));
		$this->add($titulo);

		// direccion
		$direccion = new ZendElement\Textarea('direccion');
		$direccion->setLabel('Dirección');
		$direccion->setAttributes(array(
		));
		$this->add($direccion);

		// telefono
		$telefono = new ZendElement\Text('telefono');
		$telefono->setLabel('Teléfono');
		$telefono->setAttributes(array(
		));
		$this->add($telefono);

		// anexo
		$anexo = new ZendElement\Text('anexo');
		$anexo->setLabel('Anexo');
		$anexo->setAttributes(array(
		));
		$this->add($anexo);

		// email
		$email = new ZendElement\Text('email');
		$email->setLabel('Email');
		$email->setAttributes(array(
			'data-bv-emailaddress' => "true"
		));
		$this->add($email);

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