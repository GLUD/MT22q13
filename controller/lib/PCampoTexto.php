<?php
/**
 * PCampoTexto -- Componente encargado de administrar el elemento "Campo de texto" (input).
 *
 * @author		Harold D. Duque C. - middlebrainco@gmail.com
 * @version		1.0
 * @since		2013.
 */
class PCampoTexto {
	/**
	 * Nombre del Campo de texto (valor de la propiedad "name" del elemento "input" HTM).
	 *
	 * @var string
	 */
	var $nombre;

	/**
	 * Indica si se debe escribir datos obligatoriamente en el Campo de texto.
	 *
	 * @var boolean
	 */
	var $esObligatorio;

	/**
	 * Indica si el Campo de texto ya fue validado.
	 *
	 * @var boolean
	 */
	var $esValidado;

	/**
	 * El tipo de validaci�n que debe realizarse al Campo de texto.
	 *
	 * @var string
	 */
	var $validacionTipo;

	/**
	 * Indica el resultado de la validacion al Campo de texto.
	 *
	 * @var boolean
	 */
	var $validacionResultado;

	/**
	 * El mensaje de error que se debe mostrar cuando el resultado de la validaci�n del Campo de texto es incorrecto.
	 *
	 * @var string
	 */
	var $errorMensaje;

	/**
	 * Arreglo con el nombre y valor de las propiedades del Campo de texto.
	 * Cada elemento del arreglo tiene como nombre la propiedad HTM y el dato asignado tiene el valor
	 * de la propiedad (ej. $propiedades['name'] = "nombreCampoTexto", asignara el valor "nombreCampoTexto"
	 * a la propiedad "name" del elemento "input" HTM).
	 *
	 * @var array
	 */
	var $propiedades;

	/**
	 * Constructora. Inicializa los atributos.
	 *
	 * @param string $nombre Nombre del Campo de texto (valor de la propiedad "name" del elemento "input" HTM).
	 * @return PCampoTexto
	 */
	function PCampoTexto($nombre) {
		// Inicializacion de atributos.
		$this->nombre					= $nombre;

		$this->validacionTipo			= "texto";
		$this->esObligatorio			= true;
		$this->esValidado				= false;
		$this->validacionResultado		= true;
		$this->errorMensaje				= "*";

		$this->propiedades['id']		= $nombre;
		$this->propiedades['name']		= $nombre;
		$this->propiedades['type']		= "text"; // Por defecto la propiedad "type" del elemento "input" HTM es "text".
	}

	/**
	 * Modifica el mensaje de error que se debe mostrar cuando el resultado de la validaci�n del Campo de texto es incorrecto.
	 *
	 * @param string $errorMensaje Texto del mensaje de error que se debe mostrar cuando el resultado de la validaci�n del Campo de texto es incorrecto.
	 */
	function modErrorMensaje($errorMensaje) {
		$this->errorMensaje = $errorMensaje;
	}

	/**
	 * Modifica si se debe escribir datos obligatoriamente en el Campo de texto.
	 *
	 * @param boolean $esObligatorio Indica si se debe escribir datos obligatoriamente en el Campo de texto.
	 */
	function modEsObligatorio($esObligatorio) {
		if (is_bool($esObligatorio)) {
			$this->esObligatorio = $esObligatorio;
		}
	}

	/**
	 * Modifica el tipo de validaci�n que debe realizarse al Campo de texto.
	 *
	 * @param string $validacionTipo Tipo de validaci�n que debe realizarse al Campo de texto
	 * ("texto": Texto, "entero": Entero, "enteroPositivoSinCero": Entero mayor a 0,
	 * "enteroPositivoConCero: Entero igual o mayor a 0, "decimal": Decimal,
	 * "decimalPositivoSinCero": Decimal mayor a 0, "decimalPositivoConCero: Decimal igual o mayor a 0,
	 * "correo": Correo electr�nico, "clave": Clave.
	 */
	function modValidacionTipo($validacionTipo) {
		$this->validacionTipo = $validacionTipo;
	}

	/**
	 * Adiciona una propiedad y su valor al Campo de texto.
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
	 * Retorna el nombre del Campo de texto.
	 *
	 * @return string
	 */
	function conNombre() {
		return $this->nombre;
	}

	/**
	 * Retorna el mensaje de error que se debe mostrar cuando el resultado de la validaci�n del Campo de texto es incorrecto.
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
	 * Genera el codigo para el "input" HTM y lo pinta. En caso de existir un error en la validaci�n,
	 * pinta igualmente el mensaje de error.
	 * El nombre del tag para el Campo de texto debe ser igual al nombre del componente.
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
	 * Retorna el codigo para el "input" HTM.
	 *
	 * @return string
	 */
	function conHtm() {
		$campoTextoHtm = "<input";

		// Genera el codigo de las propiedades y sus valores para el "input" HTM.
		foreach ($this->propiedades as $nombre => $valor) {
			$campoTextoHtm .= " {$nombre}=\"{$valor}\"";
		}

		$campoTextoHtm .= ">";

		return $campoTextoHtm;
	}

