<?php

namespace Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Urlportal
 *
 * @ORM\Table(name="urlportal")
 * @ORM\Entity(repositoryClass="Sistema\Repository\UrlportalRepository")
 */
class Urlportal {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nurlporcodigo", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="urlportal_nurlporcodigo_seq", allocationSize=1, initialValue=1)
	 */
	private $nurlporcodigo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="curlpornombre", type="string", length=200, nullable=false)
	 */
	private $curlpornombre;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="curlporurl", type="string", length=500, nullable=false)
	 */
	private $curlporurl;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nurlporestado", type="integer", nullable=false)
	 */
	private $nurlporestado;

	/**
	 * Get nurlporcodigo
	 *
	 * @return integer
	 */
	public function getNurlporcodigo() {
		return $this->nurlporcodigo;
	}

	/**
	 * Set curlpornombre
	 *
	 * @param string $curlpornombre
	 * @return Urlportal
	 */
	public function setCurlpornombre($curlpornombre) {
		$this->curlpornombre = $curlpornombre;

		return $this;
	}

	/**
	 * Get curlpornombre
	 *
	 * @return string
	 */
	public function getCurlpornombre() {
		return $this->curlpornombre;
	}

	/**
	 * Set curlporurl
	 *
	 * @param string $curlporurl
	 * @return Urlportal
	 */
	public function setCurlporurl($curlporurl) {
		$this->curlporurl = $curlporurl;

		return $this;
	}

	/**
	 * Get curlporurl
	 *
	 * @return string
	 */
	public function getCurlporurl() {
		return $this->curlporurl;
	}

	/**
	 * Set nurlporestado
	 *
	 * @param integer $nurlporestado
	 * @return Urlportal
	 */
	public function setNurlporestado($nurlporestado) {
		$this->nurlporestado = $nurlporestado;

		return $this;
	}

	/**
	 * Get nurlporestado
	 *
	 * @return integer
	 */
	public function getNurlporestado() {
		return $this->nurlporestado;
	}
}
