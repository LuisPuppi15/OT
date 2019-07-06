<?php

namespace Sistema\Controller;

use Sistema\Entity\Persona;
use Sistema\Form\PersonaForm;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class PersonaController extends AbstractActionController {
	private $usuarioLogueado;
	private $nctrcodigo = 7;
	private $rutaRaiz = 'sistema/persona';
	private $opcionesItems = array(
		array('value' => 5, 'label' => 'Items: 5', 'selected' => false),
		array('value' => 10, 'label' => 'Items: 10', 'selected' => true),
		array('value' => 20, 'label' => 'Items: 20', 'selected' => false)
	);
	private $items;
	private $em;
	private $emNamespace = 'Sistema\Entity\Persona';
	private $repo;

	public function __construct() {
		foreach ($this->opcionesItems as $item) {
			if ($item['selected']) {
				$this->items = $item['value'];
			}
		}
	}

	public function onDispatch(\Zend\Mvc\MvcEvent $e) {
		$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		$this->repo = $this->em->getRepository($this->emNamespace);

		$container = new Container('perusuario');
		$this->usuarioLogueado = $this->em->getRepository('Sistema\Entity\Perusuario')->find($container->id);

		return parent::onDispatch($e);
	}

	public function inicioAction() {
		$pagina = 1;
		$items = $this->items;
		$inicio = ($pagina === 0) ? 0 : ($pagina - 1) * $items;

		$paginacion = array(
			'inicio' => $inicio,
			'pagina' => $pagina,
			'items' => $items
		);

		$campos = array();
		$personas = $this->repo->buscar($campos, $paginacion);
		$paginador = $this->repo->getPaginador($campos, $paginacion);

		$rutaRaiz = $this->rutaRaiz;
		$rutaFiltrar = $this->url()->fromRoute($rutaRaiz, array(
			'action' => 'filtrar',
			'parametros' => ':parametros'
		));

		$repoControl = $this->em->getRepository('Sistema\Entity\Control');
		$nperusucodigo = $this->usuarioLogueado->getNperusucodigo();
		$nctrtipo = 5002;
		$botones = $repoControl->buscarHijosPorUsuario($nperusucodigo, $this->nctrcodigo, $nctrtipo);

		$opcionesItems = $this->opcionesItems;

		$view = new ViewModel();
		$view->setVariable('personas', $personas)
		     ->setVariable('botones', $botones)
		     ->setVariable('opcionesItems', $opcionesItems)
		     ->setVariable('paginador', $paginador)
		     ->setVariable('rutaRaiz', $rutaRaiz)
		     ->setVariable('rutaFiltrar', $rutaFiltrar)
		     ->setTerminal(true);

		return $view;
	}

	public function filtrarAction() {
		$strParametros = $this->params()->fromRoute('parametros');
		parse_str($strParametros, $parametros);

		$pagina = (isset($parametros['pagina'])) ? $parametros['pagina'] : 1;
		$pagina = ($pagina != '') ? $pagina : 1;

		$items = (isset($parametros['items'])) ? $parametros['items'] : $this->items;
		$items = ($items != '') ? $parametros['items'] : $this->items;

		$inicio = ($pagina - 1) * $items;

		$paginacion = array(
			'inicio' => $inicio,
			'pagina' => $pagina,
			'items' => $items
		);

		$campos = (isset($parametros['campos'])) ? $parametros['campos'] : array();

		$filtro = isset($parametros['filtro']) ? $parametros['filtro'] : '';
		switch ($filtro) {
			case 'noUsuarios':
				$personas = $this->repo->buscarNoUsuarios($campos, $paginacion);
				$paginador = $this->repo->getPaginadorNoUsuarios($campos, $paginacion);

				$template = 'sistema/persona/tabla-compacto.phtml';
				break;

			default:
				$personas = $this->repo->buscar($campos, $paginacion);
				$paginador = $this->repo->getPaginador($campos, $paginacion);

				$template = 'sistema/persona/tabla.phtml';
				break;
		}

		$viewLista = new ViewModel();
		$viewLista->setTemplate($template)
		          ->setVariable('personas', $personas)
		          ->setTerminal(true);

		$viewPaginador = new ViewModel();
		$viewPaginador->setTemplate('sistema/paginador.phtml')
		              ->setVariable('paginador', $paginador)
		              ->setVariable('ruta', 'sistema/persona')
		              ->setTerminal(true);

		$htmlLista = $this->getServiceLocator()
		                  ->get('viewrenderer')
		                  ->render($viewLista);

		$htmlPaginador = $this->getServiceLocator()
		                      ->get('viewrenderer')
		                      ->render($viewPaginador);

		$jsonModel = new JsonModel();
		$jsonModel->setVariable('htmlLista', $htmlLista)
		          ->setVariable('htmlPaginador', $htmlPaginador);

		return $jsonModel;
	}

	public function agregarAction() {
		$persona = new Persona();
		$persona->setNpertipo(1);
		$persona->setNperestado(1);

		$form = new PersonaForm($this->em);
		$form->bind($persona);

		$submit = new Element\Button('enviar');
		$submit->setValue('Guardar');
		$form->add($submit);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->em->persist($persona);
				$this->em->flush();

				return new JsonModel(array(
					'success' => true,
					'tipo' => 'modal',
					'datos' => array(
						'keyId' => 'npercodigo',
						'id' => $persona->getNpercodigo(),
						'cadena' => $persona->getCpernombre() . ' ' . $persona->getCperapellidos()
					)
				));
			}
		}

		$view = new ViewModel();
		$view->setVariable('form', $form)
		     ->setTerminal(true);

		return $view;
	}

	public function editarAction() {
		$id = (int) $this->params()->fromRoute('parametros', 0);
		$persona = $this->repo->find($id);

		$form = new PersonaForm($this->em);
		$form->bind($persona);

		if ($persona->getDpernacimiento()) {
			$form->get('dpernacimiento')->setValue($persona->getDpernacimiento()->format('d-m-Y'));
		}

		$submit = new Element\Button('enviar');
		$submit->setValue('Guardar');
		$form->add($submit);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->em->persist($persona);
				$this->em->flush();

				return new JsonModel(array(
					'success' => true,
					'tipo' => 'modal',
					'datos' => array(
						'keyId' => 'npercodigo',
						'id' => $id
					)
				));
			}
		}

		$rutaRaiz = $this->rutaRaiz;

		$view = new ViewModel();
		$view->setVariable('id', $id)
		     ->setVariable('form', $form)
		     ->setVariable('ruta', $rutaRaiz)
		     ->setTerminal(true);

		return $view;
	}

	public function eliminarAction() {
		$id = $this->params()->fromRoute('parametros');
		$persona = $this->repo->find($id);

		$form = new Form();
		$submit = new Element\Button('enviar');
		$form->add($submit);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$persona->setNperestado(0);
			$this->em->persist($persona);
			$this->em->flush();

			return new JsonModel(array(
				'success' => true,
				'tipo' => 'modal',
				'datos' => array(
					'keyId' => 'npercodigo',
					'id' => $id
				)
			));
		}

		$view = new ViewModel();
		$view->setVariable('persona', $persona)
		     ->setVariable('form', $form)
		     ->setTerminal(true);

		return $view;
	}
}