	/**
	 * Retorna el codigo Javascript para realizar la validaci�n del Campo de texto en el cliente.
	 *
	 * @return string
	 */
	function conValidacionJs() {
		$esObligatorioStr = ($this->esObligatorio ? "true" : "false");
		$validacionJs = "validarCampoTexto(\"{$this->nombre}\", \"{$this->nombre}Error\", \"{$this->validacionTipo}\", {$esObligatorioStr}, validacion)";

		return $validacionJs;
	}

	/**
	 * Devuelve el resultado de la validaci�n del Campo de texto.
	 *
	 * @return boolean, string
	 */
	function validar() {
		$this->propiedades['value'] = @$_POST[$this->nombre];
		$this->validacionResultado = @$_POST[$this->nombre];
		$this->esValidado = true;

		return $this->validacionResultado;
	}

	/**
	 * Ejecuta la validaci�n correcta para el Campo de Texto dependiendo del tipo de validaci�n.
	 *
	 * @param string $texto Valor digitado en el Campo de Texto.
	 * @return boolean
	 */
	function validarValor($texto) {
		switch ($this->validacionTipo) {
			case "entero": {
				return $this->ValidarEntero($texto);
			} case "decimal": {
				return $this->ValidarDecimal($texto);
			} case "enteroPositivoConCero": {
				return $this->ValidarEnteroPositivoConCero($texto);
			} case "decimalPositivoConCero": {
				return $this->ValidarDecimalPositivoConCero($texto);
			} case "enteroPositivoSinCero": {
				return $this->ValidarEnteroPositivoSinCero($texto);
			} case "decimalPositivoSinCero": {
				return $this->ValidarDecimalPositivoSinCero($texto);
			} case "correo": {
				return $this->validarCorreo($texto);
			} case "clave": {
				return $this->validarClave($texto);
			} default: {
				return true;
			}
		}
	}

	function validarClave($clave) {
		if (strlen($clave) < 6) {
			return false;
		} else {
			for ($i = 0; $i < strlen($clave); $i++) {
				$caracter = $clave[$i];
				if ($caracter < '0' or ($caracter > '9' and $caracter < 'A') or ($caracter > 'Z' and $caracter < 'a') or $caracter > 'z') {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Eval�a si el texto digitado en el Campo de Texto es un n�mero entero.
	 *
	 * @param string $numero Texto digitado en el Campo de Texto.
	 * @return boolean
	 */
	function validarEntero($numero) {
		return (
			is_numeric($numero) and
			round($numero) == $numero
			? true : false);
	}

	/**
	 * Eval�a si el texto digitado en el Campo de Texto es un n�mero decimal.
	 *
	 * @param string $numero Texto digitado en el Campo de Texto.
	 * @return boolean
	 */
	function validarDecimal($numero) {
		return is_numeric($numero);
	}

	/**
	 * Eval�a si el texto digitado en el Campo de Texto es un n�mero entero mayor o igual a cero.
	 *
	 * @param string $numero Texto digitado en el Campo de Texto.
	 * @return boolean
	 */
	function validarEnteroPositivoConCero($numero) {
		return (
			is_numeric($numero) and
			round($numero) == $numero and
			$numero >= 0
			? true : false);
	}

	/**
	 * Eval�a si el texto digitado en el Campo de Texto es un n�mero decimal mayor o igual a cero.
	 *
	 * @param string $numero Texto digitado en el Campo de Texto.
	 * @return boolean
	 */
	function validarDecimalPositivoConCero($numero) {
		return (
			is_numeric($numero) and
			$numero >= 0
			? true : false);
	}

	/**
	 * Eval�a si el texto digitado en el Campo de Texto es un n�mero entero mayor a cero.
	 *
	 * @param string $numero Texto digitado en el Campo de Texto.
	 * @return boolean
	 */
	function validarEnteroPositivoSinCero($numero) {
		return (
			is_numeric($numero) and
			round($numero) == $numero and
			$numero > 0
			? true : false);
	}

	/**
	 * Eval�a si el texto digitado en el Campo de Texto es un n�mero decimal mayor a cero.
	 *
	 * @param string $numero Texto digitado en el Campo de Texto.
	 * @return boolean
	 */
	function validarDecimalPositivoSinCero($numero) {
		return (
			is_numeric($numero) and
			$numero > 0
			? true : false);
	}

	/**
	 * Eval�a si el texto digitado en el Campo de Texto es una cuenta de correo valida.
	 *
	 * @param string $correo Texto digitado en el Campo de Texto.
	 * @return boolean
	 */
	function validarCorreo($correo) {
		$correo = trim($correo);

		if (strpos($correo, " ") != false) {
			return false;
		}

		$correoSplit = explode("@", $correo);
		if (count($correoSplit) != 2 || $correoSplit[0] == "" || $correoSplit[1] == "") {
			return false;
		}

		$correoSplit = explode(".", $correoSplit[1]);
		foreach ($correoSplit as $id => $texto) {
			if ($texto == "") {
				return false;
			}
		}

		return true;
	}
}
