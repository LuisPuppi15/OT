<?php

namespace Sistema\Service;

use Sistema\Entity\Perusuario;
use Zend\Authentication\AuthenticationService as ZendAuthenticationService;

class AutenticacionService extends ZendAuthenticationService {
	/**
	 * This method makes sure that we always get a User-object
	 * we can call methods on. In case of a non-authorized user
	 * we will receive a dummy object without storage or with
	 * session storage. So data might be lost!
	 */
	public function getIdentity() {
		$storage = $this->getStorage();

		if ($storage->isEmpty()) {
			return new \Sistema\Entity\Perusuario\Dummy();
		}

		$userid = $storage->read();

		$user = $this->getEntityManager()->find('Sistema\Entity\Perusuario', $userid);

		if ($user == null) {
			return new \Sistema\Entity\Perusuario\Dummy();
		} else {
			return $user;
		}
	}

	/**
	 * Register a new user to the system. The user password will by hashed before
	 * it will be saved to the database.
	 */
	public function register(User $user) {

	}

	/**
	 * Reset the users password to a random value and send an e-mail to the
	 * user containing the new password.
	 */
	public function resetPassword(User $user) {

	}

	/**
	 * Delete a users account from the database. This does not really delete the
	 * user, as there are too many connections to all other tables, but rather
	 * deletes all personal information from the user records.
	 */
	public function delete(User $user) {

	}

	public function setEntityManager(\Doctrine\ORM\EntityManager $entityManager) {
		$this->entityManager = $entityManager;
	}

	public function getEntityManager() {
		return $this->entityManager;
	}
}