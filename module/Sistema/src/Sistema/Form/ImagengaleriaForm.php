<?php
namespace Sistema\Form;

use DoctrineModule\Form\Element as DoctrineElement;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element as ZendElement;
use Zend\Form\Form;
use Zend\InputFilter;
use Zend\InputFilter\InputFilterProviderInterface;

class ImagengaleriaForm extends Form implements InputFilterProviderInterface {
	protected $em;
	protected $publicPath;
	protected $objectData;

	function __construct($em, $publicPath, $objectData = null) {
		parent::__construct();

		$this->em = $em;
		$this->publicPath = $publicPath;
		$this->objectData = $objectData;

		$hydrator = new DoctrineHydrator($this->em, 'Sistema\Entity\Imagengaleria');
		$this->setHydrator($hydrator);

		$this->init();
		$this->initFilters();
	}

	public function init() {
		// cimagaltitulo
		$cimagaltitulo = new ZendElement\Text('cimagaltitulo');
		$cimagaltitulo->setLabel('Título');
		$this->add($cimagaltitulo);

		// cimagalimg
		$cimagalimg = new ZendElement\File('cimagalimg');
		$value = ($this->objectData) ? $this->objectData->getCimagalimg() : '';

		$cimagalimg->setLabel('Imagen');
		$cimagalimg->setAttributes(array(
			'required' => false,
			'data-input-type' => 'img',
			'data-value' => $value,
			'data-show-upload' => 'false'
		));
		$this->add($cimagalimg);

		// imgString
		$imgString = new ZendElement\Text('imgString');
		$imgString->setValue($value);
		$imgString->setAttributes(array(
			'required' => true,
			'class' => 'input-mirror',
			'data-mirror' => 'cimagalimg'
		));
		$this->add($imgString);

		// galeria
		$galeria = new DoctrineElement\ObjectSelect('galeria');
		$galeria->setLabel('Galería');
		$galeria->setAttributes(array(
			'data-live-search' => true
		));
		$galeria->setOptions(array(
			'object_manager' => $this->em,
			'target_class' => 'Sistema\Entity\Galeria',
			'display_empty_item' => false,
			'empty_item_label' => '(Seleccione una galería)',
			'find_method' => array(
				'name' => 'buscarHabilitados'
			)
		));
		$this->add($galeria);

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

	public function initFilters() {
		$inputFilter = new InputFilter\InputFilter();

		// File Input
		$fileInput = new InputFilter\FileInput('cimagalimg');
		$fileInput->setRequired(false);
		$fileInput->getFilterChain()->attachByName(
			'filerenameupload',
			array(
				'target' => $this->publicPath . '/upload/img/img.jpg',
				'randomize' => true
			)
		);
		$inputFilter->add($fileInput);

		$this->setInputFilter($inputFilter);
	}

	public function getInputFilterSpecification() {
		return array();
	}
}
?>