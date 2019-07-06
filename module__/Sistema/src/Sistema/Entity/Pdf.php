<?php

namespace Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pdf
 *
 * @ORM\Table(name="pdf")
 * @ORM\Entity(repositoryClass="Sistema\Repository\PdfRepository")
 */
class Pdf {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="npdfcodigo", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="pdf_npdfcodigo_seq", allocationSize=1, initialValue=1)
	 */
	private $npdfcodigo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cpdf", type="string", length=500, nullable=false)
	 */
	private $cpdf;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dpdffechareg", type="date", nullable=false)
	 */
	private $dpdffechareg;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dpdffechapublico", type="date", nullable=true)
	 */
	private $dpdffechapublico;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="npdfestado", type="integer", nullable=false)
	 */
	private $npdfestado;

	/**
	 * @ORM\ManyToOne(targetEntity="Sistema\Entity\Menu")
	 * @ORM\JoinColumn(name="nmencodigo", referencedColumnName="nmencodigo")
	 */
	protected $menu;

	/**
	 * Get npdfcodigo
	 *
	 * @return integer
	 */
	public function getNpdfcodigo() {
		return $this->npdfcodigo;
	}

	/**
	 * Set cpdf
	 *
	 * @param string $cpdf
	 * @return Pdf
	 */
	public function setCpdf($cpdf) {
		$this->cpdf = $cpdf;

		return $this;
	}

	/**
	 * Get cpdf
	 *
	 * @return string
	 */
	public function getCpdf() {
		return $this->cpdf;
	}

	/**
	 * Set dpdffechareg
	 *
	 * @param \DateTime $dpdffechareg
	 * @return Pdf
	 */
	public function setDpdffechareg($dpdffechareg) {
		$this->dpdffechareg = $dpdffechareg;

		return $this;
	}

	/**
	 * Get dpdffechareg
	 *
	 * @return \DateTime
	 */
	public function getDpdffechareg() {
		return $this->dpdffechareg;
	}

	/**
	 * Set dpdffechapublico
	 *
	 * @param \DateTime $dpdffechapublico
	 * @return Pdf
	 */
	public function setDpdffechapublico($dpdffechapublico) {
		$this->dpdffechapublico = $dpdffechapublico;

		return $this;
	}

	/**
	 * Get dpdffechapublico
	 *
	 * @return \DateTime
	 */
	public function getDpdffechapublico() {
		return $this->dpdffechapublico;
	}

	/**
	 * Set npdfestado
	 *
	 * @param integer $npdfestado
	 * @return Pdf
	 */
	public function setNpdfestado($npdfestado) {
		$this->npdfestado = $npdfestado;

		return $this;
	}

	/**
	 * Get npdfestado
	 *
	 * @return integer
	 */
	public function getNpdfestado() {
		return $this->npdfestado;
	}

	/**
	 * Set menu
	 *
	 * @param \Sistema\Entity\Menu $menu
	 * @return Pdf
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
