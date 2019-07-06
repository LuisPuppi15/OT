<?php
namespace Portal\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FechaHelper extends AbstractHelper {
	function __invoke($tipo) {
		if ($tipo == 'full') {
			$dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado");
			$meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
			$fecha = $dias[date('w')] . " " . date('d') . " de " . $meses[date('n') - 1] . " del " . date('Y');
		}

		return $fecha;
	}
}