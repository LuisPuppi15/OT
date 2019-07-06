<?php
namespace Sistema\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PortadaController extends AbstractActionController {
	private $usuarioLogueado;
	private $rutaRaiz = 'sistema/portada';
	private $em;

	public function onDispatch(\Zend\Mvc\MvcEvent $e) {
		$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		$container = new \Zend\Session\Container('perusuario');
		$this->usuarioLogueado = $this->em->getRepository('Sistema\Entity\Perusuario')->find($container->id);

		return parent::onDispatch($e);
	}

	public function inicioAction() {
		$repoControl = $this->em->getRepository('Sistema\Entity\Control');

		$nperusucodigo = $this->usuarioLogueado->getNperusucodigo();
		$controlsPorUsuario = $repoControl->buscarPorUsuario($nperusucodigo);

		$modulos = array();
		$menus = array();
		$subMenus = array();

		foreach ($controlsPorUsuario as $control) {
			$nctrtipo = $control->getNctrtipo();

			switch ($nctrtipo) {
				case '5000':
					$moduloId = $control->getNctrcodigo();
					$modulos[$moduloId] = $control;

					if (!isset($menus[$moduloId])) {
						$menus[$moduloId] = array();
					}
					if (!isset($subMenus[$moduloId])) {
						$subMenus[$moduloId] = array();
					}
					break;

				case '5001':
					$padre = $control->getPadre();
					$procede = true;

					if ($padre) {
						if ($padre->getNctrtipo() == 5001) {
							$procede = false;
						}
					}

					if ($procede) {
						$moduloId = $control->getPadre()->getNctrcodigo();
						$menuId = $control->getNctrcodigo();
						$menus[$moduloId][$menuId] = $control;

						if (!isset($subMenus[$moduloId][$menuId])) {
							$subMenus[$moduloId][$menuId] = array();
						}
					}

					$recursividad = function ($controls, $moduloId, $menuId) use ($subMenus) {
						foreach ($controls as $control) {
							if ($control->getNctrtipo() == '5001') {
								$subMenuId = $control->getNctrcodigo();
								$subMenus[$moduloId][$menuId][$subMenuId] = $control;
							}
						}

						return $subMenus;
					};

					$hijos = $repoControl->enviarHijos($control->getNctrcodigo());
					if (count($hijos)) {
						$menuId = $control->getNctrcodigo();
						$subMenus = $recursividad($hijos, $moduloId, $menuId);
					}
					break;
			}
		}

		$fecha = $this->getFecha();

		$view = new ViewModel();
		$view->setVariable('persona', $this->usuarioLogueado->getPersona());
		$view->setVariable('modulos', $modulos);
		$view->setVariable('menus', $menus);
		$view->setVariable('subMenus', $subMenus);
		$view->setVariable('fecha', $fecha);

		return $view;
	}

	private function getFecha() {
		$dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado");
		$meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
		$fecha = $dias[date('w')] . " " . date('d') . " de " . $meses[date('n') - 1] . " del " . date('Y');

		return $fecha;
	}
}
