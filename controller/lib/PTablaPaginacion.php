<?php
/**
 * PTablaPaginacion -- Componente encargado de administrar el elemento "Paginaci�n" de una tabla.
 *
 * @author		Harold D. Duque C. - middlebrainco@gmail.com
 * @version		1.0
 * @since		2013.
 */
class PTablaPaginacion {
	/**
	 * El n�mero de filas que se muestran por p�gina en la tabla.
	 *
	 * @var int
	 */
	var $filasPagina;

	/**
	 * El n�mero de la p�gina que se debe mostrar.
	 *
	 * @var int
	 */
	var $paginaActual;

	/**
	 * El nombre de la clave que tiene el valor de la p�gina actual.
	 *
	 * @var string
	 */
	var $paginaActualClaveNombre;

	/**
	 * El nombre de la funci�n javascript que administra la paginaci�n en el cliente.
	 *
	 * @var string
	 */
	var $paginaActualFuncionNombre;

	/**
	 * Url de la p�gina en la que est� ubicada la tabla.
	 * Con base en esta url, se crean los links de la paginaci�n.
	 *
	 * @var string
	 */
	var $urlHtm;

	/**
	 * Constructora. Inicializa los atributos.
	 *
	 * @param string $nombre Nombre de la Columna.
	 * @param int $filasPagina El n�mero de filas que se muestran por p�gina en la tabla.
	 * @return PTablaPaginacion
	 */
	function PTablaPaginacion($nombre, $filasPagina) {
		// Inicializacion de atributos.
		$this->nombre						= $nombre;
		$this->filasPagina					= $filasPagina;

		$this->paginaActual					= 1;
		$this->paginaActualClaveNombre		= $this->nombre."Pag";
		$this->paginaActualFuncionNombre	= $this->nombre."PagFuncion";
		$this->urlHtm						= $this->conUrlHtm();
	}

	/**
	 * Consulta el url de la p�gina en la que est� ubicada la tabla.
	 * Con base en esta url, se crean los links de la paginaci�n.
	 *
	 * @return string
	 */
	function conUrlHtm() {
		$getHtm = "";
		
		foreach ($_GET as $clave => $valor) {
			if ($clave != $this->paginaActualClaveNombre) {
				$getHtm .= $clave."=".$valor."&";
			}
		}

		$urlHtm = $_SERVER['PHP_SELF']."?".$getHtm.$this->paginaActualClaveNombre."=";

		return $urlHtm;
	}

	/**
	 * Retorna el HTM para la paginacion cuando se utiliza una Lista.
	 *
	 * @param int $paginasNum El n�mero de p�ginas que ocupan los datos a mostrar.
	 * @return string
	 */
	function conListaPaginas($paginasNum) {
		$listaPaginasHtm = "
				<script language=\"javascript\">
					function {$this->paginaActualFuncionNombre}(combo) {
						var url = \"{$this->urlHtm}\" + combo.value;
						window.location = url;
					}
				</script>";

		$listaPaginasHtm .= "
				P&aacute;gina
				<select name=\"{$this->paginaActualClaveNombre}\" onChange=\"{$this->paginaActualFuncionNombre}(this);\">";

		if ($paginasNum > 0) {
			for ($i = 1; $i <= $paginasNum; $i++) {
				$listaPaginasHtm .= "\n\t\t\t\t\t<option value=\"{$i}\"";
				if ($i == $this->paginaActual) {
					$listaPaginasHtm .= " selected";
				}
				$listaPaginasHtm .= ">{$i}</option>";
			}
		} else {
			$listaPaginasHtm .= "\n\t\t\t\t\t<option value=\"1\">1</option>";
		}

		$listaPaginasHtm .= "</select>";

		return $listaPaginasHtm;
	}

	/**
	 * Retorna el HTM para la paginaci�n cuando se utiliza un link a la pagina anterior.
	 *
	 * @return string
	 */
	function conAnteriorHtm() {
		if ($this->paginaActual > 1) {
			$anteriorNum = $this->paginaActual - 1;
			$anteriorHtm = "<a href=\"{$this->urlHtm}{$anteriorNum}\" id=\"anteriorLink\">Anterior</a>";
		} else {
			$anteriorHtm = "Anterior";
		}

		return $anteriorHtm;
	}

	/**
	 * Retorna el HTM para la paginaci�n cuando se utiliza un link a la pagina siguiente.
	 *
	 * @return string
	 */
	function conSiguienteHtm($paginasNum) {
		if ($this->paginaActual < $paginasNum) {
			$siguienteNum = $this->paginaActual + 1;
			$siguienteHtm = "<a href=\"{$this->urlHtm}{$siguienteNum}\" id=\"siguienteLink\">Siguiente</a>";
		} else {
			$siguienteHtm = "Siguiente";
		}

		return $siguienteHtm;
	}

	/**
	 * Retorna el c�digo HTM de la paginaci�n.
	 *
	 * @param array $datos Arreglo con los datos que se quieren mostrar en la Tabla.
	 * @return string
	 */
	function conHtm($filasTotal) {
		if ($this->filasPagina > 0) {
			$this->paginaActual = (isset($_GET[$this->paginaActualClaveNombre]) ? $_GET[$this->paginaActualClaveNombre] : 1);

			$paginasNum = ceil($filasTotal / $this->filasPagina);
			
			if ($paginasNum == 0) {
				$paginasNum++;
			}

			$paginacionHtm = $this->conListaPaginas($paginasNum);
			$paginacionHtm .= " de {$paginasNum} \n\t\t\t\t\t[ ";
			$paginacionHtm .= $this->conAnteriorHtm();
			$paginacionHtm .= " | \n\t\t\t\t\t";
			$paginacionHtm .= $this->conSiguienteHtm($paginasNum);
			$paginacionHtm .= " ]";
		} else {
			$paginacionHtm = "";
		}

		return $paginacionHtm;
	}
}
?>