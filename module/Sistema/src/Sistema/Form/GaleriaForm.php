<?php
namespace Sistema\Form;

use DoctrineModule\Form\Element as DoctrineElement;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element as ZendElement;
use Zend\Form\Form;
use Zend\InputFilter;
use Zend\InputFilter\InputFilterProviderInterface;

class GaleriaForm extends Form implements InputFilterProviderInterface {
	protected $em;
	protected $publicPath;
	protected $objectData;

	function __construct($em, $publicPath, $objectData = null) {
		parent::__construct();

		$this->em = $em;
		$this->publicPath = $publicPath;
		$this->objectData = $objectData;

		$hydrator = new DoctrineHydrator($this->em, 'Sistema\Entity\Galeria');
		$this->setHydrator($hydrator);

		$this->init();
		$this->initFilters();
	}

	public function init() {
		// cgaltitulo
		$cgaltitulo = new ZendElement\Text('cgaltitulo');
		$cgaltitulo->setLabel('Título');
		$cgaltitulo->setAttributes(array(
			'required' => true,
			'data-bv-notempty' => ''
		));
		$this->add($cgaltitulo);

		// cgalsubtitulo
		$cgalsubtitulo = new ZendElement\Text('cgalsubtitulo');
		$cgalsubtitulo->setLabel('Subtítulo');
		$this->add($cgalsubtitulo);

		// cgalimagen
		$cgalimagen = new ZendElement\File('cgalimagen');
		$value = ($this->objectData) ? $this->objectData->getCgalimagen() : '';

		$cgalimagen->setLabel('Imagen');
		$cgalimagen->setAttributes(array(
			'required' => false,
			'data-input-type' => 'img',
			'data-value' => $value,
			'data-show-upload' => 'false'
		));
		$this->add($cgalimagen);

		// imgString
		$imgString = new ZendElement\Text('imgString');
		$imgString->setValue($value);
		$imgString->setAttributes(array(
			'required' => true,
			'class' => 'input-mirror',
			'data-mirror' => 'cgalimagen'
		));
		$this->add($imgString);

		// dgalfecha
		$dgalfecha = new ZendElement\Text('dgalfecha');
		$dgalfecha->setLabel('Fecha');
		$dgalfecha->setAttributes(array(
			'required' => false,
			'data-provide' => 'datepicker',
			'data-date-format' => 'dd-mm-yyyy'
		));
		$this->add($dgalfecha);

		// padre
		$padre = new DoctrineElement\ObjectSelect('padre');
		$ngalcodigo = ($this->objectData) ? $this->objectData->getNgalcodigo() : null;

		$padre->setLabel('Galería padre');
		$padre->setAttributes(array(
			'data-live-search' => true
		));
		$padre->setOptions(array(
			'object_manager' => $this->em,
			'target_class' => 'Sistema\Entity\Galeria',
			'display_empty_item' => true,
			'empty_item_label' => '(Seleccione una galería)',
			'find_method' => array(
				'name' => 'buscarHabilitados',
				'params' => array(
					'ngalcodigo' => $ngalcodigo
				)
			)
		));
		$this->add($padre);

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
		$fileInput = new InputFilter\FileInput('cgalimagen');
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
		return array(
			'padre' => array(
				'required' => false
			)
		);
	}
}
?>