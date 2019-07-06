<?php

namespace Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Url
 *
 * @ORM\Table(name="url")
 * @ORM\Entity(repositoryClass="Sistema\Repository\UrlRepository")
 */
class Url {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nurlcodigo", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="url_nurlcodigo_seq", allocationSize=1, initialValue=1)
	 */
	private $nurlcodigo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="curl", type="string", length=500, nullable=false)
	 */
	private $curl;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="durlfechareg", type="date", nullable=false)
	 */
	private $durlfechareg;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="durlfechapublico", type="date", nullable=true)
	 */
	private $durlfechapublico;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nurlestado", type="integer", nullable=false)
	 */
	private $nurlestado;

	/**
	 * @ORM\ManyToOne(targetEntity="Sistema\Entity\Menu")
	 * @ORM\JoinColumn(name="nmencodigo", referencedColumnName="nmencodigo")
	 */
	protected $menu;

	/**
	 * Get nurlcodigo
	 *
	 * @return integer
	 */
	public function getNurlcodigo() {
		return $this->nurlcodigo;
	}

	/**
	 * Set curl
	 *
	 * @param string $curl
	 * @return Url
	 */
	public function setCurl($curl) {
		$this->curl = $curl;

		return $this;
	}

	/**
	 * Get curl
	 *
	 * @return string
	 */
	public function getCurl() {
		return $this->curl;
	}

	/**
	 * Set durlfechareg
	 *
	 * @param \DateTime $durlfechareg
	 * @return Url
	 */
	public function setDurlfechareg($durlfechareg) {
		$this->durlfechareg = $durlfechareg;

		return $this;
	}

	/**
	 * Get durlfechareg
	 *
	 * @return \DateTime
	 */
	public function getDurlfechareg() {
		return $this->durlfechareg;
	}

	/**
	 * Set durlfechapublico
	 *
	 * @param \DateTime $durlfechapublico
	 * @return Url
	 */
	public function setDurlfechapublico($durlfechapublico) {
		$this->durlfechapublico = $durlfechapublico;

		return $this;
	}

	/**
	 * Get durlfechapublico
	 *
	 * @return \DateTime
	 */
	public function getDurlfechapublico() {
		return $this->durlfechapublico;
	}

	/**
	 * Set nurlestado
	 *
	 * @param integer $nurlestado
	 * @return Url
	 */
	public function setNurlestado($nurlestado) {
		$this->nurlestado = $nurlestado;

		return $this;
	}

	/**
	 * Get nurlestado
	 *
	 * @return integer
	 */
	public function getNurlestado() {
		return $this->nurlestado;
	}

	/**
	 * Set menu
	 *
	 * @param \Sistema\Entity\Menu $menu
	 * @return Url
	 */
	public function setMenu(\Sistema\Entity\Menu $menu = null) {
		$this->menu = $menu;

		return $this;
	}

	/**
	 * Get menu
	 *
	 * @return \Sistema\Entity\Menu
	 */
	public function getMenu() {
		return $this->menu;
	}
}
