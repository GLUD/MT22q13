<?php
require_once('PTablaPaginacion.php');

/**
 * PTablaVertical -- Componente encargado de administrar el elemento "Tabla Vertical" (table).
 *
 * @author		Harold D. Duque C. - middlebrainco@gmail.com
 * @version		1.0
 * @since		2013.
 */
class PTablaVertical {
	/**
	 * Nombre de la Tabla.
	 *
	 * @var string
	 */
	var $nombre;

	/**
	 * Arreglo con las columnas de la tabla.
	 * Los elementos del arreglo son de tipo PTablaColumna.
	 *
	 * @var array
	 */
	var $columnas;

	/**
	 * El n�mero de filas que se muestran por p�gina en la tabla.
	 *
	 * @var int
	 */
	var $filasPagina;

	/**
	 * El nombre de la clave que tiene el valor de la p�gina actual.
	 *
	 * @var string
	 */
	var $paginaActualClaveNombre;

	/**
	 * Indica si los datos de la tabla se pueden ordenar de diferentes maneras.
	 *
	 * @var boolean
	 */
	var $hayOrdenamiento;

	/**
	 * El nombre de la clave que tiene el valor del criterio por el que se deben ordenar los datos.
	 *
	 * @var string
	 */
	var $ordenamientoClaveNombre;

	/**
	 * El nombre de la funci�n javascript que administra el ordenamiento en el cliente.
	 *
	 * @var string
	 */
	var $ordenamientoFuncionNombre;

	/**
	 * El mensaje que se debe mostrar cuando la tabla no tiene datos para mostrar.
	 *
	 * @var string
	 */
	var $sinDatosMensaje;

	/**
	 * Arreglo con los datos que se deben mostrar en la Tabla.
	 *
	 * @var array
	 */
	var $datos;


	/**
	 * Constructora. Inicializa los atributos.
	 *
	 * @param string $nombre Nombre de la tabla.
	 * @return PTablaVertical
	 */
	function PTablaVertical($nombre) {
		// Inicializacion de atributos.
		$this->nombre						= $nombre;

		$this->columnas						= array();
		$this->filasPagina					= 0;
		$this->paginaActualClaveNombre		= $this->nombre."Pag";
		$this->hayOrdenamiento				= false;
		$this->ordenamientoClaveNombre		= $this->nombre."Ord";
		$this->ordenamientoFuncionNombre	= $this->nombre."OrdFuncion";
		$this->sinDatosMensaje				= "";
		$this->datos						= array();
	}

	/**
	 * Modifica el n�mero de filas que se muestran por p�gina en la tabla.
	 *
	 * @param int $filasPagina El n�mero de filas que se muestran por p�gina en la tabla.
	 */
	function modFilasPagina($filasPagina) {
		$this->filasPagina = $filasPagina;
	}

	/**
	 * Modifica si los datos de la tabla se pueden ordenar de diferentes maneras.
	 *
	 * @param boolean $hayOrdenamiento Indica si los datos de la tabla se pueden ordenar de diferentes maneras.
	 */
	function modHayOrdenamiento($hayOrdenamiento) {
		$this->hayOrdenamiento = $hayOrdenamiento;
	}

	/**
	 * Modifica el mensaje que se debe mostrar cuando la tabla no tiene datos para mostrar.
	 *
	 * @param string $sinDatosMensaje El mensaje que se debe mostrar cuando la tabla no tiene datos para mostrar.
	 */
	function adiSinDatosMensaje($sinDatosMensaje) {
		$this->sinDatosMensaje = $sinDatosMensaje;
	}

	/**
	 * Adiciona una columna a la Tabla.
	 *
	 * @param string $nombre Nombre con el que se identifica la Columna.
	 * @param string $clave Nombre de la clave del campo que se quiere mostrar en la Columna.
	 * @param string $tagNombre Nombre del tag en el que se quiere mostrar la Columna.
	 * @param boolean $esOrdenable Indica si los datos de la tabla se pueden ordenar por la Columna.
	 */
	function adiColumna($nombre, $clave, $tagNombre, $esOrdenable = false) {
		$this->columnas[] = array("nombre" => $nombre, "clave" => $clave, "tagNombre" => $tagNombre, "esOrdenable" => $esOrdenable, "esFuncion" => false);
	}

