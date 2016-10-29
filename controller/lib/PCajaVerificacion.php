<?php
/**
 * PCajaVerificacion -- Componente encargado de administrar el elemento "Caja de verificaci�n" (checkbox).
 *
 * @author		Harold D. Duque C. - middlebrainco@gmail.com
 * @version		1.0
 * @since		2013.
 */
class PCajaVerificacion {
	/**
	 * Nombre de la Caja de verificaci�n (valor de la propiedad "name" del elemento "checkbox" HTM).
	 *
	 * @var string
	 */
	var $nombre;

	/**
	 * Indica si se debe seleccionar obligatoriamente al menos un item en la Caja de verificaci�n.
	 *
	 * @var boolean
	 */
	var $esObligatorio;

	/**
	 * Indica si la Caja de verificaci�n ya fue validada.
	 *
	 * @var boolean
	 */
	var $esValidado;

	/**
	 * Indica el resultado de la validacion de la Caja de verificaci�n.
	 *
	 * @var boolean
	 */
	var $validacionResultado;

	/**
	 * El mensaje de error que se debe mostrar cuando el resultado de la validaci�n de la Caja de verificaci�n es incorrecto.
	 *
	 * @var string
	 */
	var $errorMensaje;

	/**
	 * Arreglo con el nombre y valor de las propiedades de la Caja de verificaci�n.
	 * Cada elemento del arreglo tiene como nombre la propiedad HTM y el dato asignado tiene el valor
	 * de la propiedad (ej. $propiedades['name'] = "nombreCajaVerificaci�n", asignara el valor "nombreCajaVerificaci�n"
	 * a la propiedad "name" del elemento "checkbox" HTM).
	 *
	 * @var array
	 */
	var $propiedades;

	/**
	 * Los items seleccionados en la Caja de verificaci�n.
	 *
	 * @var array
	 */
	var $itemsSeleccionados;

	/**
	 * La plantilla con el HTM que se quiere mostrar cada item de la Caja de verificaci�n.
	 * El tag {CAJAVERIFICACION} se reemplazar� por la Caja de verificaci�n del item.
	 * El tag {TEXTO} se reemplazar� por el texto del item.
	 * Los tags deben estar en may�sculas.
	 * Ej.: con la plantilla "{CAJAVERIFICACION} {TEXTO}<br>", para el item con value="1", texto="nombreItem"
	 * aparecer� "<input type=checkbox" name="nombreCajaVerificacion" value="1"> nombreItem<br>".
	 *
	 * @var string
	 */
	var $plantilla;
	
	/**
	 * Indica si la Caja de Verificaci�n debe tener un item para seleccionar todos los items.
	 *
	 * @var boolean
	 */
	var $haySeleccionarTodos;
	
	/**
	 * Arreglo con el nombre y valor de las propiedades del item que selecciona todos los items de la Caja de verificaci�n.
	 * Cada elemento del arreglo tiene como nombre la propiedad HTM y el dato asignado tiene el valor
	 * de la propiedad (ej. $propiedades['class'] = "classItem", asignara el valor "classItem"
	 * a la propiedad "class" del item que selecciona todos los items de la Caja de verificaci�n).
	 *
	 * @var array
	 */
	var $seleccionarTodosPropiedades;
	
	/**
	 * Objeto que tiene el m�todo que administra los items.
	 *
	 * @var object
	 */
	var $controlador;

	/**
	 * Nombre del m�todo que administra los items.
	 * Esta funci�n debe devolver un objeto de tipo PCajaVerificacionItem o false cuando no existan m�s registros
	 *
	 * @var string
	 */
	var $funcionNombre;

	/**
	 * Constructora. Inicializa los atributos.
	 *
	 * @param string $nombre Nombre de la Caja de verificaci�n (valor de la propiedad "name" del elemento "checkbox" HTM).
	 * @return PCajaVerificacion
	 */
	function PCajaVerificacion($nombre) {
		// Inicializacion de atributos.
		$this->nombre						= $nombre;

		$this->esObligatorio				= true;
		$this->esValidado					= false;
		$this->validacionResultado			= true;
		$this->errorMensaje					= "*";
		$this->itemsSeleccionados			= array();
		$this->plantilla					= "{CAJAVERIFICACION} {TEXTO} ";
		$this->haySeleccionarTodos			= false;
		$this->seleccionarTodosPropiedades	= array();
		
		$this->seleccionarTodosPropiedades["onclick"]		= "{$this->nombre}MarcarTodos();";
		
		$this->controlador				= null;
		$this->funcionNombre			= null;

		$this->propiedades['id']			= $nombre;
		$this->propiedades['name']			= $nombre;
	}

