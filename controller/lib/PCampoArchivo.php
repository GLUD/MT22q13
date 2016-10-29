<?php
/**
 * PCampoArchivo -- Componente encargado de administrar el elemento "Campo de archivo" (input).
 *
 * @author		Harold D. Duque C. - middlebrainco@gmail.com
 * @version		1.0
 * @since		2013.
 */
class PCampoArchivo {
	/**
	 * Nombre del Campo de archivo (valor de la propiedad "name" del elemento "input" HTM).
	 *
	 * @var string
	 */
	var $nombre;

	/**
	 * Indica si se debe escribir datos obligatoriamente en el Campo de archivo.
	 *
	 * @var boolean
	 */
	var $esObligatorio;

	/**
	 * Indica si el Campo de archivo ya fue validado.
	 *
	 * @var boolean
	 */
	var $esValidado;

	/**
	 * Indica el resultado de la validacion al Campo de archivo.
	 *
	 * @var boolean
	 */
	var $validacionResultado;

	/**
	 * El mensaje de error que se debe mostrar cuando el resultado de la validaci�n del Campo de archivo es incorrecto.
	 *
	 * @var string
	 */
	var $errorMensaje;

	/**
	 * Arreglo con el nombre y valor de las propiedades del Campo de archivo.
	 * Cada elemento del arreglo tiene como nombre la propiedad HTM y el dato asignado tiene el valor
	 * de la propiedad (ej. $propiedades['name'] = "nombreCampoArchivo", asignara el valor "nombreCampoArchivo"
	 * a la propiedad "name" del elemento "input" HTM).
	 *
	 * @var array
	 */
	var $propiedades;

	/**
	 * El tama�o maximo que puede tener el archivo.
	 *
	 * @var int
	 */
	var $archivoTam;

	/**
	 * Nombre del archivo que contiene el Campo de archivo.
	 *
	 * @var string
	 */
	var $archivoNombre;

	/**
	 * Constructora. Inicializa los atributos.
	 *
	 * @param string $nombre Nombre del Campo de archivo (valor de la propiedad "name" del elemento "input" HTM).
	 * @return PCampoArchivo
	 */
	function PCampoArchivo($nombre) {
		// Inicializacion de atributos.
		$this->nombre					= $nombre;

		$this->validacionTipo			= "texto";
		$this->esObligatorio			= true;
		$this->esValidado				= false;
		$this->validacionResultado		= true;
		$this->errorMensaje				= "*";
		$this->archivoTam				= 0;
		$this->archivoNombre			= "";

		$this->propiedades['id']		= $nombre;
		$this->propiedades['name']		= $nombre;
		$this->propiedades['type']		= "file";
	}

	/**
	 * Modifica el tama�o maximo que puede tener el archivo.
	 *
	 * @param int $archivoTam El tama�o maximo que puede tener el archivo.
	 */
	function modArchivoTam($archivoTam) {
		$this->archivoTam = $archivoTam;
	}

	/**
	 * Modifica el nombre del archivo que contiene el Campo de archivo.
	 *
	 * @param string $archivoNombre Nombre del archivo que contiene el Campo de archivo.
	 */
	function modArchivoNombre($archivoNombre) {
		if ($this->esValidado == false) {
			$this->archivoNombre = $archivoNombre;
		}
	}

	/**
	 * Modifica el mensaje de error que se debe mostrar cuando el resultado de la validaci�n del Campo de archivo es incorrecto.
	 *
	 * @param string $errorMensaje Texto del mensaje de error que se debe mostrar cuando el resultado de la validaci�n del Campo de archivo es incorrecto.
	 */
	function modErrorMensaje($errorMensaje) {
		$this->errorMensaje = $errorMensaje;
	}

	/**
	 * Modifica si se debe escribir datos obligatoriamente en el Campo de archivo.
	 *
	 * @param boolean $esObligatorio Indica si se debe escribir datos obligatoriamente en el Campo de archivo.
	 */
	function modEsObligatorio($esObligatorio) {
		if (is_bool($esObligatorio)) {
			$this->esObligatorio = $esObligatorio;
		}
	}

