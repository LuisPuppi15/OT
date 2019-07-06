<?php

namespace Sistema\Controller;

use Sistema\Entity\Rol;
use Sistema\Entity\Rolcontrol;
use Sistema\Form\RolAccesosForm;
use Sistema\Form\RolForm;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class RolController extends AbstractActionController {
	private $usuarioLogueado;
	private $nctrcodigo = 5;
	private $rutaRaiz = 'sistema/rol';
	private $opcionesItems = array(
		array('value' => 5, 'label' => 'Items: 5', 'selected' => false),
		array('value' => 10, 'label' => 'Items: 10', 'selected' => true),
		array('value' => 20, 'label' => 'Items: 20', 'selected' => false)
	);
	private $items;
	private $em;
	private $emNamespace = 'Sistema\Entity\Rol';
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
		$rols = $this->repo->buscar($campos, $paginacion);
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
		$view->setVariable('rols', $rols)
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
		$rols = $this->repo->buscar($campos, $paginacion);
		$paginador = $this->repo->getPaginador($campos, $paginacion);

		$viewLista = new ViewModel();
		$viewLista->setTemplate('sistema/rol/tabla.phtml')
		          ->setVariable('rols', $rols)
		          ->setTerminal(true);

		$rutaRaiz = $this->rutaRaiz;
		$viewPaginador = new ViewModel();
		$viewPaginador->setTemplate('sistema/paginador.phtml')
		              ->setVariable('paginador', $paginador)
		              ->setVariable('ruta', $rutaRaiz)
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
		$rol = new Rol();
		$rol->setNrolestado(1);

		$form = new RolForm($this->em);
		$form->bind($rol);

		$submit = new Element\Button('enviar');
		$submit->setValue('Guardar');
		$form->add($submit);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->em->persist($rol);
				$this->em->flush();

				return new JsonModel(array(
					'success' => true,
					'tipo' => 'modal',
					'datos' => array(
						'keyId' => 'nrolcodigo',
						'id' => $rol->getNrolcodigo()
					)
				));
			}
		}

		$view = new ViewModel();
		$view->setVariable('form', $form)
		     ->setTerminal('ruta', $this->rutaRaiz)
		                                ->setTerminal(true);

		return $view;
	}

	public function editarAction() {
		$id = (int) $this->params()->fromRoute('parametros', 0);
		$rol = $this->em->getRepository($this->emNamespace)->find($id);

		$form = new RolForm($this->em);
		$form->bind($rol);

		$submit = new Element\Button('enviar');
		$submit->setValue('Guardar');
		$form->add($submit);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->em->persist($rol);
				$this->em->flush();

				return new JsonModel(array(
					'success' => true,
					'tipo' => 'modal',
					'datos' => array(
						'keyId' => 'nrolcodigo',
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
		$rol = $this->repo->find($id);

		$form = new Form();
		$submit = new Element\Button('enviar');
		$form->add($submit);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$rol->setNrolestado(0);
			$this->em->persist($rol);
			$this->em->flush();

			return new JsonModel(array(
				'success' => true,
				'tipo' => 'modal',
				'datos' => array(
					'keyId' => 'nrolcodigo',
					'id' => $id
				)
			));
		}

		$view = new ViewModel();
		$view->setVariable('rol', $rol)
		     ->setVariable('form', $form)
		     ->setTerminal(true);

		return $view;
	}

	public function accesosAction() {
		$id = (int) $this->params()->fromRoute('parametros', 0);
		$rol = $this->repo->find($id);

		$repoControl = $this->em->getRepository('Sistema\Entity\Control');
		$repoRolcontrol = $this->em->getRepository('Sistema\Entity\Rolcontrol');

		$controls = $repoControl->enviarTodos();
		$rolcontrols = $repoRolcontrol->buscarPorRol($id);

		$form = new RolAccesosForm($this->em, $controls, $rolcontrols);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$valores = $form->getData();

				foreach ($valores as $key => $value) {
					if ($key != 'enviar') {
						$value = (int) $value;
						$nctrcodigo = $key;
						$control = $repoControl->find($nctrcodigo);
						$rolcontrol = $repoRolcontrol->buscarPorPk($id, $nctrcodigo);
						$registrar = false;

						if (!$rolcontrol) {
							if ($value != 0) {
								$rolcontrol = new Rolcontrol();
								$registrar = true;
							}
						} else {
							$registrar = true;
						}

						if ($registrar) {
							$rolcontrol->setNrolctrestado($value);
							$rolcontrol->setRol($rol);
							$rolcontrol->setControl($control);

							$this->em->persist($rolcontrol);
						}

						$this->em->flush();
					}
				}

				return new JsonModel(array(
					'success' => true,
					'tipo' => 'modal',
					'datos' => array(
					)
				));
			}
		}

		$controls = $repoControl->enviarPadres();

		$view = new ViewModel();
		$view->setVariable('id', $id)
		     ->setVariable('form', $form)
		     ->setVariable('controls', $controls)
		     ->setTerminal(true);

		return $view;
	}
}
