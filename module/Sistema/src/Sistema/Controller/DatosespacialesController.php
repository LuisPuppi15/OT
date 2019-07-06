<?php
namespace Sistema\Controller;

use Sistema\Entity\Datosespaciales;
use Sistema\Form\DatosespacialesForm;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class DatosespacialesController extends AbstractActionController {
	private $usuarioLogueado;
	private $nctrcodigo = 105;
	private $rutaRaiz = 'sistema/datosespaciales';
	private $opcionesItems = array(
		array('value' => 5, 'label' => 'Items: 5', 'selected' => false),
		array('value' => 10, 'label' => 'Items: 10', 'selected' => true),
		array('value' => 20, 'label' => 'Items: 20', 'selected' => false)
	);
	private $items;
	private $em;
	private $emNamespace = 'Sistema\Entity\Datosespaciales';
	private $repo;
	private $publicPath;

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
		$datosespacialess = $this->repo->buscar($campos, $paginacion);
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
		$view->setVariable('datosespacialess', $datosespacialess)
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
		$items = ($items != '') ? $items : $this->items;

		$inicio = ($pagina - 1) * $items;

		$paginacion = array(
			'inicio' => $inicio,
			'pagina' => $pagina,
			'items' => $items
		);

		$campos = (isset($parametros['campos'])) ? $parametros['campos'] : array();
		$datosespacialess = $this->repo->buscar($campos, $paginacion);
		$paginador = $this->repo->getPaginador($campos, $paginacion);

		$viewLista = new ViewModel();
		$viewLista->setTemplate('sistema/datosespaciales/lista.phtml')
		          ->setVariable('datosespacialess', $datosespacialess)
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
		$datosespaciales = new Datosespaciales();

		$form = new DatosespacialesForm($this->em);
		$form->bind($datosespaciales);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$post = array_merge_recursive(
				$request->getPost()->toArray(),
				$request->getFiles()->toArray()
			);

			$form->setData($post);
			if ($form->isValid()) {
				$ultimoOrden = $this->repo->buscarUltimoOrden();
				$ndatespjerarquia = $ultimoOrden + 1;

				$datosespaciales->setNdatespjerarquia($ndatespjerarquia);
				$datosespaciales->setNdatespestado(1);

				$this->em->persist($datosespaciales);
				$this->em->flush();

				return new JsonModel(array(
					'success' => true,
					'tipo' => 'modal',
					'datos' => array(
					)
				));
			}
		}

		$view = new ViewModel();
		$view->setVariable('form', $form);
		$view->setVariable('ruta', $this->rutaRaiz);
		$view->setTerminal(true);

		return $view;
	}

	public function editarAction() {
		$id = (int) $this->params()->fromRoute('parametros', 0);
		$datosespaciales = $this->repo->find($id);

		$form = new DatosespacialesForm($this->em);
		$form->bind($datosespaciales);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$post = array_merge_recursive(
				$request->getPost()->toArray(),
				$request->getFiles()->toArray()
			);

			$form->setData($post);
			if ($form->isValid()) {
				$this->em->persist($datosespaciales);
				$this->em->flush();

				return new JsonModel(array(
					'success' => true,
					'tipo' => 'modal',
					'datos' => array(
						'keyId' => 'iddatosespaciales',
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
		$datosespaciales = $this->repo->find($id);

		$form = new Form();
		$submit = new Element\Button('enviar');
		$form->add($submit);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$datosespaciales->setNdatespestado(0);
			$this->em->persist($datosespaciales);
			$this->em->flush();

			return new JsonModel(array(
				'success' => true,
				'tipo' => 'modal',
				'datos' => array()
			));
		}

		$view = new ViewModel();
		$view->setVariable('datosespaciales', $datosespaciales)
		     ->setVariable('form', $form)
		     ->setTerminal(true);

		return $view;
	}

	public function ordenarAction() {
		$strParametros = $this->params()->fromRoute('parametros');
		parse_str($strParametros, $parametros);

		$id = $parametros['id'];
		$friendId = $parametros['friendId'];
		$tipo = $parametros['tipo'];

		$datosespaciales = $this->repo->find($id);
		$friend = $this->repo->find($friendId);

		$orden = $datosespaciales->getNdatespjerarquia();
		$friendOrden = $friend->getNdatespjerarquia();

		if ($tipo == 'prev') {
			$datosespacialessUpd = $this->repo->buscarMayoresParaOrdenar($friendOrden, $orden);

			foreach ($datosespacialessUpd as $datosespacialesUpd) {
				$datosespacialesUpd->setNdatespjerarquia($datosespacialesUpd->getNdatespjerarquia() + 1);
				$this->em->persist($datosespacialesUpd);
			}

		} elseif ($tipo == 'next') {
			$datosespacialessUpd = $this->repo->buscarMenoresParaOrdenar($friendOrden, $orden);

			foreach ($datosespacialessUpd as $datosespacialesUpd) {
				$datosespacialesUpd->setNdatespjerarquia($datosespacialesUpd->getNdatespjerarquia() - 1);
				$this->em->persist($datosespacialesUpd);
			}
		}

		$datosespaciales->setNdatespjerarquia($friendOrden);
		$this->em->persist($datosespaciales);

		$this->em->flush();

		return new JsonModel(array(
			'success' => true,
			'tipo' => 'modal',
			'datos' => array()
		));
	}

	public function publicarAction() {
		$id = $this->params()->fromRoute('parametros');
		$datosespaciales = $this->repo->find($id);

		if ($datosespaciales->getNdatespestado() != 2) {
			$form = new Form();
			$submit = new Element\Button('enviar');
			$submit->setLabel('Publicar');
			$submit->setAttributes(array(
				'class' => 'btn btn-modal eliminar'
			));
			$form->add($submit);

			$request = $this->getRequest();
			if ($request->isPost()) {
				$datosespaciales->setNdatespestado(2);
				$this->em->persist($datosespaciales);
				$this->em->flush();

				return new JsonModel(array(
					'success' => true,
					'tipo' => 'modal',
					'datos' => array()
				));
			}

			$view = new ViewModel();
			$view->setVariable('datosespaciales', $datosespaciales)
			     ->setVariable('form', $form)
			     ->setTerminal(true);
		} else {
			$view = new ViewModel();
			$view->setVariable('error', 'El item ya ha sido publicado');
			$view->setTemplate('sistema/datosespaciales/error.phtml');
			$view->setTerminal(true);
		}

		return $view;
	}

	private function eliminarArchivo($nombre) {
		$archivo = $this->publicPath . '/upload/img/' . $nombre;
		if (file_exists($archivo)) {
			unlink($archivo);
		}
	}
}