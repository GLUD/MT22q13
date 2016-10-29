<?php
/**
 * PCalendario -- Componente encargado de administrar el elemento "Calendario" (input).
 *
 * @author		Harold D. Duque C. - middlebrainco@gmail.com
 * @version		1.0
 * @since		2013.
 */
class PCalendario {
	/**
	 * Nombre del Calendario (valor de la propiedad "name" del elemento "input" HTM).
	 *
	 * @var string
	 */
	var $nombre;

	/**
	 * Indica si se debe seleccionar obligatoriamente una fecha en el Calendario.
	 *
	 * @var boolean
	 */
	var $esObligatorio;

	/**
	 * Indica si el Calendario ya fue validado.
	 *
	 * @var boolean
	 */
	var $esValidado;

	/**
	 * Indica el resultado de la validacion del Calendario.
	 *
	 * @var boolean
	 */
	var $validacionResultado;

	/**
	 * El mensaje de error que se debe mostrar cuando el resultado de la validaci�n del Calendario es incorrecto.
	 *
	 * @var string
	 */
	var $errorMensaje;

	/**
	 * Arreglo con el nombre y valor de las propiedades del Calendario.
	 * Cada elemento del arreglo tiene como nombre la propiedad HTM y el dato asignado tiene el valor
	 * de la propiedad (ej. $propiedades['name'] = "nombreCalendario", asignar� el valor "nombreCalendario"
	 * a la propiedad "name" del elemento "input" HTM).
	 *
	 * @var array
	 */
	var $propiedades;

	/**
	 * Constructora. Inicializa los atributos.
	 *
	 * @param string $nombre Nombre del Calendario (valor de la propiedad "name" del elemento "input" HTM).
	 * @return PCalendario
	 */
	function PCalendario($nombre) {
		// Inicializacion de atributos.
		$this->nombre					= $nombre;

		$this->esObligatorio			= true;
		$this->esValidado				= false;
		$this->validacionResultado		= true;
		$this->errorMensaje				= "*";

		$this->propiedades['id']		= $nombre;
		$this->propiedades['name']		= $nombre;
		$this->propiedades['readonly']	= "true";
	}

	/**
	 * Modifica el mensaje de error que se debe mostrar cuando el resultado de la validaci�n del Calendario es incorrecto.
	 *
	 * @param string $errorMensaje Texto del mensaje de error que se debe mostrar cuando el resultado de la validaci�n del Calendario es incorrecto.
	 */
	function modErrorMensaje($errorMensaje) {
		$this->errorMensaje = $errorMensaje;
	}

	/**
	 * Modifica si se debe seleccionar obligatoriamente una fecha en el Calendario.
	 *
	 * @param boolean $esObligatorio Indica si se debe seleccionar obligatoriamente una fecha en el Calendario.
	 */
	function modEsObligatorio($esObligatorio) {
		if (is_bool($esObligatorio)) {
			$this->esObligatorio = $esObligatorio;
		}
	}

	/**
	 * Adiciona una propiedad y su valor al Calendario.
	 *
	 * @param string $nombre Nombre de la propiedad.
	 * @param string $valor Valor de la propiedad.
	 */
	function adiPropiedad($nombre, $valor) {
		if (strtolower($nombre) != "value" || $this->esValidado == false) {
			$this->propiedades[$nombre] = $valor;
		}
	}

	/**
	 * Retorna el nombre del Calendario.
	 *
	 * @return string
	 */
	function conNombre() {
		return $this->nombre;
	}

	/**
	 * Retorna el mensaje de error que se debe mostrar cuando el resultado de la validaci�n del Calendario es incorrecto.
	 *
	 * @return string
	 */
	function conErrorMensaje() {
		$errorMensajeHtm = "<span id=\"{$this->nombre}Error\" style=\"display: ";

		if ($this->validacionResultado === false) {
			$errorMensajeHtm .= "block";
		} else {
			$errorMensajeHtm .= "none";
		}

		$errorMensajeHtm .= "\">{$this->errorMensaje}</span>";

		return $errorMensajeHtm;
	}

	/**
	 * Genera el codigo para el "input" HTM y lo pinta. En caso de existir un error en la validaci�n,
	 * pinta igualmente el mensaje de error.
	 * El nombre del tag para el Calendario debe ser igual al nombre del componente.
	 * El nombre del tag para el mensaje de error debe ser igual al nombre del componente,
	 * seguido por la cadena de texto "Error" (ej: para el componente "nombreComponente",
	 * entonces ser�: "nombreComponenteError").
	 *
	 * @param NokTemplate $nokTemplate Objeto de la libreria NokTemplate que permite usar templates.
	 */
	function pintar(&$nokTemplate, $formaNombre) {
		$calendarioHtm = $this->conHtm($formaNombre);

		$nokTemplate->asignar($this->nombre, $calendarioHtm);

		$errorMensaje_ = $this->conErrorMensaje();
		$errorMensajeTag = $this->nombre."Error";
		$nokTemplate->asignar($errorMensajeTag, $errorMensaje_);
	}
	
	/**
	 * Retorna el codigo para el "input" HTM.
	 *
	 * @return string
	 */
	function conHtm($formaNombre) {
		$calendarioHtm = "
			<script language=\"javascript\" src=\"../../js/dhtmlgoodies_calendar.js?random=20060118\" type=\"text/javascript\"></script>
			<link href=\"../../css/dhtmlgoodies_calendar.css\" rel=\"stylesheet\" type=\"text/css\">";
		
		$calendarioHtm .= "<input";

		// Genera el codigo de las propiedades y sus valores para el "input" HTM.
		foreach ($this->propiedades as $nombre => $valor) {
			$calendarioHtm .= " {$nombre}=\"{$valor}\"";
		}

		$calendarioHtm .= "> <img src=\"../../img/calendario/cal.gif\" alt=\"Seleccione una fecha\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" onclick=\"displayCalendar(document.{$formaNombre}.{$this->nombre},'yyyy-mm-dd',this)\">";

		return $calendarioHtm;
	}
	
	/**
	 * Retorna el codigo Javascript para realizar la validaci�n del Calendario en el cliente.
	 *
	 * @return string
	 */
	function conValidacionJs() {
		$esObligatorioStr = ($this->esObligatorio ? "true" : "false");
		$validacionJs = "validarCalendario(\"{$this->nombre}\", \"{$this->nombre}Error\", {$esObligatorioStr}, validacion)";

		return $validacionJs;
	}

	/**
	 * Devuelve el resultado de la validaci�n del Calendario.
	 *
	 * @return boolean, string
	 */
	function validar() {
		$this->propiedades['value'] = $_POST[$this->nombre];
		
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