	/**
	 * Adiciona una propiedad y su valor al Campo de archivo.
	 *
	 * @param string $nombre Nombre de la propiedad.
	 * @param string $valor Valor de la propiedad.
	 */
	function adiPropiedad($nombre, $valor) {
		if (strtolower($nombre) != "type" and
		(strtolower($nombre) != "value" || $this->esValidado == false)) {
			$this->propiedades[$nombre] = $valor;
		}
	}

	/**
	 * Retorna el nombre del Campo de archivo.
	 *
	 * @return string
	 */
	function conNombre() {
		return $this->nombre;
	}

	/**
	 * Retorna el mensaje de error que se debe mostrar cuando el resultado de la validaci�n del Campo de archivo es incorrecto.
	 *
	 * @return string
	 */
	function conErrorMensaje() {
		$errorMensajeHtm = "<div class=\"alertaForm tooltip\" id=\"{$this->nombre}Error\" "
                . "title=\"{$this->errorMensaje}\" style=\"display: ";
		
		if ($this->validacionResultado === false) {
			$errorMensajeHtm .= "block";
		} else {
			$errorMensajeHtm .= "none";
		}
                $errorMensajeHtm .= "\"></div>";
                
		return $errorMensajeHtm;
	}

	/**
	 * Genera el codigo para el "input" HTM y lo pinta. En caso de existir un error en la validaci�n,
	 * pinta igualmente el mensaje de error.
	 * El nombre del tag para el Campo de archivo debe ser igual al nombre del componente.
	 * El nombre del tag para el mensaje de error debe ser igual al nombre del componente,
	 * seguido por la cadena de texto "Error" (ej: para el componente "nombreComponente",
	 * entonces ser�: "nombreComponenteError").
	 *
	 * @param NokTemplate $nokTemplate Objeto de la libreria NokTemplate que permite usar templates.
	 */
	function pintar(&$nokTemplate) {
		$campoArchivoHtm = $this->conHtm();

		$nokTemplate->asignar($this->nombre, $campoArchivoHtm);

		$errorMensaje_ = $this->conErrorMensaje();
		$errorMensajeTag = $this->nombre."Error";
		$nokTemplate->asignar($errorMensajeTag, $errorMensaje_);
	}

	/**
	 * Retorna el codigo para el "input" HTM.
	 *
	 * @return string
	 */
	function conHtm() {
		$campoArchivoHtm = "<input";

		// Genera el codigo de las propiedades y sus valores para el "input" HTM.
		foreach ($this->propiedades as $nombre => $valor) {
			$campoArchivoHtm .= " {$nombre}=\"{$valor}\"";
		}

		$campoArchivoHtm .= ">";

		return $campoArchivoHtm;
	}

	/**
	 * Retorna el codigo Javascript para realizar la validaci�n del Campo de archivo en el cliente.
	 *
	 * @return string
	 */
	function conValidacionJs() {
		$esObligatorioStr = ($this->esObligatorio ? "true" : "false");
		$validacionJs = "validarCampoArchivo(\"{$this->nombre}\", \"{$this->nombre}Error\", {$esObligatorioStr}, validacion)";

		return $validacionJs;
	}

	/**
	 * Devuelve el resultado de la validaci�n del Campo de archivo.
	 *
	 * @return boolean, string
	 */
	function validar() {
		if (@$_FILES[$this->nombre]['name'] != "") {
			if ($this->archivoTam != 0 and $_FILES[$this->nombre]['size'] > $this->archivoTam) {
				$this->validacionResultado = false;
			} else {
				$this->validacionResultado								= $_FILES[$this->nombre];
			}
		} else if ($this->esObligatorio) {
			$this->validacionResultado = false;
		} else {
			$this->validacionResultado = null;
		}


		$this->esValidado = true;

		return $this->validacionResultado;
	}
}
?>