	/**
	 * Modifica la plantilla con el HTM que se quiere mostrar cada item de la Caja de verificaci�n.
	 * El tag {CAJAVERIFICACION} se reemplazar� por la Caja de verificaci�n del item.
	 * El tag {TEXTO} se reemplazar� por el texto del item.
	 * Los tags deben estar en may�sculas.
	 * Ej.: con la plantilla "{CAJAVERIFICACION} {TEXTO}<br>", para el item con value="1", texto="nombreItem"
	 * aparecer� "<input type=checkbox" name="nombreCajaVerificacion" value="1"> nombreItem<br>".
	 *
	 * @param string $plantilla La plantilla con el HTM que se quiere mostrar cada item de la Caja de verificaci�n.
	 */
	function modPlantilla($plantilla) {
		$this->plantilla = $plantilla;
	}

	/**
	 * Modifica los items seleccionados en la Caja de verificaci�n.
	 *
	 * @param array $itemsSeleccionados Los items seleccionados en la Caja de verificaci�n.
	 */
	function modItemsSeleccionados($itemsSeleccionados) {
		if ($this->esValidado == false) {
			$this->itemsSeleccionados = $itemsSeleccionados;
		}
	}

	/**
	 * Modifica el mensaje de error que se debe mostrar cuando el resultado de la validaci�n de la Caja de verificaci�n es incorrecto.
	 *
	 * @param string $errorMensaje Texto del mensaje de error que se debe mostrar cuando el resultado de la validaci�n de la Caja de verificaci�n es incorrecto.
	 */
	function modErrorMensaje($errorMensaje) {
		$this->errorMensaje = $errorMensaje;
	}

	/**
	 * Modifica si se debe seleccionar obligatoriamente al menos un item en la Caja de verificaci�n.
	 *
	 * @param boolean $esObligatorio Indica si se debe seleccionar obligatoriamente al menos un item en la Caja de verificaci�n.
	 */
	function modEsObligatorio($esObligatorio) {
		if (is_bool($esObligatorio)) {
			$this->esObligatorio = $esObligatorio;
		}
	}
	
	/**
	 * Modifica si la Caja de Verificaci�n debe tener un item para seleccionar todos los items.
	 *
	 * @param boolean $haySeleccionarTodos Indica si la Caja de Verificaci�n debe tener un item para seleccionar todos los items.
	 */
	function modHaySeleccionarTodos($haySeleccionarTodos) {
		if (is_bool($haySeleccionarTodos)) {
			$this->haySeleccionarTodos = $haySeleccionarTodos;
		}
	}
	
	/**
	 * Modifica el objeto y el nombre del m�todo que administra los items.
	 * Este m�todo debe devolver un objeto de tipo PCajaVerificacionItem o false cuando no existan m�s registros.
	 *
	 * @param object $controlador Objeto que tiene el m�todo que administra los items.
	 * @param string $funcionNombre Nombre de la funci�n que administra los items.
	 */
	function modControlador($controlador, $funcionNombre) {
		$this->controlador = $controlador;
		$this->funcionNombre = $funcionNombre;
	}

	/**
	 * Adiciona una propiedad y su valor a la Caja de verificaci�n.
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
	 * Adiciona una propiedad y su valor item que selecciona todos los items de la Caja de verificaci�n.
	 *
	 * @param string $nombre Nombre de la propiedad.
	 * @param string $valor Valor de la propiedad.
	 */
	function adiSeleccionarTodosPropiedad($nombre, $valor) {
		if (strtolower($nombre) != "name" and strtolower($nombre) != "onclick") {
			$this->seleccionarTodosPropiedades[$nombre] = $valor;
		} elseif (strtolower($nombre) == "onclick") {
			$this->seleccionarTodosPropiedades["onclick"] = $valor.";".$this->seleccionarTodosPropiedades["onclick"];
		}
	}

	/**
	 * Retorna el nombre de la Caja de verificaci�n.
	 *
	 * @return string
	 */
	function conNombre() {
		return $this->nombre;
	}

