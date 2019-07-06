<?php
namespace Sistema\Form;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element as ZendElement;
use Zend\Form\Form;

class TipocontenidoForm extends Form {
	protected $em;

	function __construct($em) {
		parent::__construct();

		$this->em = $em;

		$hydrator = new DoctrineHydrator($this->em, 'Sistema\Entity\Tipocontenido');
		$this->setHydrator($hydrator);

		$this->init();
	}

	public function init() {
		// ctipconnombre
		$ctipconnombre = new ZendElement\Text('ctipconnombre');
		$ctipconnombre->setLabel('Nombre');
		$ctipconnombre->setAttributes(array(
			'required' => true
		));
		$this->add($ctipconnombre);

		// ctipcondescripcion
		$ctipcondescripcion = new ZendElement\Textarea('ctipcondescripcion');
		$ctipcondescripcion->setLabel('Descripción');
		$this->add($ctipcondescripcion);

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