	/**
	 * Adiciona una columna a la Tabla.
	 *
	 * @param string $nombre Nombre con el que se identifica la Columna.
	 * @param string $clave Nombre de la clave del campo que se quiere mostrar en la Columna.
	 * @param string $tagNombre Nombre del tag en el que se quiere mostrar la Columna.
	 * @param boolean $esOrdenable Indica si los datos de la tabla se pueden ordenar por la Columna.
	 */
	function adiColumnaFuncion($nombre, $funcion, $tagNombre, $esOrdenable = false) {
		$this->columnas[] = array("nombre" => $nombre, "clave" => $funcion, "tagNombre" => $tagNombre, "esOrdenable" => $esOrdenable, "esFuncion" => true);
	}

	/**
	 * Adiciona los datos que se deben mostrar en la Tabla.
	 *
	 * @param array $datos Arreglo con los datos que se deben mostrar en la Tabla.
	 */
	function adiDatos($datos) {
		$this->datos = $datos;
		$datos = $this->ordenarDatos();
	}

	/**
	 * Genera el codigo HTM de la tabla, su paginaci�n y su ordenamiento y los pinta.
	 * El nombre del tag para la Tabla debe ser igual al nombre del componente.
	 * El nombre del tag para la paginaci�n de la Tabla debe ser igual 
	 * al nombre del componente seguido por la palabra "Paginacion".
	 * El nombre del tag para el ordenamiento de la Tabla debe ser igual
	 * al nombre del componente seguido por la palabra "Ordenamiento".
	 * Ej.: para la tabla con el nombre "nombreTabla"
	 * ser�n: "nombreTablaPaginacion" y "nombreTablaOrdenamiento".
	 * 
	 * @param NokTemplate $nokTemplate Objeto de la libreria NokTemplate que permite usar templates.
	 * @param string $paginaSrc Ruta de la p�gina HTM que de la tabla que se quiere mostrar.
	 */
	function pintar($nokTemplate, $paginaSrc) {
		$paginacionHtm = $this->conPaginacionHtm();
		$paginacionTag = $this->nombre."Paginacion";
		$nokTemplate->asignar($paginacionTag, $paginacionHtm);

		$ordenamientoHtm = $this->conOrdenamientoHtm();
		$ordenamientoTag = $this->nombre."Ordenamiento";
		$nokTemplate->asignar($ordenamientoTag, $ordenamientoHtm);

		$filasPaginaNumHtm = $this->conFilasPaginaNumHtm();
		$filasPaginaNumTag = $this->nombre."Total";
		$nokTemplate->asignar($filasPaginaNumTag, $filasPaginaNumHtm);

		$nokTemplate->cargar("tablaPlt", $paginaSrc);

		$datosTam = count($this->datos);

		if ($datosTam > 0) {
			$paginaActual = (isset($_GET[$this->paginaActualClaveNombre]) ? $_GET[$this->paginaActualClaveNombre] : 1);
			$inicio = ($paginaActual - 1) * $this->filasPagina;

			if ($this->filasPagina > 0) {
				$fin = $inicio + $this->filasPagina;
				$fin = ($fin > $datosTam ? $datosTam : $fin);
			} else {
				$fin = $datosTam;
			}

			for ($i = $inicio; $i < $fin; $i++) {
				foreach ($this->columnas as $columna) {
					$esFuncion = $columna['esFuncion'];
					$columnaClave = $columna['clave'];

					if ($esFuncion) {
						$columnaHtml = call_user_func($columnaClave, $this->datos[$i]);
						$nokTemplate->asignar($tagNombre, $columnaHtml);
					} else {
						$tagNombre = $columna['tagNombre'];
						$nokTemplate->asignar($tagNombre, $this->datos[$i]->$columnaClave);
					}
				}

				$nokTemplate->expandir($this->nombre, "+tablaPlt");
			}
		} else {
			$nokTemplate->asignar($this->nombre, $this->sinDatosMensaje);
		}
	}

