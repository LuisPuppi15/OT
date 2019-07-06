<?php
namespace Sistema\Form;

use Zend\Form\Element as ZendElement;
use Zend\Form\Form;

class PerusuarioAccesosForm extends Form {
	protected $em;
	protected $controls;
	protected $rolcontrols;
	protected $perusuaccesos;
	protected $tipo;

	function __construct($em, $controls, $rolcontrols, $perusuaccesos, $tipo = null) {
		parent::__construct();

		$this->em = $em;
		$this->controls = $controls;
		$this->rolcontrols = $rolcontrols;
		$this->perusuaccesos = $perusuaccesos;
		$this->tipo = $tipo;

		$this->init();
	}

	public function init() {
		foreach ($this->controls as $control) {
			$jstreeAttr = array();
			$jstreeAttr['selected'] = false;
			$jstreeAttr['id'] = $control->getNctrcodigo();
			$value = 0;

			foreach ($this->rolcontrols as $rolcontrol) {
				if ($rolcontrol->getControl()->getNctrcodigo() == $control->getNctrcodigo()) {
					if ($rolcontrol->getNrolctrestado() == 1) {
						$jstreeAttr['opened'] = true;
						$value = 1;

						if ($control->getHijos()->count() == 0) {
							$jstreeAttr['selected'] = true;
						}
					}
				}
			}
			foreach ($this->perusuaccesos as $perusuacceso) {
				if ($perusuacceso->getControl()->getNctrcodigo() == $control->getNctrcodigo()) {
					if ($perusuacceso->getNperusuaccestado() == 1) {
						$jstreeAttr['opened'] = true;
						$value = 1;

						if ($control->getHijos()->count() == 0) {
							$jstreeAttr['selected'] = true;
						}
					} elseif ($perusuacceso->getNperusuaccestado() == 0) {
						$jstreeAttr['selected'] = false;
						$value = 0;
					}
				}
			}

			$atributos['data-jstree'] = json_encode($jstreeAttr);

			$element = new ZendElement\Hidden();
			$element->setName($control->getNctrcodigo());
			$element->setValue($value);
			$element->setOptions(array());
			$element->setAttributes($atributos);
			$this->add($element);
		}

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