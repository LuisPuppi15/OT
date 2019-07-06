<?php
namespace Sistema\Controller;

use Sistema\Entity\Slider;
use Sistema\Form\SliderForm;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class SliderController extends AbstractActionController {
	private $usuarioLogueado;
	private $nctrcodigo = 30;
	private $rutaRaiz = 'sistema/slider';
	private $opcionesItems = array(
		array('value' => 5, 'label' => 'Items: 5', 'selected' => false),
		array('value' => 10, 'label' => 'Items: 10', 'selected' => true),
		array('value' => 20, 'label' => 'Items: 20', 'selected' => false)
	);
	private $items;
	private $em;
	private $emNamespace = 'Sistema\Entity\Slider';
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
		$sliders = $this->repo->buscar($campos, $paginacion);
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
		$view->setVariable('sliders', $sliders)
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
		$sliders = $this->repo->buscar($campos, $paginacion);
		$paginador = $this->repo->getPaginador($campos, $paginacion);

		$viewLista = new ViewModel();
		$viewLista->setTemplate('sistema/slider/lista.phtml')
		          ->setVariable('sliders', $sliders)
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
		$slider = new Slider();

		$form = new SliderForm($this->em, $this->publicPath);
		$form->bind($slider);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$post = array_merge_recursive(
				$request->getPost()->toArray(),
				$request->getFiles()->toArray()
			);

			$form->setData($post);
			if ($form->isValid()) {
				$cslirutimgfull = $slider->getCslirutimgfull();
				$cslirutimgfull = $cslirutimgfull['tmp_name'];

				if ($cslirutimgfull != '') {
					$cslirutimgfull = substr(strrchr($cslirutimgfull, '/'), 1);
					$slider->setCslirutimgfull($cslirutimgfull);
				} else {
					$slider->setCslirutimgfull(null);
				}

				$ultimoOrden = $this->repo->buscarUltimoOrden();
				$cslijerrarquia = $ultimoOrden + 1;

				$slider->setCslijerrarquia($cslijerrarquia);
				$slider->setDslifechareg(new \DateTime());
				$slider->setNsliestado(1);

				$this->em->persist($slider);
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
		$slider = $this->repo->find($id);

		$oldImgfullString = $slider->getCslirutimgfull();

		$form = new SliderForm($this->em, $this->publicPath, $slider);
		$form->bind($slider);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$post = array_merge_recursive(
				$request->getPost()->toArray(),
				$request->getFiles()->toArray()
			);

			$form->setData($post);
			if ($form->isValid()) {
				$imgfullString = $post['imgfullString'];

				if ($imgfullString != $oldImgfullString) {
					$this->eliminarArchivo($oldImgfullString);

					if ($imgfullString != '') {
						$cslirutimgfull = $slider->getCslirutimgfull();
						$cslirutimgfull = $cslirutimgfull['tmp_name'];
						$cslirutimgfull = substr(strrchr($cslirutimgfull, '/'), 1);
						$slider->setCslirutimgfull($cslirutimgfull);
					} else {
						$slider->setCslirutimgfull(null);
					}
				} else {
					$slider->setCslirutimgfull($oldImgfullString);
				}

				$this->em->persist($slider);
				$this->em->flush();

				return new JsonModel(array(
					'success' => true,
					'tipo' => 'modal',
					'datos' => array(
						'keyId' => 'idslider',
						'id' => $id
					)
				));
			}
		}

		$rutaRaiz = $this->rutaRaiz;
		$cslirutimgfull = $slider->getCslirutimgfull();

		$view = new ViewModel();
		$view->setVariable('id', $id)
		     ->setVariable('form', $form)
		     ->setVariable('cslirutimgfull', $cslirutimgfull)
		     ->setVariable('ruta', $rutaRaiz)
		     ->setTerminal(true);

		return $view;
	}

	public function eliminarAction() {
		$id = $this->params()->fromRoute('parametros');
		$slider = $this->repo->find($id);

		$form = new Form();
		$submit = new Element\Button('enviar');
		$form->add($submit);

		$request = $this->getRequest();
		if ($request->isPost()) {
			$slider->setNsliestado(0);
			$this->em->persist($slider);
			$this->em->flush();

			return new JsonModel(array(
				'success' => true,
				'tipo' => 'modal',
				'datos' => array()
			));
		}

		$view = new ViewModel();
		$view->setVariable('slider', $slider)
		     ->setVariable('form', $form)
		     ->setTerminal(true);

		return $view;
	}

	public function publicarAction() {
		$id = $this->params()->fromRoute('parametros');
		$slider = $this->repo->find($id);

		if ($slider->getNsliestado() != 2) {
			$form = new Form();
			$submit = new Element\Button('enviar');
			$submit->setLabel('Publicar');
			$submit->setAttributes(array(
				'class' => 'btn btn-modal eliminar'
			));
			$form->add($submit);

			$request = $this->getRequest();
			if ($request->isPost()) {
				$slider->setNsliestado(2);
				$this->em->persist($slider);
				$this->em->flush();

				return new JsonModel(array(
					'success' => true,
					'tipo' => 'modal',
					'datos' => array()
				));
			}

			$view = new ViewModel();
			$view->setVariable('slider', $slider)
			     ->setVariable('form', $form)
			     ->setTerminal(true);
		} else {
			$view = new ViewModel();
			$view->setVariable('error', 'El contenido ya ha sido publicado');
			$view->setTemplate('sistema/slider/error.phtml');
			$view->setTerminal(true);
		}

		return $view;
	}

	public function ordenarAction() {
		$strParametros = $this->params()->fromRoute('parametros');
		parse_str($strParametros, $parametros);

		$id = $parametros['id'];
		$friendId = $parametros['friendId'];
		$tipo = $parametros['tipo'];

		$slider = $this->repo->find($id);
		$friend = $this->repo->find($friendId);

		$orden = $slider->getCslijerrarquia();
		$friendOrden = $friend->getCslijerrarquia();

		if ($tipo == 'prev') {
			$slidersUpd = $this->repo->buscarMayoresParaOrdenar($friendOrden, $orden);

			foreach ($slidersUpd as $sliderUpd) {
				$sliderUpd->setCslijerrarquia($sliderUpd->getCslijerrarquia() + 1);
				$this->em->persist($sliderUpd);
			}

		} elseif ($tipo == 'next') {
			$slidersUpd = $this->repo->buscarMenoresParaOrdenar($friendOrden, $orden);

			foreach ($slidersUpd as $sliderUpd) {
				$sliderUpd->setCslijerrarquia($sliderUpd->getCslijerrarquia() - 1);
				$this->em->persist($sliderUpd);
			}
		}

		$slider->setCslijerrarquia($friendOrden);
		$this->em->persist($slider);

		$this->em->flush();

		return new JsonModel(array(
			'success' => true,
			'tipo' => 'modal',
			'datos' => array()
		));
	}

	private function eliminarArchivo($nombre) {
		$archivo = $this->publicPath . '/upload/img/' . $nombre;
		if (file_exists($archivo)) {
			unlink($archivo);
		}
	}
}