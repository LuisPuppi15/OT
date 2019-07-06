<?php
namespace Sistema\Form;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element as ZendElement;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class HtmlForm extends Form implements InputFilterProviderInterface {
	protected $em;
	protected $objectData;

	function __construct($em) {
		parent::__construct();

		$this->em = $em;

		$hydrator = new DoctrineHydrator($this->em, 'Sistema\Entity\Html');
		$this->setHydrator($hydrator);

		$this->init();
	}

	public function init() {
		// chtmlcontenido
		$chtmlcontenido = new ZendElement\Textarea('chtmlcontenido');
		$chtmlcontenido->setLabel('Contenido');
		$chtmlcontenido->setAttributes(array(
			'required' => true,
			'class' => "ckeditor"
		));
		$this->add($chtmlcontenido);

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