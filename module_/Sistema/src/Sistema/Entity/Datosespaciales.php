<?php

namespace Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Datosespaciales
 *
 * @ORM\Table(name="datosespaciales")
 * @ORM\Entity(repositoryClass="Sistema\Repository\DatosespacialesRepository")
 */
class Datosespaciales {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="ndatespcodigo", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="datosespaciales_ndatespcodigo_seq", allocationSize=1, initialValue=1)
	 */
	private $ndatespcodigo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cdatespnombre", type="string", length=100, nullable=false)
	 */
	private $cdatespnombre;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cdatespcontenido", type="string", nullable=true)
	 */
	private $cdatespcontenido;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="ndatespjerarquia", type="integer", nullable=false)
	 */
	private $ndatespjerarquia;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="ndatespestado", type="integer", nullable=false)
	 */
	private $ndatespestado;

	/**
	 * Get ndatespcodigo
	 *
	 * @return integer
	 */
	public function getNdatespcodigo() {
		return $this->ndatespcodigo;
	}

	/**
	 * Set cdatespnombre
	 *
	 * @param string $cdatespnombre
	 * @return Datosespaciales
	 */
	public function setCdatespnombre($cdatespnombre) {
		$this->cdatespnombre = $cdatespnombre;

		return $this;
	}

	/**
	 * Get cdatespnombre
	 *
	 * @return string
	 */
	public function getCdatespnombre() {
		return $this->cdatespnombre;
	}

	/**
	 * Set cdatespcontenido
	 *
	 * @param string $cdatespcontenido
	 * @return Datosespaciales
	 */
	public function setCdatespcontenido($cdatespcontenido) {
		$this->cdatespcontenido = $cdatespcontenido;

		return $this;
	}

	/**
	 * Get cdatespcontenido
	 *
	 * @return string
	 */
	public function getCdatespcontenido() {
		return $this->cdatespcontenido;
	}

	/**
	 * Set ndatespjerarquia
	 *
	 * @param integer $ndatespjerarquia
	 * @return Datosespaciales
	 */
	public function setNdatespjerarquia($ndatespjerarquia) {
		$this->ndatespjerarquia = $ndatespjerarquia;

		return $this;
	}

	/**
	 * Get ndatespjerarquia
	 *
	 * @return integer
	 */
	public function getNdatespjerarquia() {
		return $this->ndatespjerarquia;
	}

	/**
	 * Set ndatespestado
	 *
	 * @param integer $ndatespestado
	 * @return Datosespaciales
	 */
	public function setNdatespestado($ndatespestado) {
		$this->ndatespestado = $ndatespestado;

		return $this;
	}

	/**
	 * Get ndatespestado
	 *
	 * @return integer
	 */
	public function getNdatespestado() {
		return $this->ndatespestado;
	}
}
