<?php
/**
 * PAreaTexto -- Componente encargado de administrar el elemento "�rea de texto" (textarea).
 *
 * @author		Harold D. Duque C. - middlebrainco@gmail.com
 * @version		1.0
 * @since		2013.
 */
class PAreaTexto {
	/**
	 * Nombre del �rea de texto (valor de la propiedad "name" del elemento "textarea" HTM).
	 *
	 * @var string
	 */
	var $nombre;

	/**
	 * Indica si se debe escribir datos obligatoriamente en el �rea de texto.
	 *
	 * @var boolean
	 */
	var $esObligatorio;

	/**
	 * Indica si el �rea de texto ya fue validada.
	 *
	 * @var boolean
	 */
	var $esValidado;

	/**
	 * Indica el resultado de la validacion al �rea de texto.
	 *
	 * @var boolean
	 */
	var $validacionResultado;

	/**
	 * El mensaje de error que se debe mostrar cuando el resultado de la validaci�n del �rea de texto es incorrecto.
	 *
	 * @var string
	 */
	var $errorMensaje;

	/**
	 * Arreglo con el nombre y valor de las propiedades del �rea de texto.
	 * Cada elemento del arreglo tiene como nombre la propiedad HTM y el dato asignado tiene el valor
	 * de la propiedad (ej. $propiedades['name'] = "nombreAreaTexto", asignara el valor "nombreAreaTexto"
	 * a la propiedad "name" del elemento "textarea" HTM).
	 *
	 * @var array
	 */
	var $propiedades;

	/**
	 * El texto que contiene el �rea de Texto.
	 *
	 * @var string
	 */
	var $texto;

	/**
	 * Constructora. Inicializa los atributos.
	 *
	 * @param string $nombre Nombre del �rea de texto (valor de la propiedad "name" del elemento "textarea" HTM).
	 * @return PAreaTexto
	 */
	function PAreaTexto($nombre) {
		// Inicializacion de atributos.
		$this->nombre					= $nombre;

		$this->esObligatorio			= true;
		$this->esValidado				= false;
		$this->validacionResultado		= true;
		$this->errorMensaje				= "*";
		$this->texto					= "";

		$this->propiedades['id']		= $nombre;
		$this->propiedades['name']		= $nombre;
	}

	/**
	 * Modifica el texto que contiene el �rea de Texto.
	 *
	 * @param string $texto El texto que contiene el �rea de Texto.
	 */
	function modTexto($texto) {
		if ($this->esValidado == false) {
			$this->texto = $texto;
		}
	}

	/**
	 * Modifica el mensaje de error que se debe mostrar cuando el resultado de la validaci�n del �rea de texto es incorrecto.
	 *
	 * @param string $errorMensaje Texto del mensaje de error que se debe mostrar cuando el resultado de la validaci�n del �rea de texto es incorrecto.
	 */
	function modErrorMensaje($errorMensaje) {
		$this->errorMensaje = $errorMensaje;
	}

	/**
	 * Modifica si se debe escribir datos obligatoriamente en el �rea de texto.
	 *
	 * @param boolean $esObligatorio Indica si se debe escribir datos obligatoriamente en el �rea de texto.
	 */
	function modEsObligatorio($esObligatorio) {
		if (is_bool($esObligatorio)) {
			$this->esObligatorio = $esObligatorio;
		}
	}

	/**
	 * Adiciona una propiedad y su valor al �rea de texto.
	 *
	 * @param string $nombre Nombre de la propiedad.
	 * @param string $valor Valor de la propiedad.
	 */
	function adiPropiedad($nombre, $valor) {
		$this->propiedades[$nombre] = $valor;
	}

	/**
	 * Retorna el nombre del �rea de texto.
	 *
	 * @return string
	 */
	function conNombre() {
		return $this->nombre;
	}

	/**
	 * Retorna el mensaje de error que se debe mostrar cuando el resultado de la validaci�n del �rea de texto es incorrecto.
	 *
	 * @return string
	 */
	function conErrorMensaje() {
		$errorMensajeHtm = "<div class=\"alert alert-warning\" id=\"{$this->nombre}Error\" "
                . "title=\"{$this->errorMensaje}\" style=\"display: ";

		if ($this->validacionResultado === false) {
			$errorMensajeHtm .= "block";
		} else {
			$errorMensajeHtm .= "none";
		}
                $errorMensajeHtm .= "\"><span class=\"glyphicon glyphicon-warning-sign\"></span> {$this->errorMensaje}</div>";

		return $errorMensajeHtm;
	}

	/**
	 * Genera el codigo para el "textarea" HTM y lo pinta. En caso de existir un error en la validaci�n,
	 * pinta igualmente el mensaje de error.
	 * El nombre del tag para el �rea de texto debe ser igual al nombre del componente.
	 * El nombre del tag para el mensaje de error debe ser igual al nombre del componente,
	 * seguido por la cadena de texto "Error" (ej: para el componente "nombreComponente",
	 * entonces ser�: "nombreComponenteError").
	 *
	 * @param NokTemplate $nokTemplate Objeto de la libreria NokTemplate que permite usar templates.
	 */
	function pintar(&$nokTemplate) {
		$campoTextoHtm = $this->conHtm();

		$nokTemplate->asignar($this->nombre, $campoTextoHtm);

		$errorMensaje_ = $this->conErrorMensaje();
		$errorMensajeTag = $this->nombre."Error";
		$nokTemplate->asignar($errorMensajeTag, $errorMensaje_);
	}

	/**
	 * Retorna el codigo para el "textarea" HTM.
	 *
	 * @return string
	 */
	function conHtm() {
		$campoTextoHtm = "<textarea";

		// Genera el codigo de las propiedades y sus valores para el "textarea" HTM.
		foreach ($this->propiedades as $nombre => $valor) {
			$campoTextoHtm .= " {$nombre}=\"{$valor}\"";
		}

		$campoTextoHtm .= ">{$this->texto}</textarea>";

		return $campoTextoHtm;
	}

	/**
	 * Retorna el codigo Javascript para realizar la validaci�n del �rea de texto en el cliente.
	 *
	 * @return string
	 */
	function conValidacionJs() {
		$esObligatorioStr = ($this->esObligatorio ? "true" : "false");
		$validacionJs = "validarAreaTexto(\"{$this->nombre}\", \"{$this->nombre}Error\", {$esObligatorioStr}, validacion)";

		return $validacionJs;
	}

	/**
	 * Devuelve el resultado de la validaci�n del �rea de texto.
	 *
	 * @return boolean, string
	 */
	function validar() {
		$this->texto = $_POST[$this->nombre];

		if ($this->esObligatorio && $_POST[$this->nombre] == "") {
			$this->validacionResultado = false;
		} else {
			$this->validacionResultado = $_POST[$this->nombre];
		}

		$this->esValidado = true;

		return $this->validacionResultado;
	}
}
?>
