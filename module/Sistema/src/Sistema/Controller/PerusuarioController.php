<?php
namespace Sistema\Controller;

use Sistema\Entity\Perusuacceso;
use Sistema\Entity\Perusuario;
use Sistema\Form\PerusuarioAccesosForm;
use Sistema\Form\PerusuarioForm;
use Sistema\Form\PerusuarioRolForm;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class PerusuarioController extends AbstractActionController {
	private $usuarioLogueado;
	private $nctrcodigo = 6;
	private $rutaRaiz = 'sistema/perusuario';
	private $opcionesItems = array(
		array('value' => 5, 'label' => 'Items: 5', 'selected' => false),
		array('value' => 10, 'label' => 'Items: 10', 'selected' => true),
		array('value' => 20, 'label' => 'Items: 20', 'selected' => false)
	);
	private $items;
	private $em;
	private $emNamespace = 'Sistema\Entity\Perusuario';
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
		$perusuarios = $this->repo->buscar($campos, $paginacion);
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
		$view->setVariable('perusuarios', $perusuarios)
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
		$perusuarios = $this->repo->buscar($campos, $paginacion);
		$paginador = $this->repo->getPaginador($campos, $paginacion);

		$viewLista = new ViewModel();
		$viewLista->setTemplate('sistema/perusuario/tabla.phtml')
		          ->setVariable('perusuarios', $perusuarios)
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
		$perusuario = new Perusuario();
		$perusuario->setNperusuestado(1);

		$form = new PerusuarioForm($this->em);
		$form->bind($perusuario);

		$submit = new Element\Button('enviar');
		$submit->setValue('Guardar');
		$form->add($submit);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->em->persist($perusuario);
				$this->em->flush();

				return new JsonModel(array(
					'success' => true,
					'tipo' => 'modal',
					'datos' => array(
						'keyId' => 'npercodigo',
						'id' => $perusuario->getPersona()->getNpercodigo()
					)
				));
			}
		}

		// Lista de personas
		$pagina = 1;
		$items = 5;
		$inicio = ($pagina === 0) ? 0 : ($pagina - 1) * $items;

		$paginacion = array(
			'inicio' => $inicio,
			'pagina' => $pagina,
			'items' => $items
		);

		$rutaPersona = 'sistema/persona';
		$repoPersona = $this->em->getRepository('Sistema\Entity\Persona');
		$campos = array();
		$personas = $repoPersona->buscarNoUsuarios($campos, $paginacion);
		$paginador = $repoPersona->getPaginadorNoUsuarios($campos, $paginacion);

		$rutas = array(
			'main' => $this->rutaRaiz,
			'filtrar' => $this->url()->fromRoute($rutaPersona, array(
				'action' => 'filtrar',
				'parametros' => ':parametros'
			)),
			'agregar' => $this->url()->fromRoute($rutaPersona, array('action' => 'agregar'))
		);

		$dataPersonas = array(
			'filtro' => 'noUsuarios',
			'rutas' => $rutas,
			'items' => $items,
			'personas' => $personas,
			'paginador' => $paginador
		);

		$ruta = $this->rutaRaiz;

		$view = new ViewModel();
		$view->setVariable('form', $form)
		     ->setVariable('ruta', $ruta)
		     ->setVariable('dataPersonas', $dataPersonas)
		     ->setTerminal(true);

		return $view;
	}

	public function editarAction() {
		$id = (int) $this->params()->fromRoute('parametros', 0);
		$perusuario = $this->repo->find($id);

		$form = new PerusuarioForm($this->em);
		$form->bind($perusuario);

		$submit = new Element\Button('enviar');
		$submit->setValue('Guardar');
		$form->add($submit);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->em->persist($perusuario);
				$this->em->flush();

				return new JsonModel(array(
					'success' => true,
					'tipo' => 'modal',
					'datos' => array(
						'keyId' => 'npercodigo',
						'id' => $perusuario->getPersona()->getNpercodigo()
					)
				));
			}
		}

		$ruta = $this->rutaRaiz;

		$view = new ViewModel();
		$view->setVariable('id', $id)
		     ->setVariable('form', $form)
		     ->setVariable('ruta', $ruta)
		     ->setTerminal(true);

		return $view;
	}

	public function eliminarAction() {
		$id = $this->params()->fromRoute('parametros');
		$perusuario = $this->repo->find($id);

		$form = new Form();
		$submit = new Element\Button('enviar');
		$form->add($submit);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$perusuario->setNperusuestado(0);
			$this->em->persist($perusuario);
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
		$view->setVariable('perusuario', $perusuario)
		     ->setVariable('form', $form)
		     ->setTerminal(true);

		return $view;
	}

	public function estadoAction() {
		$id = $this->params()->fromRoute('parametros');
		$perusuario = $this->repo->find($id);

		$form = new Form();
		$submit = new Element\Button('enviar');
		$form->add($submit);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$nperusuestado = $perusuario->getNperusuestado();

			if ($nperusuestado == 1) {
				$perusuario->setNperusuestado(2);
			} elseif ($nperusuestado == 2) {
				$perusuario->setNperusuestado(1);
			}

			$this->em->persist($perusuario);
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
		$view->setVariable('perusuario', $perusuario)
		     ->setVariable('form', $form)
		     ->setTerminal(true);

		return $view;
	}

	public function rolAction() {
		$id = (int) $this->params()->fromRoute('parametros', 0);
		$perusuario = $this->repo->find($id);

		$form = new PerusuarioRolForm($this->em);
		$form->bind($perusuario);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$requestPost = $request->getPost();
			if ($requestPost['rol'] == '') {
				$requestPost['rol'] = null;
			}
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->em->persist($perusuario);
				$this->em->flush();

				return new JsonModel(array(
					'success' => true,
					'tipo' => 'modal',
					'datos' => array()
				));
			}
		}

		$ruta = $this->rutaRaiz;

		$view = new ViewModel();
		$view->setVariable('id', $id)
		     ->setVariable('form', $form)
		     ->setVariable('ruta', $ruta)
		     ->setTerminal(true);

		return $view;
	}

	public function accesosAction() {
		$id = (int) $this->params()->fromRoute('parametros', 0);
		$perusuario = $this->repo->find($id);

		$rol = $perusuario->getRol();
		$nrolcodigo = null;
		if ($rol) {
			$nrolcodigo = $rol->getNrolcodigo();
		}

		$repoControl = $this->em->getRepository('Sistema\Entity\Control');
		$repoRolcontrol = $this->em->getRepository('Sistema\Entity\Rolcontrol');
		$repoPerusuacceso = $this->em->getRepository('Sistema\Entity\Perusuacceso');

		$controls = $repoControl->enviarTodos();
		$rolcontrols = $repoRolcontrol->buscarPorRol($nrolcodigo);
		$perusuaccesos = $repoPerusuacceso->buscarPorUsuario($id);

		$form = new PerusuarioAccesosForm($this->em, $controls, $rolcontrols, $perusuaccesos);

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
						$perusuacceso = $repoPerusuacceso->buscarPorPk($id, $nctrcodigo);
						$rolcontrol = $repoRolcontrol->buscarPorPk($nrolcodigo, $nctrcodigo);
						$registrar = false;

						if ($rolcontrol) {
							$nrolctrestado = $rolcontrol->getNrolctrestado();
							if ($value != $nrolctrestado) {
								$registrar = true;
								if (!$perusuacceso) {
									$perusuacceso = new Perusuacceso();
								}
							} else {
								if ($perusuacceso) {
									$nperusuaccestado = $perusuacceso->getNperusuaccestado();
									if ($value != $nperusuaccestado) {
										$registrar = true;
									}
								}
							}
						} else {
							if (!$perusuacceso) {
								if ($value != 0) {
									$perusuacceso = new Perusuacceso();
									$registrar = true;
								}
							} else {
								$registrar = true;
							}
						}

						if ($registrar) {
							$perusuacceso->setNperusuaccestado($value);
							$perusuacceso->setPerusuario($perusuario);
							$perusuacceso->setControl($control);

							$this->em->persist($perusuacceso);
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
		     ->setVariable('perusuario', $perusuario)
		     ->setVariable('form', $form)
		     ->setVariable('controls', $controls)
		     ->setTerminal(true);

		return $view;
	}

	public function resetearAccesosAction() {
		$id = (int) $this->params()->fromRoute('parametros', 0);
		$perusuario = $this->repo->find($id);

		foreach ($perusuario->getPerusuaccesos() as $perusuacceso) {
			$this->em->remove($perusuacceso);
		}

		$this->em->flush();

		return new JsonModel(array(
			'success' => true,
			'tipo' => 'modal',
			'datos' => array(
			)
		));
	}
}