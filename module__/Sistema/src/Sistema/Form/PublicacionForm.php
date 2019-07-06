<?php
namespace Sistema\Form;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element as ZendElement;
use Zend\Form\Form;
use Zend\InputFilter;

class PublicacionForm extends Form {
	protected $em;
	protected $publicPath;
	protected $objectData;

	function __construct($em, $publicPath, $objectData = null) {
		parent::__construct();

		$this->em = $em;
		$this->publicPath = $publicPath;
		$this->objectData = $objectData;

		$hydrator = new DoctrineHydrator($this->em, 'Sistema\Entity\Publicacion');
		$this->setHydrator($hydrator);

		$this->init();
		$this->initFilters();
	}

	public function init() {
		// cpubtitulo
		$cpubtitulo = new ZendElement\Text('cpubtitulo');
		$cpubtitulo->setLabel('Título');
		$cpubtitulo->setAttributes(array(
			'required' => "true",
			'data-bv-notempty' => ''
		));
		$this->add($cpubtitulo);

		// cpubanio
		$cpubanio = new ZendElement\Text('cpubanio');
		$cpubanio->setLabel('Año');
		$cpubanio->setAttributes(array(
			'required' => "true",
			'data-bv-notempty' => '',
			'data-provide' => 'datepicker',
			'data-date-format' => 'yyyy',
			'data-date-min-view-mode' => 'years'
		));
		$this->add($cpubanio);

		// cpubimagen
		$cpubimagen = new ZendElement\File('cpubimagen');
		$value = ($this->objectData) ? $this->objectData->getCpubimagen() : '';

		$cpubimagen->setLabel('Imagen');
		$cpubimagen->setAttributes(array(
			'required' => false,
			'data-input-type' => 'img',
			'data-value' => $value,
			'data-show-upload' => 'false'
		));
		$this->add($cpubimagen);

		// imgString
		$imgString = new ZendElement\Text('imgString');
		$imgString->setValue($value);
		$imgString->setAttributes(array(
			'required' => true,
			'class' => 'input-mirror',
			'data-mirror' => 'cpubimagen'
		));
		$this->add($imgString);

		// cpubpdf
		$cpubpdf = new ZendElement\File('cpubpdf');
		$value = ($this->objectData) ? $this->objectData->getCpubpdf() : '';

		$cpubpdf->setLabel('Archivo (Pdf)');
		$cpubpdf->setAttributes(array(
			'required' => false,
			'data-input-type' => 'pdf',
			'data-value' => $value,
			'data-show-upload' => 'false'
		));
		$this->add($cpubpdf);

		// pdfString
		$pdfString = new ZendElement\Text('pdfString');
		$pdfString->setValue($value);
		$pdfString->setAttributes(array(
			'required' => true,
			'class' => 'input-mirror',
			'data-mirror' => 'cpubpdf'
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

		// cpubimagen
		$cpubimagen = new InputFilter\FileInput('cpubimagen');
		$cpubimagen->setRequired(false);
		$cpubimagen->getFilterChain()->attachByName(
			'filerenameupload',
			array(
				'target' => $this->publicPath . '/upload/img/img.jpg',
				'randomize' => true
			)
		);
		$inputFilter->add($cpubimagen);

		// cpubpdf
		$cpubpdf = new InputFilter\FileInput('cpubpdf');
		$cpubpdf->setRequired(false);
		$cpubpdf->getFilterChain()->attachByName(
			'filerenameupload',
			array(
				'target' => $this->publicPath . '/upload/pdf/archivo.pdf',
				'randomize' => true
			)
		);
		$inputFilter->add($cpubpdf);

		$this->setInputFilter($inputFilter);
	}

	public function getInputFilterSpecificiation() {
		return array();
	}
}
?>