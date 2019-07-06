<?php
namespace Sistema\Form;

use DoctrineModule\Form\Element as DoctrineElement;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Element as ZendElement;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class MenuForm extends Form implements InputFilterProviderInterface {
	protected $em;
	protected $objectData;
	protected $recargarPadreRuta;

	function __construct($em, $recargarPadreRuta, $objectData = null) {
		parent::__construct();

		$this->em = $em;
		$this->recargarPadreRuta = $recargarPadreRuta;
		$this->objectData = $objectData;

		$hydrator = new DoctrineHydrator($this->em, 'Sistema\Entity\Menu');
		$this->setHydrator($hydrator);

		$this->init();
	}

	public function init() {
		// cmennombre
		$cmennombre = new ZendElement\Text('cmennombre');
		$cmennombre->setLabel('Nombre');
		$cmennombre->setAttributes(array(
			'required' => true
		));
		$this->add($cmennombre);

		// nivelmenu
		$nivelmenu = new DoctrineElement\ObjectSelect('nivelmenu');
		$nivelmenu->setLabel('Nivel del menú');
		$nivelmenu->setAttributes(array(
			'data-accion' => '{"nombre":"recargarPadre", "control":"padre", "ruta":"' . $this->recargarPadreRuta . '"}'
		));
		$nivelmenu->setOptions(array(
			'object_manager' => $this->em,
			'target_class' => 'Sistema\Entity\Nivelmenu',
			'find_method' => array(
				'name' => 'buscarHabilitados'
			)
		));
		$this->add($nivelmenu);

		$condicion = true;

		if ($this->objectData) {
			$tipocontenido = $this->objectData->getTipocontenido();

			if ($tipocontenido) {
				$repoMenu = $this->em->getRepository('Sistema\Entity\Menu');
				$existeContenido = $repoMenu->buscarSiExisteContenido($this->objectData->getNmencodigo(), $tipocontenido->getNtipconcodigo());
				if ($existeContenido) {
					$condicion = false;
				}
			}
		}

		if ($condicion) {
			// tipocontenido
			$tipocontenido = new DoctrineElement\ObjectSelect('tipocontenido');
			$tipocontenido->setLabel('Tipo de contenido');
			$tipocontenido->setOptions(array(
				'object_manager' => $this->em,
				'target_class' => 'Sistema\Entity\Tipocontenido',
				'display_empty_item' => true,
				'empty_item_label' => '(Ninguno)',
				'find_method' => array(
					'name' => 'buscarHabilitados'
				)
			));
			$this->add($tipocontenido);
		}

		// padre
		$padre = new DoctrineElement\ObjectSelect('padre');
		$padre->setLabel('Menu dependiente');
		$padre->setAttributes(array(
			'data-live-search' => true
		));
		$padre->setOptions(array(
			'object_manager' => $this->em,
			'target_class' => 'Sistema\Entity\Menu',
			'display_empty_item' => true,
			'empty_item_label' => '(Ninguno)',
			'disable_inarray_validator' => true,
			'find_method' => array(
				'name' => 'buscarHabilitadosPorNivel',
				'params' => array(
					'nnivmencodigo' => 1
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

	public function getInputFilterSpecification() {
		return array(
			'tipocontenido' => array(
				'required' => false
			),
			'padre' => array(
				'required' => false
			)
		);
	}
}
?>