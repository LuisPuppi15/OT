<?php

namespace Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contacto
 *
 * @ORM\Table(name="contacto")
 * @ORM\Entity(repositoryClass="Sistema\Repository\ContactoRepository")
 */
class Contacto {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="idcontacto", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="contacto_idcontacto_seq", allocationSize=1, initialValue=1)
	 */
	private $idcontacto;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="titulo", type="string", length=250, nullable=false)
	 */
	private $titulo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="direccion", type="string", length=500, nullable=true)
	 */
	private $direccion;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="telefono", type="string", length=30, nullable=true)
	 */
	private $telefono;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="anexo", type="string", length=50, nullable=true)
	 */
	private $anexo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="email", type="string", length=100, nullable=true)
	 */
	private $email;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="estado", type="integer", nullable=false)
	 */
	private $estado;

	/**
	 * Get idcontacto
	 *
	 * @return integer
	 */
	public function getIdcontacto() {
		return $this->idcontacto;
	}

	/**
	 * Set titulo
	 *
	 * @param string $titulo
	 * @return Contacto
	 */
	public function setTitulo($titulo) {
		$this->titulo = $titulo;

		return $this;
	}

	/**
	 * Get titulo
	 *
	 * @return string
	 */
	public function getTitulo() {
		return $this->titulo;
	}

	/**
	 * Set direccion
	 *
	 * @param string $direccion
	 * @return Contacto
	 */
	public function setDireccion($direccion) {
		$this->direccion = $direccion;

		return $this;
	}

	/**
	 * Get direccion
	 *
	 * @return string
	 */
	public function getDireccion() {
		return $this->direccion;
	}

	/**
	 * Set telefono
	 *
	 * @param string $telefono
	 * @return Contacto
	 */
	public function setTelefono($telefono) {
		$this->telefono = $telefono;

		return $this;
	}

	/**
	 * Get telefono
	 *
	 * @return string
	 */
	public function getTelefono() {
		return $this->telefono;
	}

	/**
	 * Set anexo
	 *
	 * @param string $anexo
	 * @return Contacto
	 */
	public function setAnexo($anexo) {
		$this->anexo = $anexo;

		return $this;
	}

	/**
	 * Get anexo
	 *
	 * @return string
	 */
	public function getAnexo() {
		return $this->anexo;
	}

	/**
	 * Set email
	 *
	 * @param string $email
	 * @return Contacto
	 */
	public function setEmail($email) {
		$this->email = $email;

		return $this;
	}

	/**
	 * Get email
	 *
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Set estado
	 *
	 * @param integer $estado
	 * @return Contacto
	 */
	public function setEstado($estado) {
		$this->estado = $estado;

		return $this;
	}

	/**
	 * Get estado
	 *
	 * @return integer
	 */
	public function getEstado() {
		return $this->estado;
	}
}
