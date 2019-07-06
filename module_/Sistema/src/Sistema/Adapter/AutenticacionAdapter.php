<?php
namespace Sistema\Adapter;

use Zend\Authentication\Adapter\AdapterInterface;

class AutenticacionAdapter implements AdapterInterface {
	private $foo = true;

	public function authenticate() {
		if ($this->foo) {
			return true;
		}
	}
}
?>