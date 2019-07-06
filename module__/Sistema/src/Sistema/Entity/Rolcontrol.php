<?php

namespace Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rolcontrol
 *
 * @ORM\Table(name="rolcontrol", indexes={@ORM\Index(name="IDX_651F356726DDAF5A", columns={"nrolcodigo"}), @ORM\Index(name="IDX_651F35678B563386", columns={"nctrcodigo"})})
 * @ORM\Entity(repositoryClass="Sistema\Repository\RolcontrolRepository")
 */
class Rolcontrol {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nrolctrestado", type="integer", nullable=false)
	 */
	private $nrolctrestado;

	/**
	 * @var \Sistema\Entity\Rol
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="NONE")
	 * @ORM\ManyToOne(targetEntity="Sistema\Entity\Rol", inversedBy="rolcontrols")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="nrolcodigo", referencedColumnName="nrolcodigo")
	 * })
	 */
	private $rol;

	/**
	 * @var \Sistema\Entity\Control
	 *
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="NONE")
	 * @ORM\ManyToOne(targetEntity="Sistema\Entity\Control", inversedBy="rolcontrols")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="nctrcodigo", referencedColumnName="nctrcodigo")
	 * })
	 */
	private $control;

	/**
	 * Set nrolctrestado
	 *
	 * @param integer $nrolctrestado
	 * @return Rolcontrol
	 */
	public function setNrolctrestado($nrolctrestado) {
		$this->nrolctrestado = $nrolctrestado;

		return $this;
	}

	/**
	 * Get nrolctrestado
	 *
	 * @return integer
	 */
	public function getNrolctrestado() {
		return $this->nrolctrestado;
	}

	/**
	 * Set rol
	 *
	 * @param \Sistema\Entity\Rol $rol
	 * @return Rolcontrol
	 */
	public function setRol(\Sistema\Entity\Rol $rol = null) {
		$this->rol = $rol;

		return $this;
	}

	/**
	 * Get rol
	 *
	 * @return \Sistema\Entity\Rol
	 */
	public function getRol() {
		return $this->rol;
	}

	/**
	 * Set control
	 *
	 * @param \Sistema\Entity\Control $control
	 * @return Rolcontrol
	 */
	public function setControl(\Sistema\Entity\Control $control = null) {
		$this->control = $control;

		return $this;
	}

	/**
	 * Get control
	 *
	 * @return \Sistema\Entity\Control
	 */
	public function getControl() {
		return $this->control;
	}
}