	/**
	 * Devuelve las filas de los datos que se van a mostrar en la p�gina actual.
	 *
	 * @return array
	 */
	function conFilasPagina() {
		$datosTam = count($this->datos);
		$paginaActual = (isset($_GET[$this->paginaActualClaveNombre]) ? $_GET[$this->paginaActualClaveNombre] : 1);
		$inicio = ($paginaActual - 1) * $this->filasPagina;

		if ($this->filasPagina > 0) {
			$filas = $this->filasPagina;
		} else {
			$filas = $datosTam;
		}

		$filas = array_slice($this->datos, $inicio, $filas);

		return $filas;
	}

	/**
	 * Devuelve el total de filas y el intervalo de filas de los datos que se van a mostrar en la p�gina actual.
	 *
	 * @return string
	 */
	function conFilasPaginaNumHtm() {
		$datosTam = count($this->datos);
		$paginaActual = (isset($_GET[$this->paginaActualClaveNombre]) ? $_GET[$this->paginaActualClaveNombre] : 1);
		$inicio = ($paginaActual - 1) * $this->filasPagina;

		if ($this->filasPagina > 0) {
			$fin = $inicio + $this->filasPagina;
			$fin = ($fin > $datosTam ? $datosTam : $fin);
		} else {
			$fin = $datosTam;
		}

		if ($datosTam > 0) {
			$inicio++;
			$filasPaginaNumHtm = "{$inicio} - {$fin} de {$datosTam}";
		} else {
			$filasPaginaNumHtm = "";
		}

		return $filasPaginaNumHtm;
	}

	/**
	 * Retorna el c�digo HTM de la paginaci�n.
	 *
	 * @return string
	 */
	function conPaginacionHtm() {
		$tablePaginacion = new PTablaPaginacion($this->nombre, $this->filasPagina);
		$paginacionHtm = $tablePaginacion->conHtm($this->datos);

		return $paginacionHtm;
	}

	/**
	 * Retorna los datos que se quieren mostrar en la tabla, ordenados por el criterio que se quiere.
	 */
	function ordenarDatos() {
		if ($this->hayOrdenamiento and isset($_GET[$this->ordenamientoClaveNombre])) {
			$ordenActual = $_GET[$this->ordenamientoClaveNombre];

			foreach ($this->datos as $clave => $fila) {
				$datosOrdenados[$clave] = $fila[$ordenActual];
			}

			array_multisort($datosOrdenados, SORT_ASC, $this->datos);
		}
	}

	/**
	 * Retorna le c�digo HTM para el ordenamiento de la Tabla.
	 *
	 * @return string
	 */
	function conOrdenamientoHtm() {
		$ordenamientoHtm = "";

		if ($this->hayOrdenamiento) {
			$ordenActual = (isset($_GET[$this->ordenamientoClaveNombre]) ? $_GET[$this->ordenamientoClaveNombre] : "");

			foreach ($_GET as $clave => $valor) {
				if ($clave != $this->ordenamientoClaveNombre and $clave != $this->paginaActualClaveNombre) {
					$getHtm .= $clave."=".$valor."&";
				}
			}

			$urlHtm = $_SERVER['PHP_SELF']."?".$getHtm.$this->ordenamientoClaveNombre."=";

			$ordenamientoHtm = "
				<script language=\"javascript\">
					function {$this->ordenamientoFuncionNombre}(combo) {
						if (combo.value != -1) {
							var url = \"{$urlHtm}\" + combo.value;
							window.location=url;
						}
					}
				</script>";

			$ordenamientoHtm .= "
				Orden : 
				<select name=\"{$this->ordenamientoClaveNombre}\" onChange=\"{$this->ordenamientoFuncionNombre}(this);\">
					<option value=-1";

			if ($ordenActual == "") {
				$ordenamientoHtm .= " selected";
			}

			$ordenamientoHtm .= ">-- Seleccione --</option>";

			foreach ($this->columnas as $columna) {
				$columnaNombre = $columna['nombre'];
				$columnaEsOrdenable = $columna["esOrdenable"];

				if ($columnaEsOrdenable) {
					$columnaClave = $columna["clave"];
					$ordenamientoHtm .= "\n\t\t\t\t\t<option value={$columnaClave}";

					if ($columnaClave == $ordenActual) {
						$ordenamientoHtm .= " selected";
					}

					$ordenamientoHtm .= ">{$columnaNombre}</option>";
				}
			}

			$ordenamientoHtm .= "</select>";

		}

		return $ordenamientoHtm;
	}
}
?> 