<?php
namespace Sistema\Form;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element as ZendElement;
use Zend\Form\Form;
use Zend\InputFilter;

class BoletinForm extends Form {
	protected $em;
	protected $publicPath;
	protected $objectData;

	function __construct($em, $publicPath, $objectData = null) {
		parent::__construct();

		$this->em = $em;
		$this->publicPath = $publicPath;
		$this->objectData = $objectData;

		$hydrator = new DoctrineHydrator($this->em, 'Sistema\Entity\Boletin');
		$this->setHydrator($hydrator);

		$this->init();
		$this->initFilters();
	}

	public function init() {
		// cboltitulo
		$cboltitulo = new ZendElement\Text('cboltitulo');
		$cboltitulo->setLabel('Título');
		$cboltitulo->setAttributes(array(
			'required' => true
		));
		$this->add($cboltitulo);

		// dbolanio
		$dbolanio = new ZendElement\Text('dbolanio');
		$dbolanio->setLabel('Año');
		$dbolanio->setAttributes(array(
			'required' => true,
			'data-provide' => 'datepicker',
			'data-date-format' => 'yyyy',
			'data-date-min-view-mode' => 'years'
		));
		$this->add($dbolanio);

		// cbolrutaimg
		$cbolrutaimg = new ZendElement\File('cbolrutaimg');
		$value = ($this->objectData) ? $this->objectData->getCbolrutaimg() : '';

		$cbolrutaimg->setLabel('Imagen');
		$cbolrutaimg->setAttributes(array(
			'required' => false,
			'data-input-type' => 'img',
			'data-value' => $value,
			'data-show-upload' => 'false'
		));
		$this->add($cbolrutaimg);

		// imgString
		$imgString = new ZendElement\Text('imgString');
		$imgString->setValue($value);
		$imgString->setAttributes(array(
			'required' => true,
			'class' => 'input-mirror',
			'data-mirror' => 'cbolrutaimg'
		));
		$this->add($imgString);

		// cbolrutapdf
		$cbolrutapdf = new ZendElement\File('cbolrutapdf');
		$value = ($this->objectData) ? $this->objectData->getCbolrutapdf() : '';

		$cbolrutapdf->setLabel('Archivo (Pdf)');
		$cbolrutapdf->setAttributes(array(
			'required' => false,
			'data-input-type' => 'pdf',
			'data-value' => $value,
			'data-show-upload' => 'false'
		));
		$this->add($cbolrutapdf);

		// pdfString
		$pdfString = new ZendElement\Text('pdfString');
		$pdfString->setValue($value);
		$pdfString->setAttributes(array(
			'required' => true,
			'class' => 'input-mirror',
			'data-mirror' => 'cbolrutapdf'
		));
		$this->add($pdfString);

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

		// cbolrutaimg
		$cbolrutaimg = new InputFilter\FileInput('cbolrutaimg');
		$cbolrutaimg->setRequired(false);
		$cbolrutaimg->getFilterChain()->attachByName(
			'filerenameupload',
			array(
				'target' => $this->publicPath . '/upload/img/img.jpg',
				'randomize' => true
			)
		);
		$inputFilter->add($cbolrutaimg);

		// cbolrutapdf
		$cbolrutapdf = new InputFilter\FileInput('cbolrutapdf');
		$cbolrutapdf->setRequired(false);
		$cbolrutapdf->getFilterChain()->attachByName(
			'filerenameupload',
			array(
				'target' => $this->publicPath . '/upload/pdf/archivo.pdf',
				'randomize' => true
			)
		);
		$inputFilter->add($cbolrutapdf);

		$this->setInputFilter($inputFilter);
	}

	public function getInputFilterSpecificiation() {
		return array();
	}
}
?>