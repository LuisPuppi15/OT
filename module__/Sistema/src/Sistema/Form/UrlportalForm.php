<?php
namespace Sistema\Form;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element as ZendElement;
use Zend\Form\Form;

class UrlportalForm extends Form {
	protected $em;

	function __construct($em) {
		parent::__construct();

		$this->em = $em;

		$hydrator = new DoctrineHydrator($this->em, 'Sistema\Entity\Urlportal');
		$this->setHydrator($hydrator);

		$this->init();
	}

	public function init() {
		// curlpornombre
		$curlpornombre = new ZendElement\Text('curlpornombre');
		$curlpornombre->setLabel('Nombre');
		$curlpornombre->setAttributes(array(
			'required' => true
		));
		$this->add($curlpornombre);

		// curlporurl
		$curlporurl = new ZendElement\Text('curlporurl');
		$curlporurl->setLabel('Url');
		$curlporurl->setAttributes(array(
			'required' => true
		));
		$this->add($curlporurl);

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