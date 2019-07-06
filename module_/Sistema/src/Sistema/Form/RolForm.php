<?php
namespace Sistema\Form;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element as ZendElement;
use Zend\Form\Form;

class RolForm extends Form {
	protected $em;
	protected $tipo;

	function __construct($em, $tipo = null) {
		parent::__construct();

		$this->em = $em;
		$this->tipo = $tipo;

		$hydrator = new DoctrineHydrator($this->em, 'Sistema\Entity\Rol');
		$this->setHydrator($hydrator);

		$this->init();
	}

	public function init() {
		// crolnombre
		$crolnombre = new ZendElement\Text('crolnombre');
		$crolnombre->setLabel('Nombre');
		$crolnombre->setAttributes(array(
			'required' => "true"
		));
		$this->add($crolnombre);

		// croldescripcion
		$croldescripcion = new ZendElement\Textarea('croldescripcion');
		$croldescripcion->setLabel('Descripcion');
		$croldescripcion->setAttributes(array(
			'required' => "true"
		));
		$this->add($croldescripcion);
	}

	public function getInputFilterSpecification() {
		return array();
	}
}
?>