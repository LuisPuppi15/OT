<?php
namespace Sistema\Form;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element as ZendElement;
use Zend\Form\Form;
use Zend\InputFilter;

class PdfForm extends Form {
	protected $em;
	protected $publicPath;
	protected $objectData;

	function __construct($em, $publicPath, $objectData = null) {
		parent::__construct();

		$this->em = $em;
		$this->publicPath = $publicPath;
		$this->objectData = $objectData;

		$hydrator = new DoctrineHydrator($this->em, 'Sistema\Entity\Pdf');
		$this->setHydrator($hydrator);

		$this->init();
		$this->initFilters();
	}

	public function init() {
		// cpdf
		$cpdf = new ZendElement\File('cpdf');
		$value = ($this->objectData) ? $this->objectData->getCpdf() : '';

		$cpdf->setLabel('Archivo (Pdf)');
		$cpdf->setAttributes(array(
			'required' => false,
			'data-input-type' => 'pdf',
			'data-value' => $value,
			'data-show-upload' => 'false'
		));
		$this->add($cpdf);

		// pdfString
		$pdfString = new ZendElement\Text('pdfString');
		$pdfString->setValue($value);
		$pdfString->setAttributes(array(
			'required' => true,
			'class' => 'input-mirror',
			'data-mirror' => 'cpdf'
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

		// cpdf
		$cpdf = new InputFilter\FileInput('cpdf');
		$cpdf->setRequired(false);
		$cpdf->getFilterChain()->attachByName(
			'filerenameupload',
			array(
				'target' => $this->publicPath . '/upload/pdf/archivo.pdf',
				'randomize' => true
			)
		);
		$inputFilter->add($cpdf);

		$this->setInputFilter($inputFilter);
	}

	public function getInputFilterSpecification() {
		return array();
	}
}
?>