<?php

namespace Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Persona
 *
 * @ORM\Table(name="persona")
 * @ORM\Entity(repositoryClass="Sistema\Repository\PersonaRepository")
 */
class Persona {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="npercodigo", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="persona_npercodigo_seq", allocationSize=1, initialValue=1)
	 */
	private $npercodigo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cpernombre", type="string", length=500, nullable=false)
	 */
	private $cpernombre;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cperapellidos", type="string", length=500, nullable=true)
	 */
	private $cperapellidos;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="nperdni", type="string", length=8, nullable=false)
	 */
	private $nperdni;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dpernacimiento", type="date", nullable=true)
	 */
	private $dpernacimiento;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="npertipo", type="integer", nullable=false)
	 */
	private $npertipo;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nperestado", type="integer", nullable=false)
	 */
	private $nperestado;

	/**
	 * @var \Sistema\Entity\Perusuario
	 * @ORM\OneToMany(targetEntity="Sistema\Entity\Perusuario", mappedBy="persona")
	 */
	protected $perusuarios;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->perusuarios = new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function __toString() {
		$cadena = $this->cpernombre;
		if ($this->cperapellidos) {
			$cadena .= ' ' . $this->cperapellidos;
		}
		return $cadena;
	}

	/**
	 * Get npercodigo
	 *
	 * @return integer
	 */
	public function getNpercodigo() {
		return $this->npercodigo;
	}

	/**
	 * Set cpernombre
	 *
	 * @param string $cpernombre
	 * @return Persona
	 */
	public function setCpernombre($cpernombre) {
		$this->cpernombre = $cpernombre;

		return $this;
	}

	/**
	 * Get cpernombre
	 *
	 * @return string
	 */
	public function getCpernombre() {
		return $this->cpernombre;
	}

	/**
	 * Set cperapellidos
	 *
	 * @param string $cperapellidos
	 * @return Persona
	 */
	public function setCperapellidos($cperapellidos) {
		$this->cperapellidos = $cperapellidos;

		return $this;
	}

	/**
	 * Get cperapellidos
	 *
	 * @return string
	 */
	public function getCperapellidos() {
		return $this->cperapellidos;
	}

	/**
	 * Set nperdni
	 *
	 * @param string $nperdni
	 * @return Persona
	 */
	public function setNperdni($nperdni) {
		$this->nperdni = $nperdni;

		return $this;
	}

	/**
	 * Get nperdni
	 *
	 * @return string
	 */
	public function getNperdni() {
		return $this->nperdni;
	}

	/**
	 * Set dpernacimiento
	 *
	 * @param \DateTime $dpernacimiento
	 * @return Persona
	 */
	public function setDpernacimiento($dpernacimiento) {
		$this->dpernacimiento = $dpernacimiento;

		return $this;
	}

	/**
	 * Get dpernacimiento
	 *
	 * @return \DateTime
	 */
	public function getDpernacimiento() {
		return $this->dpernacimiento;
	}

	/**
	 * Set npertipo
	 *
	 * @param integer $npertipo
	 * @return Persona
	 */
	public function setNpertipo($npertipo) {
		$this->npertipo = $npertipo;

		return $this;
	}

	/**
	 * Get npertipo
	 *
	 * @return integer
	 */
	public function getNpertipo() {
		return $this->npertipo;
	}

	/**
	 * Set nperestado
	 *
	 * @param integer $nperestado
	 * @return Persona
	 */
	public function setNperestado($nperestado) {
		$this->nperestado = $nperestado;

		return $this;
	}

	/**
	 * Get nperestado
	 *
	 * @return integer
	 */
	public function getNperestado() {
		return $this->nperestado;
	}

	/**
	 * Add perusuarios
	 *
	 * @param \Sistema\Entity\Perusuario $perusuarios
	 * @return Persona
	 */
	public function addPerusuario(\Sistema\Entity\Perusuario $perusuarios) {
		$this->perusuarios[] = $perusuarios;

		return $this;
	}

	/**
	 * Remove perusuarios
	 *
	 * @param \Sistema\Entity\Perusuario $perusuarios
	 */
	public function removePerusuario(\Sistema\Entity\Perusuario $perusuarios) {
		$this->perusuarios->removeElement($perusuarios);
	}

	/**
	 * Get perusuarios
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getPerusuarios() {
		return $this->perusuarios;
	}
}
