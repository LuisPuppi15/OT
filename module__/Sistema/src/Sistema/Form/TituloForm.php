<?php
namespace Sistema\Form;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element as ZendElement;
use Zend\Form\Form;

class TituloForm extends Form {
	protected $em;
	protected $tipo;

	function __construct($em, $tipo = null) {
		parent::__construct();

		$this->em = $em;
		$this->tipo = $tipo;

		$hydrator = new DoctrineHydrator($this->em, 'Sistema\Entity\Titulo');
		$this->setHydrator($hydrator);

		$this->init();
	}

	public function init() {
		// titulo
		$titulo = new ZendElement\Text('titulo');
		$titulo->setLabel('Titulo');
		$titulo->setAttributes(array(
			'required' => "true",
		));
		$this->add($titulo);

		// descripcion
		$descripcion = new ZendElement\Textarea('descripcion');
		$descripcion->setLabel('Sub-Título');
		$descripcion->setAttributes(array(
		));
		$this->add($descripcion);

		// submit
		$submit = new ZendElement\Button('enviar');
		$submit->setLabel('Guardar');
		$submit->setAttributes(array(
			'type' => 'submit',
			'class' => 'btn btn-modal enviar',
		));
		$submit->setOptions(array(
			'label' => 'Guardar',
		));
		$this->add($submit);
	}

	public function getInputFilterSpecificiation() {
		return array();
	}
}
?>