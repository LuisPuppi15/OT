<?php
namespace Sistema\Form;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element as ZendElement;
use Zend\Form\Form;

class PublicacionesForm extends Form {
	protected $em;

	function __construct($em) {
		parent::__construct();

		$this->em = $em;

		$hydrator = new DoctrineHydrator($this->em, 'Sistema\Entity\Publicaciones');
		$this->setHydrator($hydrator);

		$this->init();
	}

	public function init() {
		// cpubnombre
		$cpubnombre = new ZendElement\Text('cpubnombre');
		$cpubnombre->setLabel('Nombre');
		$cpubnombre->setAttributes(array(
			'required' => true
		));
		$this->add($cpubnombre);

		// cpuburl
		$cpuburl = new ZendElement\Text('cpuburl');
		$cpuburl->setLabel('Url');
		$cpuburl->setAttributes(array(
			'required' => true
		));
		$this->add($cpuburl);

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