<?php
namespace Sistema\Controller;

use Sistema\Entity\Boletin;
use Sistema\Form\BoletinForm;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class BoletinController extends AbstractActionController {
	private $usuarioLogueado;
	private $nctrcodigo = 64;
	private $rutaRaiz = 'sistema/boletin';
	private $opcionesItems = array(
		array('value' => 5, 'label' => 'Items: 5', 'selected' => false),
		array('value' => 10, 'label' => 'Items: 10', 'selected' => true),
		array('value' => 20, 'label' => 'Items: 20', 'selected' => false)
	);
	private $items;
	private $em;
	private $emNamespace = 'Sistema\Entity\Boletin';
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
		$boletins = $this->repo->buscar($campos, $paginacion);
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
		$view->setVariable('boletins', $boletins)
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
		$boletins = $this->repo->buscar($campos, $paginacion);
		$paginador = $this->repo->getPaginador($campos, $paginacion);

		$viewLista = new ViewModel();
		$viewLista->setTemplate('sistema/boletin/lista.phtml')
		          ->setVariable('boletins', $boletins)
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
		$boletin = new Boletin();

		$form = new BoletinForm($this->em, $this->publicPath);
		$form->bind($boletin);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$post = array_merge_recursive(
				$request->getPost()->toArray(),
				$request->getFiles()->toArray()
			);

			$form->setData($post);
			if ($form->isValid()) {
				// cbolrutaimg
				$cbolrutaimg = $boletin->getCbolrutaimg();
				$cbolrutaimg = $cbolrutaimg['tmp_name'];

				if ($cbolrutaimg != '') {
					$cbolrutaimg = substr(strrchr($cbolrutaimg, '/'), 1);
					$boletin->setCbolrutaimg($cbolrutaimg);
				} else {
					$boletin->setCbolrutaimg(null);
				}

				// cbolrutapdf
				$cbolrutapdf = $boletin->getCbolrutapdf();
				$cbolrutapdf = $cbolrutapdf['tmp_name'];

				if ($cbolrutapdf != '') {
					$cbolrutapdf = substr(strrchr($cbolrutapdf, '/'), 1);
					$boletin->setCbolrutapdf($cbolrutapdf);
				} else {
					$boletin->setCbolrutapdf(null);
				}

				$ultimoOrden = $this->repo->buscarUltimoOrden();
				$cboljerarquia = $ultimoOrden + 1;

				$boletin->setCboljerarquia($cboljerarquia);
				$boletin->setDbolfechareg(new \DateTime());
				$boletin->setNbolestado(1);

				$this->em->persist($boletin);
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
		$boletin = $this->repo->find($id);

		$oldImgString = $boletin->getCbolrutaimg();
		$oldPdfString = $boletin->getCbolrutapdf();

		$form = new BoletinForm($this->em, $this->publicPath, $boletin);
		$form->bind($boletin);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$post = array_merge_recursive(
				$request->getPost()->toArray(),
				$request->getFiles()->toArray()
			);

			$form->setData($post);
			if ($form->isValid()) {
				// cbolrutaimg
				$imgString = $post['imgString'];

				if ($imgString != $oldImgString) {
					$this->eliminarArchivo($oldImgString);

					if ($imgString != '') {
						$cbolrutaimg = $boletin->getCbolrutaimg();
						$cbolrutaimg = $cbolrutaimg['tmp_name'];
						$cbolrutaimg = substr(strrchr($cbolrutaimg, '/'), 1);
						$boletin->setCbolrutaimg($cbolrutaimg);
					} else {
						$boletin->setCbolrutaimg(null);
					}
				} else {
					$boletin->setCbolrutaimg($oldImgString);
				}

				// cbolrutapdf
				$pdfString = $post['pdfString'];

				if ($pdfString != $oldPdfString) {
					$this->eliminarArchivo($oldPdfString);

					if ($pdfString != '') {
						$cbolrutapdf = $boletin->getCbolrutapdf();
						$cbolrutapdf = $cbolrutapdf['tmp_name'];
						$cbolrutapdf = substr(strrchr($cbolrutapdf, '/'), 1);
						$boletin->setCbolrutapdf($cbolrutapdf);
					} else {
						$boletin->setCbolrutapdf(null);
					}
				} else {
					$boletin->setCbolrutapdf($oldPdfString);
				}

				$this->em->persist($boletin);
				$this->em->flush();

				return new JsonModel(array(
					'success' => true,
					'tipo' => 'modal',
					'datos' => array(
						'keyId' => 'idboletin',
						'id' => $id
					)
				));
			}
		}

		$rutaRaiz = $this->rutaRaiz;
		$cbolrutaimg = $boletin->getCbolrutaimg();

		$view = new ViewModel();
		$view->setVariable('id', $id)
		     ->setVariable('form', $form)
		     ->setVariable('cbolrutaimg', $cbolrutaimg)
		     ->setVariable('ruta', $rutaRaiz)
		     ->setTerminal(true);

		return $view;
	}

	public function eliminarAction() {
		$id = $this->params()->fromRoute('parametros');
		$boletin = $this->repo->find($id);

		$form = new Form();
		$submit = new Element\Button('enviar');
		$form->add($submit);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$boletin->setNbolestado(0);
			$this->em->persist($boletin);
			$this->em->flush();

			return new JsonModel(array(
				'success' => true,
				'tipo' => 'modal',
				'datos' => array()
			));
		}

		$view = new ViewModel();
		$view->setVariable('boletin', $boletin)
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

		$boletin = $this->repo->find($id);
		$friend = $this->repo->find($friendId);

		$orden = $boletin->getCboljerarquia();
		$friendOrden = $friend->getCboljerarquia();

		if ($tipo == 'prev') {
			$boletinsUpd = $this->repo->buscarMayoresParaOrdenar($friendOrden, $orden);

			foreach ($boletinsUpd as $boletinUpd) {
				$boletinUpd->setCboljerarquia($boletinUpd->getCboljerarquia() + 1);
				$this->em->persist($boletinUpd);
			}

		} elseif ($tipo == 'next') {
			$boletinsUpd = $this->repo->buscarMenoresParaOrdenar($friendOrden, $orden);

			foreach ($boletinsUpd as $boletinUpd) {
				$boletinUpd->setCboljerarquia($boletinUpd->getCboljerarquia() - 1);
				$this->em->persist($boletinUpd);
			}
		}

		$boletin->setCboljerarquia($friendOrden);
		$this->em->persist($boletin);

		$this->em->flush();

		return new JsonModel(array(
			'success' => true,
			'tipo' => 'modal',
			'datos' => array()
		));
	}

	public function publicarAction() {
		$id = $this->params()->fromRoute('parametros');
		$boletin = $this->repo->find($id);

		if ($boletin->getNbolestado() != 2) {
			$form = new Form();
			$submit = new Element\Button('enviar');
			$submit->setLabel('Publicar');
			$submit->setAttributes(array(
				'class' => 'btn btn-modal eliminar'
			));
			$form->add($submit);

			$request = $this->getRequest();
			if ($request->isPost()) {
				$boletin->setNbolestado(2);
				$this->em->persist($boletin);
				$this->em->flush();

				return new JsonModel(array(
					'success' => true,
					'tipo' => 'modal',
					'datos' => array()
				));
			}

			$view = new ViewModel();
			$view->setVariable('boletin', $boletin)
			     ->setVariable('form', $form)
			     ->setTerminal(true);
		} else {
			$view = new ViewModel();
			$view->setVariable('error', 'El item ya ha sido publicado');
			$view->setTemplate('sistema/boletin/error.phtml');
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