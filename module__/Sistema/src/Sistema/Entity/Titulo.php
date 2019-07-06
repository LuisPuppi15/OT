<?php

namespace Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Titulo
 *
 * @ORM\Table(name="titulo")
 * @ORM\Entity(repositoryClass="Sistema\Repository\TituloRepository")
 */
class Titulo {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="idtitulo", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="titulo_idtitulo_seq", allocationSize=1, initialValue=1)
	 */
	private $idtitulo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="titulo", type="string", length=255, nullable=false)
	 */
	private $titulo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="descripcion", type="string", length=500, nullable=true)
	 */
	private $descripcion;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="referencia", type="string", length=45, nullable=true)
	 */
	private $referencia;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="estado", type="integer", nullable=false)
	 */
	private $estado;

	/**
	 * Get idtitulo
	 *
	 * @return integer
	 */
	public function getIdtitulo() {
		return $this->idtitulo;
	}

	/**
	 * Set titulo
	 *
	 * @param string $titulo
	 * @return Titulo
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
	 * Set descripcion
	 *
	 * @param string $descripcion
	 * @return Titulo
	 */
	public function setDescripcion($descripcion) {
		$this->descripcion = $descripcion;

		return $this;
	}

	/**
	 * Get descripcion
	 *
	 * @return string
	 */
	public function getDescripcion() {
		return $this->descripcion;
	}

	/**
	 * Set referencia
	 *
	 * @param string $referencia
	 * @return Titulo
	 */
	public function setReferencia($referencia) {
		$this->referencia = $referencia;

		return $this;
	}

	/**
	 * Get referencia
	 *
	 * @return string
	 */
	public function getReferencia() {
		return $this->referencia;
	}

	/**
	 * Set estado
	 *
	 * @param integer $estado
	 * @return Titulo
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
