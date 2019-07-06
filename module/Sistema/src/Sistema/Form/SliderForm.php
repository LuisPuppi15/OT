<?php
namespace Sistema\Form;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element as ZendElement;
use Zend\Form\Form;
use Zend\InputFilter;

class SliderForm extends Form {
	protected $em;
	protected $publicPath;
	protected $objectData;

	function __construct($em, $publicPath, $objectData = null) {
		parent::__construct();

		$this->em = $em;
		$this->publicPath = $publicPath;
		$this->objectData = $objectData;

		$hydrator = new DoctrineHydrator($this->em, 'Sistema\Entity\Slider');
		$this->setHydrator($hydrator);

		$this->init();
		$this->initFilters();
	}

	public function init() {
		// cslititulo
		$cslititulo = new ZendElement\Text('cslititulo');
		$cslititulo->setLabel('Título');
		$cslititulo->setAttributes(array(
			'required' => "true"
		));
		$this->add($cslititulo);

		// cslidescripcion
		$cslidescripcion = new ZendElement\Textarea('cslidescripcion');
		$cslidescripcion->setLabel('Descripción');
		$cslidescripcion->setAttributes(array(
		));
		$this->add($cslidescripcion);

		// cslirutimgfull
		$cslirutimgfull = new ZendElement\File('cslirutimgfull');
		$value = ($this->objectData) ? $this->objectData->getCslirutimgfull() : '';

		$cslirutimgfull->setLabel('Imagen');
		$cslirutimgfull->setAttributes(array(
			'required' => false,
			'data-input-type' => 'img',
			'data-value' => $value,
			'data-show-upload' => 'false'
		));
		$this->add($cslirutimgfull);

		// imgfullString
		$imgfullString = new ZendElement\Text('imgfullString');
		$imgfullString->setValue($value);
		$imgfullString->setAttributes(array(
			'required' => true,
			'class' => 'input-mirror',
			'data-mirror' => 'cslirutimgfull'
		));
		$this->add($imgfullString);

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
		$fileInput = new InputFilter\FileInput('cslirutimgfull');
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