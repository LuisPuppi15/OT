<?php
namespace Sistema\Form;
use DoctrineModule\Form\Element as DoctrineElement;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element as ZendElement;
use Zend\Form\Form;

class PerusuarioForm extends Form {
	protected $em;
	protected $tipo;

	function __construct($em, $tipo = null) {
		parent::__construct();

		$this->em = $em;
		$this->tipo = $tipo;

		$hydrator = new DoctrineHydrator($this->em, 'Sistema\Entity\Perusuario');
		$this->setHydrator($hydrator);

		$this->init();
	}

	public function init() {
		// cperusuname
		$cperusuname = new ZendElement\Text('cperusuname');
		$cperusuname->setLabel('Usuario');
		$cperusuname->setAttributes(array(
			'required' => "true"
		));
		$this->add($cperusuname);

		// cperusuclave
		$cperusuclave = new ZendElement\Password('cperusuclave');
		$cperusuclave->setLabel('Contraseña');
		$cperusuclave->setAttributes(array(
			'required' => "true",
			'data-bv-stringlength' => "true",
			'data-bv-stringlength-min' => "6"
		));
		$this->add($cperusuclave);

		// persona
		$persona = new DoctrineElement\ObjectSelect('persona');
		$persona->setLabel('Persona');
		$persona->setAttributes(array(
			'data-live-search' => 'true'
		));
		$persona->setOptions(array(
			'object_manager' => $this->em,
			'target_class' => 'Sistema\Entity\Persona'
		));
		$this->add($persona);
	}

	public function getInputFilterSpecificiation() {
		return array();
	}
}
?>