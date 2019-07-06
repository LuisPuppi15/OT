<?php
namespace Sistema\Controller;

use Sistema\Entity\Publicacion;
use Sistema\Form\PublicacionForm;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class PublicacionController extends AbstractActionController {
	private $usuarioLogueado;
	private $nctrcodigo = 43;
	private $rutaRaiz = 'sistema/publicacion';
	private $opcionesItems = array(
		array('value' => 5, 'label' => 'Items: 5', 'selected' => false),
		array('value' => 10, 'label' => 'Items: 10', 'selected' => true),
		array('value' => 20, 'label' => 'Items: 20', 'selected' => false)
	);
	private $items;
	private $em;
	private $emNamespace = 'Sistema\Entity\Publicacion';
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
		$publicacions = $this->repo->buscar($campos, $paginacion);
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
		$view->setVariable('publicacions', $publicacions)
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
		$publicacions = $this->repo->buscar($campos, $paginacion);
		$paginador = $this->repo->getPaginador($campos, $paginacion);

		$viewLista = new ViewModel();
		$viewLista->setTemplate('sistema/publicacion/lista.phtml')
		          ->setVariable('publicacions', $publicacions)
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
		$publicacion = new Publicacion();

		$form = new PublicacionForm($this->em, $this->publicPath);
		$form->bind($publicacion);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$post = array_merge_recursive(
				$request->getPost()->toArray(),
				$request->getFiles()->toArray()
			);

			$form->setData($post);
			if ($form->isValid()) {
				// cpubimagen
				$cpubimagen = $publicacion->getCpubimagen();
				$cpubimagen = $cpubimagen['tmp_name'];

				if ($cpubimagen != '') {
					$cpubimagen = substr(strrchr($cpubimagen, '/'), 1);
					$publicacion->setCpubimagen($cpubimagen);
				} else {
					$publicacion->setCpubimagen(null);
				}

				// cpubpdf
				$cpubpdf = $publicacion->getCpubpdf();
				$cpubpdf = $cpubpdf['tmp_name'];

				if ($cpubpdf != '') {
					$cpubpdf = substr(strrchr($cpubpdf, '/'), 1);
					$publicacion->setCpubpdf($cpubpdf);
				} else {
					$publicacion->setCpubpdf(null);
				}

				$ultimoOrden = $this->repo->buscarUltimoOrden();
				$cnotjerarquia = $ultimoOrden + 1;

				$publicacion->setCpubjerarquia($cnotjerarquia);
				$publicacion->setNpubestado(1);

				$this->em->persist($publicacion);
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
		$publicacion = $this->repo->find($id);

		$oldImgString = $publicacion->getCpubimagen();
		$oldPdfString = $publicacion->getCpubpdf();

		$form = new PublicacionForm($this->em, $this->publicPath, $publicacion);
		$form->bind($publicacion);

		if ($publicacion->getCpubanio()) {
			// $form->get('cpubanio')->setValue($publicacion->getCpubanio()->format('d-m-Y'));
		}

		$request = $this->getRequest();
		if ($request->isPost()) {
			$post = array_merge_recursive(
				$request->getPost()->toArray(),
				$request->getFiles()->toArray()
			);

			$form->setData($post);
			if ($form->isValid()) {
				// cpubimagen
				$imgString = $post['imgString'];

				if ($imgString != $oldImgString) {
					$this->eliminarArchivo($oldImgString);

					if ($imgString != '') {
						$cpubimagen = $publicacion->getCpubimagen();
						$cpubimagen = $cpubimagen['tmp_name'];
						$cpubimagen = substr(strrchr($cpubimagen, '/'), 1);
						$publicacion->setCpubimagen($cpubimagen);
					} else {
						$publicacion->setCpubimagen(null);
					}
				} else {
					$publicacion->setCpubimagen($oldImgString);
				}

				// cpubpdf
				$pdfString = $post['pdfString'];

				if ($pdfString != $oldPdfString) {
					$this->eliminarArchivo($oldPdfString);

					if ($pdfString != '') {
						$cpubpdf = $publicacion->getCpubpdf();
						$cpubpdf = $cpubpdf['tmp_name'];
						$cpubpdf = substr(strrchr($cpubpdf, '/'), 1);
						$publicacion->setCpubpdf($cpubpdf);
					} else {
						$publicacion->setCpubpdf(null);
					}
				} else {
					$publicacion->setCpubpdf($oldPdfString);
				}

				$this->em->persist($publicacion);
				$this->em->flush();

				return new JsonModel(array(
					'success' => true,
					'tipo' => 'modal',
					'datos' => array(
						'keyId' => 'idpublicacion',
						'id' => $id
					)
				));
			}
		}

		$rutaRaiz = $this->rutaRaiz;
		$cpubimagen = $publicacion->getCpubimagen();

		$view = new ViewModel();
		$view->setVariable('id', $id)
		     ->setVariable('form', $form)
		     ->setVariable('cpubimagen', $cpubimagen)
		     ->setVariable('ruta', $rutaRaiz)
		     ->setTerminal(true);

		return $view;
	}

	public function eliminarAction() {
		$id = $this->params()->fromRoute('parametros');
		$publicacion = $this->repo->find($id);

		$form = new Form();
		$submit = new Element\Button('enviar');
		$form->add($submit);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$publicacion->setNpubestado(0);
			$this->em->persist($publicacion);
			$this->em->flush();

			return new JsonModel(array(
				'success' => true,
				'tipo' => 'modal',
				'datos' => array()
			));
		}

		$view = new ViewModel();
		$view->setVariable('publicacion', $publicacion)
		     ->setVariable('form', $form)
		     ->setTerminal(true);

		return $view;
	}

	public function publicarAction() {
		$id = $this->params()->fromRoute('parametros');
		$publicacion = $this->repo->find($id);

		if ($publicacion->getNpubestado() != 2) {
			$form = new Form();
			$submit = new Element\Button('enviar');
			$submit->setLabel('Publicar');
			$submit->setAttributes(array(
				'class' => 'btn btn-modal eliminar'
			));
			$form->add($submit);

			$request = $this->getRequest();
			if ($request->isPost()) {
				$publicacion->setNpubestado(2);
				$this->em->persist($publicacion);
				$this->em->flush();

				return new JsonModel(array(
					'success' => true,
					'tipo' => 'modal',
					'datos' => array()
				));
			}

			$view = new ViewModel();
			$view->setVariable('publicacion', $publicacion)
			     ->setVariable('form', $form)
			     ->setTerminal(true);
		} else {
			$view = new ViewModel();
			$view->setVariable('error', 'El contenido ya ha sido publicado');
			$view->setTemplate('sistema/publicacion/error.phtml');
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