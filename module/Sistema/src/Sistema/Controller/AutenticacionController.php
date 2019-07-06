<?php
namespace Sistema\Controller;

use Sistema\Form\AutenticacionForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AutenticacionController extends AbstractActionController {
	private $rutaRaiz = 'sistema';
	private $em;
	private $authService;

	public function onDispatch(\Zend\Mvc\MvcEvent $e) {
		$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		$this->authService = $this->getServiceLocator()->get('Doctrine\AuthenticationService');

		return parent::onDispatch($e);
	}

	public function loginAction() {
		$form = new AutenticacionForm();
		$mensajeError = '';

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$data = $form->getData();

				$adapter = $this->authService;
				$adapter->setIdentityValue($data['cperusuname']);
				$adapter->setCredentialValue($data['cperusuclave']);
				$result = $this->authService->authenticate();

				if ($result->isValid()) {
					$perusuario = $result->getIdentity();

					switch ($perusuario->getNperusuestado()) {
						case 0:
							$mensajeError = 'Credenciales incorrectas';
							break;
						case 1:
							$container = new \Zend\Session\Container('perusuario');
							$container->id = $perusuario->getNperusucodigo();
							return $this->redirect()->toRoute('sistema');
							break;
						case 2:
							$mensajeError = 'El usuario no se encuentra habilitado';
							break;
					}
				} else {
					$mensajeError = 'Credenciales incorrectas';
				}
			}
		}

		$view = new ViewModel();

		$view->setVariable('form', $form)
		     ->setVariable('mensajeError', $mensajeError)
		     ->setVariable('ruta', $this->rutaRaiz);

		return $view;
	}

	public function logoutAction() {
		// $this->authService->clearIdentity();
		session_unset();
		return $this->redirect()->toRoute('sistema/autenticacion', array('action' => 'login'));
	}
}
