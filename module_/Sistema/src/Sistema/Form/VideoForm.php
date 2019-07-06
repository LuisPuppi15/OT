<?php
namespace Sistema\Form;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element as ZendElement;
use Zend\Form\Form;

class VideoForm extends Form {
	protected $em;

	function __construct($em) {
		parent::__construct();

		$this->em = $em;

		$hydrator = new DoctrineHydrator($this->em, 'Sistema\Entity\Video');
		$this->setHydrator($hydrator);

		$this->init();
	}

	public function init() {
		// cvidcodigo
		$cvidcodigo = new ZendElement\Text('cvidcodigo');
		$cvidcodigo->setLabel('Código');
		$cvidcodigo->setAttributes(array(
			'required' => true
		));
		$this->add($cvidcodigo);

		// cvidtitulo
		$cvidtitulo = new ZendElement\Text('cvidtitulo');
		$cvidtitulo->setLabel('Título');
		$cvidtitulo->setAttributes(array(
			'required' => true
		));
		$this->add($cvidtitulo);

		// cviddescripcion
		$cviddescripcion = new ZendElement\Textarea('cviddescripcion');
		$cviddescripcion->setLabel('Descripción');
		$cviddescripcion->setAttributes(array(
		));
		$this->add($cviddescripcion);

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