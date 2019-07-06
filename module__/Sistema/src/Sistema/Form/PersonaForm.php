<?php
namespace Sistema\Form;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element as ZendElement;
use Zend\Form\Form;

class PersonaForm extends Form {
	protected $em;
	protected $tipo;

	function __construct($em, $tipo = null) {
		parent::__construct();

		$this->em = $em;
		$this->tipo = $tipo;

		$hydrator = new DoctrineHydrator($this->em, 'Sistema\Entity\Persona');
		$this->setHydrator($hydrator);

		$this->init();
	}

	public function init() {
		// cpernombre
		$cpernombre = new ZendElement\Text('cpernombre');
		$cpernombre->setLabel('Nombre');
		$cpernombre->setAttributes(array(
			'required' => "true"
		));
		$this->add($cpernombre);

		// cperapellidos
		$cperapellidos = new ZendElement\Text('cperapellidos');
		$cperapellidos->setLabel('Apellidos');
		$cperapellidos->setAttributes(array(
			'required' => "true"
		));
		$this->add($cperapellidos);

		// nperdni
		$nperdni = new ZendElement\Text('nperdni');
		$nperdni->setLabel('Apellidos');
		$nperdni->setAttributes(array(
			'data-bv-stringlength' => "true",
			'data-bv-stringlength-min' => "8",
			'data-bv-stringlength-max' => "9"
		));
		$this->add($nperdni);

		// dpernacimiento
		$dpernacimiento = new ZendElement\Text('dpernacimiento');
		$dpernacimiento->setLabel('Apellidos');
		$dpernacimiento->setAttributes(array(
			'required' => false
		));
		$this->add($dpernacimiento);
	}

	public function getInputFilterSpecificiation() {
		return array(
			'dpernacimiento' => array(
				'required' => false
			)
		);
	}
}
?>