<?php

namespace Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Infoterritorial
 *
 * @ORM\Table(name="infoterritorial")
 * @ORM\Entity(repositoryClass="Sistema\Repository\InfoterritorialRepository")
 */
class Infoterritorial {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="ninftercodigo", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="infoterritorial_ninftercodigo_seq", allocationSize=1, initialValue=1)
	 */
	private $ninftercodigo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cinfternombre", type="string", length=100, nullable=false)
	 */
	private $cinfternombre;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cinfterurl", type="string", length=500, nullable=false)
	 */
	private $cinfterurl;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="ninfterestado", type="integer", nullable=false)
	 */
	private $ninfterestado;

	/**
	 * Get ninftercodigo
	 *
	 * @return integer
	 */
	public function getNinftercodigo() {
		return $this->ninftercodigo;
	}

	/**
	 * Set cinfternombre
	 *
	 * @param string $cinfternombre
	 * @return Infoterritorial
	 */
	public function setCinfternombre($cinfternombre) {
		$this->cinfternombre = $cinfternombre;

		return $this;
	}

	/**
	 * Get cinfternombre
	 *
	 * @return string
	 */
	public function getCinfternombre() {
		return $this->cinfternombre;
	}

	/**
	 * Set cinfterurl
	 *
	 * @param string $cinfterurl
	 * @return Infoterritorial
	 */
	public function setCinfterurl($cinfterurl) {
		$this->cinfterurl = $cinfterurl;

		return $this;
	}

	/**
	 * Get cinfterurl
	 *
	 * @return string
	 */
	public function getCinfterurl() {
		return $this->cinfterurl;
	}

	/**
	 * Set ninfterestado
	 *
	 * @param integer $ninfterestado
	 * @return Infoterritorial
	 */
	public function setNinfterestado($ninfterestado) {
		$this->ninfterestado = $ninfterestado;

		return $this;
	}

	/**
	 * Get ninfterestado
	 *
	 * @return integer
	 */
	public function getNinfterestado() {
		return $this->ninfterestado;
	}
}
