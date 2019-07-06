<?php
namespace Sistema\Controller;

use Sistema\Entity\Galeria;
use Sistema\Form\GaleriaForm;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class GaleriaController extends AbstractActionController {
	private $usuarioLogueado;
	private $nctrcodigo = 49;
	private $rutaRaiz = 'sistema/galeria';
	private $opcionesItems = array(
		array('value' => 5, 'label' => 'Items: 5', 'selected' => false),
		array('value' => 10, 'label' => 'Items: 10', 'selected' => true),
		array('value' => 20, 'label' => 'Items: 20', 'selected' => false)
	);
	private $items;
	private $em;
	private $emNamespace = 'Sistema\Entity\Galeria';
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

		$serviceManager = $e->getApplication()->getServiceManager();

		$container = new Container('perusuario');
		$this->usuarioLogueado = $this->em->getRepository('Sistema\Entity\Perusuario')->find($container->id);

		$config = $serviceManager->get('config');
		$this->publicPath = $config['asset_manager']['resolver_configs']['paths'][0];

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
		$galerias = $this->repo->buscar($campos, $paginacion);
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
		$view->setVariable('galerias', $galerias)
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
		$galerias = $this->repo->buscar($campos, $paginacion);
		$paginador = $this->repo->getPaginador($campos, $paginacion);

		$viewLista = new ViewModel();
		$viewLista->setTemplate('sistema/galeria/lista.phtml')
		          ->setVariable('galerias', $galerias)
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
		$galeria = new Galeria();

		$form = new GaleriaForm($this->em, $this->publicPath);
		$form->bind($galeria);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$requestPost = $request->getPost();
			if ($requestPost['padre'] == '') {
				$requestPost['padre'] = null;
			}
			$post = array_merge_recursive(
				$request->getPost()->toArray(),
				$request->getFiles()->toArray()
			);

			$form->setData($post);
			if ($form->isValid()) {
				$cgalimagen = $galeria->getCgalimagen();
				$cgalimagen = $cgalimagen['tmp_name'];

				if ($cgalimagen != '') {
					$cgalimagen = substr(strrchr($cgalimagen, '/'), 1);
					$galeria->setCgalimagen($cgalimagen);
				} else {
					$galeria->setCgalimagen(null);
				}

				$ultimoOrden = $this->repo->buscarUltimoOrden();
				$cnotjerarquia = $ultimoOrden + 1;

				$galeria->setCgaljerarquia($cnotjerarquia);
				$galeria->setNgalestado(1);

				$this->em->persist($galeria);
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
		$galeria = $this->repo->find($id);

		$oldImgString = $galeria->getCgalimagen();

		$form = new GaleriaForm($this->em, $this->publicPath, $galeria);
		$form->bind($galeria);

		if ($galeria->getDgalfecha()) {
			$form->get('dgalfecha')->setValue($galeria->getDgalfecha()->format('d-m-Y'));
		}

		$request = $this->getRequest();
		if ($request->isPost()) {
			$requestPost = $request->getPost();
			if ($requestPost['padre'] == '') {
				$requestPost['padre'] = null;
			}
			$post = array_merge_recursive(
				$request->getPost()->toArray(),
				$request->getFiles()->toArray()
			);

			$form->setData($post);
			if ($form->isValid()) {
				$imgString = $post['imgString'];

				if ($imgString != $oldImgString) {
					$this->eliminarArchivo($oldImgString);

					if ($imgString != '') {
						$cgalimagen = $galeria->getCgalimagen();
						$cgalimagen = $cgalimagen['tmp_name'];
						$cgalimagen = substr(strrchr($cgalimagen, '/'), 1);
						$galeria->setCgalimagen($cgalimagen);
					} else {
						$galeria->setCgalimagen(null);
					}
				} else {
					$galeria->setCgalimagen($oldImgString);
				}

				$this->em->persist($galeria);
				$this->em->flush();

				return new JsonModel(array(
					'success' => true,
					'tipo' => 'modal',
					'datos' => array(
						'keyId' => 'idgaleria',
						'id' => $id
					)
				));
			}
		}

		$rutaRaiz = $this->rutaRaiz;
		$cgalimagen = $galeria->getCgalimagen();

		$view = new ViewModel();
		$view->setVariable('id', $id)
		     ->setVariable('form', $form)
		     ->setVariable('cgalimagen', $cgalimagen)
		     ->setVariable('ruta', $rutaRaiz)
		     ->setTerminal(true);

		return $view;
	}

	public function eliminarAction() {
		$id = $this->params()->fromRoute('parametros');
		$galeria = $this->repo->find($id);

		$form = new Form();
		$submit = new Element\Button('enviar');
		$form->add($submit);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$galeria->setNgalestado(0);
			$this->em->persist($galeria);
			$this->em->flush();

			return new JsonModel(array(
				'success' => true,
				'tipo' => 'modal',
				'datos' => array()
			));
		}

		$view = new ViewModel();
		$view->setVariable('galeria', $galeria)
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

		$galeria = $this->repo->find($id);
		$friend = $this->repo->find($friendId);

		$orden = $galeria->getCgaljerarquia();
		$friendOrden = $friend->getCgaljerarquia();

		if ($tipo == 'prev') {
			$galeriasUpd = $this->repo->buscarMayoresParaOrdenar($friendOrden, $orden);

			foreach ($galeriasUpd as $galeriaUpd) {
				$galeriaUpd->setCgaljerarquia($galeriaUpd->getCgaljerarquia() + 1);
				$this->em->persist($galeriaUpd);
			}

		} elseif ($tipo == 'next') {
			$galeriasUpd = $this->repo->buscarMenoresParaOrdenar($friendOrden, $orden);

			foreach ($galeriasUpd as $galeriaUpd) {
				$galeriaUpd->setCgaljerarquia($galeriaUpd->getCgaljerarquia() - 1);
				$this->em->persist($galeriaUpd);
			}
		}

		$galeria->setCgaljerarquia($friendOrden);
		$this->em->persist($galeria);

		$this->em->flush();

		return new JsonModel(array(
			'success' => true,
			'tipo' => 'modal',
			'datos' => array()
		));
	}

	public function publicarAction() {
		$id = $this->params()->fromRoute('parametros');
		$galeria = $this->repo->find($id);

		if ($galeria->getNgalestado() != 2) {
			$form = new Form();
			$submit = new Element\Button('enviar');
			$submit->setLabel('Publicar');
			$submit->setAttributes(array(
				'class' => 'btn btn-modal eliminar'
			));
			$form->add($submit);

			$request = $this->getRequest();
			if ($request->isPost()) {
				$galeria->setNgalestado(2);
				$this->em->persist($galeria);
				$this->em->flush();

				return new JsonModel(array(
					'success' => true,
					'tipo' => 'modal',
					'datos' => array()
				));
			}

			$view = new ViewModel();
			$view->setVariable('galeria', $galeria)
			     ->setVariable('form', $form)
			     ->setTerminal(true);
		} else {
			$view = new ViewModel();
			$view->setVariable('error', 'El item ya ha sido publicado');
			$view->setTemplate('sistema/galeria/error.phtml');
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