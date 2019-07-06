<?php

namespace Sistema\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Html
 *
 * @ORM\Table(name="html")
 * @ORM\Entity(repositoryClass="Sistema\Repository\HtmlRepository")
 */
class Html {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nhtmlcodigo", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @ORM\SequenceGenerator(sequenceName="html_nhtmlcodigo_seq", allocationSize=1, initialValue=1)
	 */
	private $nhtmlcodigo;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="chtmlcontenido", type="text", nullable=false)
	 */
	private $chtmlcontenido;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dhtmlfechareg", type="date", nullable=false)
	 */
	private $dhtmlfechareg;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="dhtmlfechapublico", type="date", nullable=true)
	 */
	private $dhtmlfechapublico;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="nhtmlestado", type="integer", nullable=false)
	 */
	private $nhtmlestado;

	/**
	 * @ORM\ManyToOne(targetEntity="Sistema\Entity\Menu")
	 * @ORM\JoinColumn(name="nmencodigo", referencedColumnName="nmencodigo")
	 */
	protected $menu;

	/**
	 * Get nhtmlcodigo
	 *
	 * @return integer
	 */
	public function getNhtmlcodigo() {
		return $this->nhtmlcodigo;
	}

	/**
	 * Set chtmlcontenido
	 *
	 * @param string $chtmlcontenido
	 * @return Html
	 */
	public function setChtmlcontenido($chtmlcontenido) {
		$this->chtmlcontenido = $chtmlcontenido;

		return $this;
	}

	/**
	 * Get chtmlcontenido
	 *
	 * @return string
	 */
	public function getChtmlcontenido() {
		return $this->chtmlcontenido;
	}

	/**
	 * Set dhtmlfechareg
	 *
	 * @param \DateTime $dhtmlfechareg
	 * @return Html
	 */
	public function setDhtmlfechareg($dhtmlfechareg) {
		$this->dhtmlfechareg = $dhtmlfechareg;

		return $this;
	}

	/**
	 * Get dhtmlfechareg
	 *
	 * @return \DateTime
	 */
	public function getDhtmlfechareg() {
		return $this->dhtmlfechareg;
	}

	/**
	 * Set dhtmlfechapublico
	 *
	 * @param \DateTime $dhtmlfechapublico
	 * @return Html
	 */
	public function setDhtmlfechapublico($dhtmlfechapublico) {
		$this->dhtmlfechapublico = $dhtmlfechapublico;

		return $this;
	}

	/**
	 * Get dhtmlfechapublico
	 *
	 * @return \DateTime
	 */
	public function getDhtmlfechapublico() {
		return $this->dhtmlfechapublico;
	}

	/**
	 * Set nhtmlestado
	 *
	 * @param integer $nhtmlestado
	 * @return Html
	 */
	public function setNhtmlestado($nhtmlestado) {
		$this->nhtmlestado = $nhtmlestado;

		return $this;
	}

	/**
	 * Get nhtmlestado
	 *
	 * @return integer
	 */
	public function getNhtmlestado() {
		return $this->nhtmlestado;
	}

	/**
	 * Set menu
	 *
	 * @param \Sistema\Entity\Menu $menu
	 * @return Html
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
