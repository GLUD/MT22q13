<?php
/**
 * PListaMultiple -- Componente encargado de administrar el elemento "Lista M�ltiple" (select).
 *
 * @author		Harold D. Duque C. - middlebrainco@gmail.com
 * @version		1.0
 * @since		2013.
 */
class PListaMultiple {
	/**
	 * Nombre de la Lista M�ltiple (valor de la propiedad "name" del elemento "select" HTM).
	 *
	 * @var string
	 */
	var $nombre;

	/**
	 * Indica si se debe seleccionar obligatoriamente un item en la Lista M�ltiple.
	 *
	 * @var boolean
	 */
	var $esObligatorio;

	/**
	 * Indica si la Lista M�ltiple ya fue validada.
	 *
	 * @var boolean
	 */
	var $esValidado;

	/**
	 * Indica el resultado de la validacion de la Lista M�ltiple.
	 *
	 * @var boolean
	 */
	var $validacionResultado;

	/**
	 * El mensaje de error que se debe mostrar cuando el resultado de la validaci�n de la Lista M�ltiple es incorrecto.
	 *
	 * @var string
	 */
	var $errorMensaje;

	/**
	 * Arreglo con el nombre y valor de las propiedades de la Lista M�ltiple.
	 * Cada elemento del arreglo tiene como nombre la propiedad HTM y el dato asignado tiene el valor
	 * de la propiedad (ej. $propiedades['name'] = "nombreListaMultiple", asignara el valor "nombreListaMultiple"
	 * a la propiedad "name" del elemento "select" HTM).
	 *
	 * @var array
	 */
	var $propiedades;

	/**
	 * Los items seleccionados en la Lista M�ltiple.
	 *
	 * @var array
	 */
	var $itemsSeleccionados;

	/**
	 * El tama�o m�ximo del texto para los items de la Lista.
	 * Si el texto de un item supera el t�ma�o m�ximo,
	 * se cortar� la cadena de texto y se a�adir�n tres (3) puntos al final (...).
	 * Ej.: Si el tama�o m�ximo de la cadena es tres (3) y un item es la palabra "Lista",
	 * el item aparecer� de la siguiente manera: "Lis...".
	 *
	 * @var int
	 */
	var $textoTam;

	/**
	 * Objeto que tiene el m�todo que administra los items.
	 *
	 * @var object
	 */
	var $controlador;

	/**
	 * Nombre del m�todo que administra los items.
	 * Esta funci�n debe devolver un objeto de tipo PListaMultipleItem o false cuando no existan m�s registros
	 *
	 * @var string
	 */
	var $funcionNombre;

	/**
	 * Constructora. Inicializa los atributos.
	 *
	 * @param string $nombre Nombre de la Lista M�ltiple (valor de la propiedad "name" del elemento "select" HTM).
	 * @return PListaMultiple
	 */
	function PListaMultiple($nombre) {
		// Inicializacion de atributos.
		$this->nombre					= $nombre;

		$this->esObligatorio			= true;
		$this->esValidado				= false;
		$this->validacionResultado		= true;
		$this->errorMensaje				= "*";
		$this->itemsSeleccionados		= array();
		$this->textoTam					= 0;
		$this->items					= array("items" => array(), "valueNombre" => "", "textoNombre" => "");
		
		$this->controlador				= null;
		$this->funcionNombre			= null;

		$this->propiedades['id']		= $nombre;
		$this->propiedades['name']		= $nombre;
		$this->propiedades['size']		= "5";
		$this->propiedades['multiple']	= "multiple";
	}

	/**
	 * Modifica los items seleccionados en la Lista.
	 *
	 * @param array $itemsSeleccionados Los items seleccionados en la Lista M�ltiple.
	 */
	function modItemsSeleccionados($itemsSeleccionados) {
		if ($this->esValidado == false) {
			$this->itemsSeleccionados = array();
			foreach ($itemsSeleccionados as $itemId) {
				$this->itemsSeleccionados[$itemId] = true;
			}
		}
	}

	/**
	 * Modifica el tama�o m�ximo del texto para los items de la Lista M�ltiple.
	 * Si el texto de un item supera el t�ma�o m�ximo,
	 * se cortar� la cadena de texto y se a�adir�n tres (3) puntos al final (...).
	 * Ej.: Si el tama�o m�ximo de la cadena es tres (3) y un item es la palabra "Lista",
	 * el item aparecer� de la siguiente manera: "Lis...".
	 *
	 * @param int $textoTam El tama�o m�ximo del texto para los items de la Lista M�ltiple.
	 */
	function modTextoTam($textoTam) {
		$this->textoTam = $textoTam;
	}

