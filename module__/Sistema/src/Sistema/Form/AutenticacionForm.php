<?php
namespace Sistema\Form;

use Zend\Form\Element as ZendElement;
use Zend\Form\Form;

class AutenticacionForm extends Form {
	protected $em;
	protected $tipo;

	function __construct() {
		parent::__construct();

		$this->init();
	}

	public function init() {
		// cperusuname
		$cperusuname = new ZendElement\Text('cperusuname');
		$cperusuname->setAttributes(array(
			'required' => "true",
			'placeholder' => 'Usuario',
			'autocomplete' => 'off'
		));
		$this->add($cperusuname);

		// cperusuclave
		$cperusuclave = new ZendElement\Password('cperusuclave');
		$cperusuclave->setAttributes(array(
			'required' => "true",
			'placeholder' => 'Contraseña'
		));
		$this->add($cperusuclave);

		// submit
		$submit = new ZendElement\Button('enviar');
		$submit->setLabel('Guardar');
		$submit->setAttributes(array(
			'type' => 'submit',
			'class' => 'btn btn-modal enviar'
		));
		$submit->setOptions(array(
			'label' => 'Ingresar'
		));
		$this->add($submit);
	}

	public function getInputFilterSpecification() {
		return array();
	}
}
?>