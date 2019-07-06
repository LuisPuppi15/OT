<?php
namespace Sistema\Form;

use DoctrineModule\Form\Element as DoctrineElement;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element as ZendElement;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class PerusuarioRolForm extends Form implements InputFilterProviderInterface {
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
		// rol
		$rol = new DoctrineElement\ObjectSelect('rol');
		$rol->setLabel('Rol');
		$rol->setAttributes(array(
			'required' => false
		));
		$rol->setOptions(array(
			'object_manager' => $this->em,
			'target_class' => 'Sistema\Entity\Rol',
			'display_empty_item' => true,
			'empty_item_label' => '(Seleccione un rol)',
			'required' => false
		));
		$this->add($rol);

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
		return array(
			'rol' => array(
				'required' => false
			)
		);
	}
}
?>