	/**
	 * Modifica el mensaje de error que se debe mostrar cuando el resultado de la validaci�n de la Lista M�ltiple es incorrecto.
	 *
	 * @param string $errorMensaje Texto del mensaje de error que se debe mostrar cuando el resultado de la validaci�n de la Lista M�ltiple es incorrecto.
	 */
	function modErrorMensaje($errorMensaje) {
		$this->errorMensaje = $errorMensaje;
	}

	/**
	 * Modifica si se debe seleccionar obligatoriamente al menos un item en la Lista M�ltiple.
	 *
	 * @param boolean $esObligatorio Indica si se debe seleccionar obligatoriamente al menos un item en la Lista M�ltiple.
	 */
	function modEsObligatorio($esObligatorio) {
		if (is_bool($esObligatorio)) {
			$this->esObligatorio = $esObligatorio;
		}
	}
	
	/**
	 * Modifica el objeto y el nombre del m�todo que administra los items.
	 * Este m�todo debe devolver un objeto de tipo PListaMultipleItem o false cuando no existan m�s registros.
	 *
	 * @param object $controlador Objeto que tiene el m�todo que administra los items.
	 * @param string $funcionNombre Nombre de la funci�n que administra los items.
	 */
	function modControlador($controlador, $funcionNombre) {
		$this->controlador = $controlador;
		$this->funcionNombre = $funcionNombre;
	}

	/**
	 * Adiciona una propiedad y su valor a la Lista M�ltiple.
	 *
	 * @param string $nombre Nombre de la propiedad.
	 * @param string $valor Valor de la propiedad.
	 */
	function adiPropiedad($nombre, $valor) {
		$this->propiedades[$nombre] = $valor;
	}

	/**
	 * Retorna el nombre de la Lista M�ltiple.
	 *
	 * @return string
	 */
	function conNombre() {
		return $this->nombre;
	}

	/**
	 * Retorna el tama�o m�ximo del texto para los items de la Lista M�ltiple.
	 * Si el texto de un item supera el t�ma�o m�ximo,
	 * se corta la cadena de texto y se a�aden tres (3) puntos al final (...).
	 * Ej.: Si el tama�o m�ximo de la cadena es tres (3) y un item es la palabra "Lista",
	 * el item aparece de la siguiente manera: "Lis...".
	 * 
	 * @return string
	 */
	function conTextoTam() {
		return $this->textoTam;
	}

	/**
	 * Retorna el mensaje de error que se debe mostrar cuando el resultado de la validaci�n de la Lista M�ltiple es incorrecto.
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
	 * Genera el codigo para el "select" HTM y lo pinta. En caso de existir un error en la validaci�n,
	 * pinta igualmente el mensaje de error.
	 * El nombre del tag para la Lista M�ltiple debe ser igual al nombre del componente.
	 * El nombre del tag para el mensaje de error debe ser igual al nombre del componente,
	 * seguido por la cadena de texto "Error" (ej: para el componente "nombreComponente",
	 * entonces ser�: "nombreComponenteError").
	 *
	 * @param NokTemplate $nokTemplate Objeto de la libreria NokTemplate que permite usar templates.
	 * @param string $formaNombre Nombre de la forma a la que pertenece la slista m�ltiple.
	 */
	function pintar(&$nokTemplate, $formaNombre) {
		$campoTextoHtm = $this->conHtm($formaNombre);

		$nokTemplate->asignar($this->nombre, $campoTextoHtm);

		$errorMensaje_ = $this->conErrorMensaje();
		$errorMensajeTag = $this->nombre."Error";
		$nokTemplate->asignar($errorMensajeTag, $errorMensaje_);
	}

