<?php
namespace Sistema\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;

class AutenticacionListener implements ListenerAggregateInterface {
	public function attach(EventManagerInterface $events) {
		$this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'), -1);
	}

	public function detach(EventManagerInterface $events) {
		foreach ($this->listeners as $index => $listener) {
			if ($events->detach($listener)) {
				unset($this->listeners[$index]);
			}
		}
	}

	public function onRoute(MvcEvent $event) {
		$routeMatch = $event->getRouteMatch();
		$matchedRouteName = $routeMatch->getMatchedRouteName();

		if (strpos($matchedRouteName, 'sistema') === false) {
			return;

		} else {
			$container = new \Zend\Session\Container('perusuario');
			if ($container->id) {
				return;
			}

			$action = $routeMatch->getParam('action');
			$currentPage = "$matchedRouteName::$action";

			if ($currentPage == 'sistema/autenticacion::login') {
				return;
			}

			$router = $event->getRouter();
			return $this->redirect($router);
		}
	}

	protected function redirect($router) {
		$url = $router->assemble(array('action' => 'login'), array('name' => 'sistema/autenticacion'));

		$response = new Response();
		$response->setStatusCode(302);

		$headers = $response->getHeaders();
		$headers->addHeaderLine('Location', $url);

		return $response;
	}
}