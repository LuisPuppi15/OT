<?php
namespace Sistema\Form;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element as ZendElement;
use Zend\Form\Form;
use Zend\InputFilter;

class NoticiaForm extends Form {
	protected $em;
	protected $publicPath;
	protected $objectData;

	function __construct($em, $publicPath, $objectData = null) {
		parent::__construct();

		$this->em = $em;
		$this->publicPath = $publicPath;
		$this->objectData = $objectData;

		$hydrator = new DoctrineHydrator($this->em, 'Sistema\Entity\Noticia');
		$this->setHydrator($hydrator);

		$this->init();
		$this->initFilters();
	}

	public function init() {
		// cnottitulo
		$cnottitulo = new ZendElement\Text('cnottitulo');
		$cnottitulo->setLabel('Título principal');
		$cnottitulo->setAttributes(array(
			'required' => "true"
		));
		$this->add($cnottitulo);

		// cnotpietitulo
		$cnotpietitulo = new ZendElement\Textarea('cnotpietitulo');
		$cnotpietitulo->setLabel('Título de pie');
		$cnotpietitulo->setAttributes(array(
			'required' => false
		));
		$this->add($cnotpietitulo);

		// cnotrutaimgfull
		$cnotrutaimgfull = new ZendElement\File('cnotrutaimgfull');
		$value = ($this->objectData) ? $this->objectData->getCnotrutaimgfull() : '';

		$cnotrutaimgfull->setLabel('Imagen de referencia');
		$cnotrutaimgfull->setAttributes(array(
			'required' => false,
			'data-input-type' => 'img',
			'data-value' => $value,
			'data-show-upload' => 'false'
		));
		$this->add($cnotrutaimgfull);

		// imgfullString
		$imgfullString = new ZendElement\Text('imgfullString');
		$imgfullString->setValue($value);
		$imgfullString->setAttributes(array(
			'required' => true,
			'class' => 'input-mirror',
			'data-mirror' => 'cnotrutaimgfull'
		));
		$this->add($imgfullString);

		// dnotfecha
		$dnotfecha = new ZendElement\Text('dnotfecha');
		$dnotfecha->setLabel('Fecha de publicación');
		$dnotfecha->setAttributes(array(
			'required' => "true",
			'data-provide' => 'datepicker',
			'data-date-format' => 'dd-mm-yyyy'
		));
		$this->add($dnotfecha);

		// cnotcontenido
		$cnotcontenido = new ZendElement\Textarea('cnotcontenido');
		$cnotcontenido->setLabel('Contenido');
		$cnotcontenido->setAttributes(array(
			'required' => "true",
			'class' => "ckeditor"
		));
		$this->add($cnotcontenido);

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
		$fileInput = new InputFilter\FileInput('cnotrutaimgfull');
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

	public function getInputFilterSpecificiation() {
		return array();
	}
}
?>