	/**
	 * Retorna el codigo para el "select" HTM.
	 *
	 * @param string $formaNombre Nombre de la forma a la que pertenece la slista m�ltiple.
	 * @return string
	 */
	function conHtm($formaNombre) {
		$listaMultipleHtm = "
			<script language=\"javascript\">
				function {$this->nombre}PasarItem() {
					objTodos = document.getElementById(\"{$this->nombre}Todos\");
					obj = document.getElementById(\"{$this->nombre}[]\");
					
					for (i = 0; i < objTodos.options.length; i++) {
						if (objTodos.options[i].selected) {
							var optionNuevo = document.createElement('option');
							optionNuevo.value = objTodos.options[i].value;
	  						optionNuevo.text = objTodos.options[i].text;
	  						obj.options.add(optionNuevo);
							objTodos.remove(i);
							i--;
						}
					}
					
					for (i = 0; i < obj.options.length; i++) {
						obj.options[i].selected = true;
					}
					
					xajax_".$this->nombre."Act(xajax.getFormValues('".$formaNombre."'));
				}
				
				function {$this->nombre}DevolverItem() {
					objTodos = document.getElementById(\"{$this->nombre}Todos\");
					obj = document.getElementById(\"{$this->nombre}[]\");
					
					for (i = 0; i < obj.options.length; i++) {
						if (obj.options[i].selected) {
							var optionNuevo = document.createElement('option');
							optionNuevo.value = obj.options[i].value;
	  						optionNuevo.text = obj.options[i].text;
	  						objTodos.options.add(optionNuevo);
							obj.remove(i);
							i--;
						}
					}
					
					for (i = 0; i < obj.options.length; i++) {
						obj.options[i].selected = true;
					}
					
					xajax_".$this->nombre."Act(xajax.getFormValues('".$formaNombre."'));
				}
			</script>
			<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<td align=\"center\">";

		$listaTodosHtm = $listaHtm = "<select";
		// Genera el codigo de las propiedades y sus valores para el "select" HTM.
		foreach ($this->propiedades as $nombre => $valor) {
			if (strtolower($nombre) == "name" or strtolower($nombre) == "id") {
				$listaTodosHtm .= " {$nombre}=\"{$valor}Todos\"";
				$listaHtm .= " {$nombre}=\"{$valor}[]\"";
			} else {
				$listaTodosHtm .= " {$nombre}=\"{$valor}\"";
				$listaHtm .= " {$nombre}=\"{$valor}\"";
			}
		}

		$listaTodosHtm .= ">";
		$listaHtm .= ">";
		$itemsSeleccionadosCant = 0;

		while (($item = $this->controlador->{$this->funcionNombre}()) !== false) {
			/* @var $item PListaMultipleItem */
			$value = trim($item->value);
			$texto = trim($item->texto);

			if ($this->textoTam > 0 and strlen($texto) > $this->textoTam) {
				$texto = substr($texto, 0, $this->textoTam)."...";
			}

			$itemHtm = "\n\t<option value=\"{$value}\">{$texto}</option>";
			
			if (@$this->itemsSeleccionados[$value]) {
				$listaHtm .= $itemHtm;
				$itemsSeleccionadosCant++;
			} else {
				$listaTodosHtm .= $itemHtm;
			}
		}

		$listaMultipleHtm .= $listaTodosHtm."</select></td>
			<td width=\"50\" align=\"center\">
				<img src=\"../../img/sitio/derecha.png\" width=\"30\" height=\"30\" onclick=\"{$this->nombre}PasarItem()\" /><br /><br />
                <img src=\"../../img/sitio/izquierda.png\" width=\"30\" height=\"30\" onclick=\"{$this->nombre}DevolverItem()\" />
            </td>
            <td align=\"center\">Seleccionados: <strong>".$itemsSeleccionadosCant."</strong> ".$listaHtm;

		$listaMultipleHtm .= "</select></td></tr></table>";

		return $listaMultipleHtm;
	}

	/**
	 * Retorna el codigo Javascript para realizar la validaci�n de la Lista en el cliente.
	 *
	 * @return string
	 */
	function conValidacionJs() {
		$esObligatorioStr = ($this->esObligatorio ? "true" : "false");
		$validacionJs = "validarListaMultiple(\"{$this->nombre}\", \"{$this->nombre}Error\", {$esObligatorioStr}, validacion)";

		return $validacionJs;
	}

	/**
	 * Devuelve el resultado de la validaci�n de la Lista.
	 *
	 * @return boolean, string
	 */
	function validar() {
		if (isset($_POST[$this->nombre])) {
			$this->modItemsSeleccionados($_POST[$this->nombre]);
			$this->validacionResultado = $_POST[$this->nombre];
		} else {
			$this->itemsSeleccionados	= array();
			$this->validacionResultado	= array();
		}

		$this->esValidado = true;
		
		return $this->validacionResultado;
	}
}

class PListaMultipleItem {
	public $value;
	public $texto;

	public function __construct() {
		$this->value = "";
		$this->texto = "";
	}
}
?>