	/**
	 * Retorna el mensaje de error que se debe mostrar cuando el resultado de la validaci�n de la Caja de verificaci�n es incorrecto.
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
	 * Genera el codigo para el "checkbox" HTM y lo pinta. En caso de existir un error en la validaci�n,
	 * pinta igualmente el mensaje de error.
	 * El nombre del tag para la Caja de verificaci�n debe ser igual al nombre del componente.
	 * El nombre del tag para el mensaje de error debe ser igual al nombre del componente,
	 * seguido por la cadena de texto "Error" (ej: para el componente "nombreComponente",
	 * entonces ser�: "nombreComponenteError").
	 *
	 * @param NokTemplate $nokTemplate Objeto de la libreria NokTemplate que permite usar templates.
	 */
	function pintar(&$nokTemplate) {
		$cajaVerificacionHtm = $this->conHtm();

		$nokTemplate->asignar($this->nombre, $cajaVerificacionHtm);
		
		if ($this->haySeleccionarTodos) {
			$cajaSeleccionarTodosHtm = $this->conCajaSeleccionarTodos();
			$cajaSeleccionarTodosTag = $this->nombre."Todos";
			$nokTemplate->asignar($cajaSeleccionarTodosTag, $cajaSeleccionarTodosHtm);
		}

		$errorMensaje_ = $this->conErrorMensaje();
		$errorMensajeTag = $this->nombre."Error";
		$nokTemplate->asignar($errorMensajeTag, $errorMensaje_);
	}

	/**
	 * Retorna el codigo para el "checkbox" HTM.
	 *
	 * @return string
	 */
	function conHtm() {
		$itemCajaVerificacionHtm = "";
		
		$itemNum = 1;
		while (($item = $this->controlador->{$this->funcionNombre}($itemNum++)) !== false) {
			/* @var $item PCajaVerificacionItem */
			$value = trim($item->value);
			$texto = trim($item->texto);

			$cajaVerificacionHtm = "<input type=\"checkbox\" value=\"{$value}\"";

			// Genera el codigo de las propiedades y sus valores para el "checkbox" HTM.
			foreach ($this->propiedades as $nombre => $valor) {
				$cajaVerificacionHtm .= " {$nombre}=\"{$valor}";
				if (strtolower($nombre) == "name") {
					$cajaVerificacionHtm .= "[]";
				}
				$cajaVerificacionHtm .= "\"";
			}

			foreach ($this->itemsSeleccionados as $itemSeleccionado) {
				if ($value == $itemSeleccionado) {
					$cajaVerificacionHtm .= " checked";
				}
			}

			$cajaVerificacionHtm .= ">";

			$plantillaTemp = str_replace("{TEXTO}", $texto, $this->plantilla);
			$itemCajaVerificacionHtm .= str_replace("{CAJAVERIFICACION}", $cajaVerificacionHtm, $plantillaTemp);
		}

		return $itemCajaVerificacionHtm;
	}

	/**
	 * Retorna el codigo Javascript para realizar la validaci�n de la Caja de verificaci�n en el cliente.
	 *
	 * @return string
	 */
	function conValidacionJs() {
		$esObligatorioStr = ($this->esObligatorio ? "true" : "false");
		$validacionJs = "validarCajaVerificacion(\"{$this->nombre}[]\", \"{$this->nombre}Error\", {$esObligatorioStr}, validacion)";

		return $validacionJs;
	}
	
	/**
	 * Retorna el codigo HTM para el item que selecciona todos los items de la Caja de Verificaci�n.
	 *
	 * @return string
	 */
	function conCajaSeleccionarTodos() {
		$cajaSeleccionarTodosHtm = "
			<script language=\"javascript\">
				function {$this->nombre}MarcarTodos() {
					cajaTodos = document.getElementById(\"{$this->nombre}Todos\");
					cajasArr = document.getElementsByName(\"{$this->nombre}[]\");
					cajaTodosChk = cajaTodos.checked;
					
					for (var i = 0; i < cajasArr.length; i++) {
						cajasArr[i].checked = cajaTodosChk;
					}
				}
			</script>
			<input type=\"checkbox\" name=\"{$this->nombre}Todos\"";
		
		foreach ($this->seleccionarTodosPropiedades as $nombre => $valor) {
			$cajaSeleccionarTodosHtm .= " {$nombre}=\"{$valor}\"";
		}
		
		$cajaSeleccionarTodosHtm .= ">";
		
		return $cajaSeleccionarTodosHtm;
	}

	/**
	 * Devuelve el resultado de la validaci�n de la Caja de verificaci�n.
	 *
	 * @return boolean, string
	 */
	function validar() {
		if (isset($_POST[$this->nombre])) {
			$this->itemsSeleccionados		= $_POST[$this->nombre];
			$this->validacionResultado		= $_POST[$this->nombre];
		} else if ($this->esObligatorio) {
			$this->itemsSeleccionados	= array();
			$this->validacionResultado	= false;
		} else {
			$this->itemsSeleccionados	= array();
			$this->validacionResultado	= array();
		}

		$this->esValidado = true;

		return $this->validacionResultado;
	}
}

class PCajaVerificacionItem {
	public $value;
	public $texto;

	public function __construct() {
		$this->value = "";
		$this->texto = "";
	}
}
?>