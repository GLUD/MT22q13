<?php
/**
 * PLista -- Componente encargado de administrar el elemento "Lista" (select).
 *
 * @author		Harold D. Duque C. - middlebrainco@gmail.com
 * @version		1.0
 * @since		2013.
 */
class PLista {
	/**
	 * Nombre de la Lista (valor de la propiedad "name" del elemento "select" HTM).
	 *
	 * @var string
	 */
	var $nombre;

	/**
	 * Indica si se debe seleccionar obligatoriamente un item (que no sea el item "titulo") en la Lista.
	 *
	 * @var boolean
	 */
	var $esObligatorio;

	/**
	 * Indica si la Lista ya fue validada.
	 *
	 * @var boolean
	 */
	var $esValidado;

	/**
	 * Indica el resultado de la validacion de la Lista.
	 *
	 * @var boolean
	 */
	var $validacionResultado;

	/**
	 * El mensaje de error que se debe mostrar cuando el resultado de la validaci�n de la Lista es incorrecto.
	 *
	 * @var string
	 */
	var $errorMensaje;

	/**
	 * Arreglo con el nombre y valor de las propiedades de la Lista.
	 * Cada elemento del arreglo tiene como nombre la propiedad HTM y el dato asignado tiene el valor
	 * de la propiedad (ej. $propiedades['name'] = "nombreLista", asignara el valor "nombreLista"
	 * a la propiedad "name" del elemento "select" HTM).
	 *
	 * @var array
	 */
	var $propiedades;

	/**
	 * El item seleccionado en la Lista.
	 *
	 * @var string
	 */
	var $itemSeleccionado;

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
	 * Indica si hay un item "titulo" en la Lista.
	 * Este item aparecer� de primero en la Lista y estar� seleccionado por defecto.
	 *
	 * @var boolean
	 */
	var $hayTitulo;

	/**
	 * El texto del t�tulo de la Lista.
	 * Este texto aparecer� en el primer item de la Lista y estar� seleccionado por defecto.
	 *
	 * @var string
	 */
	var $titulo;

	/**
	 * El valor del item "t�tulo" de la Lista (valor de la propiedad "value" del item).
	 * Este item aparecer� de primero en la Lista y estar� seleccionado por defecto.
	 *
	 * @var string
	 */
	var $tituloId;

	/**
	 * El c�digo javascript para actualizar las Listas dependientes.
	 *
	 * @var string
	 */
	var $hijoHtm;

	/**
	 * Objeto que tiene el m�todo que administra los items.
	 *
	 * @var object
	 */
	var $controlador;

	/**
	 * Nombre del m�todo que administra los items.
	 * Esta funci�n debe devolver un objeto de tipo PListaItem o false cuando no existan m�s registros
	 *
	 * @var string
	 */
	var $funcionNombre;

	var $items;

	/**
	 * Constructora. Inicializa los atributos.
	 *
	 * @param string $nombre Nombre de la Lista (valor de la propiedad "name" del elemento "select" HTM).
	 * @return PLista
	 */
	function PLista($nombre) {
		// Inicializacion de atributos.
		$this->nombre					= $nombre;

		$this->esObligatorio			= true;
		$this->esValidado				= false;
		$this->validacionResultado		= true;
		$this->errorMensaje				= "*";
		$this->hayTitulo				= true;
		$this->titulo					= "-- Seleccione --";
		$this->tituloId					= "";
		$this->items					= array();
		$this->itemSeleccionado			= "";
		$this->textoTam					= 0;
		$this->hijoHtm					= "";

		$this->controlador				= null;
		$this->funcionNombre			= null;

		$this->propiedades['id']		= $nombre;
		$this->propiedades['name']		= $nombre;
	}

	/**
	 * Modifica si hay un item "titulo" en la Lista.
	 * Este item aparecer� de primero en la Lista y estar� seleccionado por defecto.
	 *
	 * @param boolean $hayTitulo Indica si hay un item "titulo" en la Lista.
	 */
	function modHayTitulo($hayTitulo) {
		$this->hayTitulo = $hayTitulo;
		if ($hayTitulo == false) {
			$this->titulo = "";
			$this->tituloId = "";
		}
	}

	/**
	 * Modifica el texto del t�tulo de la Lista.
	 * Este texto aparecer� en el primer item de la Lista y estar� seleccionado por defecto.
	 *
	 * @param string $titulo El texto del t�tulo de la Lista.
	 */
	function modTitulo($titulo) {
		$this->titulo = $titulo;
	}

	/**
	 * Modifica el valor del item "t�tulo" de la Lista (valor de la propiedad "value" del item).
	 * Este item aparecer� de primero en la Lista y estar� seleccionado por defecto.
	 *
	 * @param string $tituloId El valor del item "t�tulo" de la Lista (valor de la propiedad "value" del item).
	 */
	function modTituloId($tituloId) {
		$this->tituloId = $tituloId;
	}

	/**
	 * Modifica el objeto y el nombre del m�todo que administra los items.
	 * Este m�todo debe devolver un objeto de tipo PListaItem o false cuando no existan m�s registros.
	 *
	 * @param object $controlador Objeto que tiene el m�todo que administra los items.
	 * @param string $funcionNombre Nombre de la funci�n que administra los items.
	 */
	function modControlador($controlador, $funcionNombre) {
		$this->controlador = $controlador;
		$this->funcionNombre = $funcionNombre;
	}

	/**
	 * Modifica el item seleccionado en la Lista.
	 *
	 * @param string $itemSeleccionado El item seleccionado en la Lista.
	 */
	function modItemSeleccionado($itemSeleccionado) {
		if ($this->esValidado == false) {
			$this->itemSeleccionado = $itemSeleccionado;
		}
	}

	/**
	 * Modifica el tama�o m�ximo del texto para los items de la Lista.
	 * Si el texto de un item supera el t�ma�o m�ximo,
	 * se cortar� la cadena de texto y se a�adir�n tres (3) puntos al final (...).
	 * Ej.: Si el tama�o m�ximo de la cadena es tres (3) y un item es la palabra "Lista",
	 * el item aparecer� de la siguiente manera: "Lis...".
	 *
	 * @param int $textoTam El tama�o m�ximo del texto para los items de la Lista.
	 */
	function modTextoTam($textoTam) {
		$this->textoTam = $textoTam;
	}

	/**
	 * Modifica el mensaje de error que se debe mostrar cuando el resultado de la validaci�n de la Lista es incorrecto.
	 *
	 * @param string $errorMensaje Texto del mensaje de error que se debe mostrar cuando el resultado de la validaci�n de la Lista es incorrecto.
	 */
	function modErrorMensaje($errorMensaje) {
		$this->errorMensaje = $errorMensaje;
	}

	/**
	 * Modifica si se debe seleccionar obligatoriamente un item (que no sea el item "titulo") en la Lista.
	 *
	 * @param boolean $esObligatorio Indica si se debe seleccionar obligatoriamente un item (que no sea el item "titulo") en la Lista.
	 */
	function modEsObligatorio($esObligatorio) {
		if (is_bool($esObligatorio)) {
			$this->esObligatorio = $esObligatorio;
		}
	}

	/**
	 * Adiciona una propiedad y su valor a la Lista.
	 *
	 * @param string $nombre Nombre de la propiedad.
	 * @param string $valor Valor de la propiedad.
	 */
	function adiPropiedad($nombre, $valor) {
		$this->propiedades[$nombre] = $valor;
	}

	/**
	 * Retorna el nombre de la Lista.
	 *
	 * @return string
	 */
	function conNombre() {
		return $this->nombre;
	}

	/**
	 * Retorna el tama�o m�ximo del texto para los items de la Lista.
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
	 * Retorna el valor del item seleccionado.
	 *
	 * @return string
	 */
	function conItemSeleccionado() {
		return $this->itemSeleccionado;
	}

	/**
	 * Retorna el texto del t�tulo de la Lista.
	 * Este item aparece de primero en la Lista y seleccionado por defecto.
	 *
	 * @return string
	 */
	function conTitulo() {
		return $this->titulo;
	}

	/**
	 * Retorna el valor del item "t�tulo" de la Lista (valor de la propiedad "value" del item).
	 * Este item aparece de primero en la Lista y seleccionado por defecto.
	 *
	 * @return string
	 */
	function conTituloId() {
		return $this->tituloId;
	}

	/**
	 * Retorna el mensaje de error que se debe mostrar cuando el resultado de la validaci�n de la Lista es incorrecto.
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

	function conItems() {
		return $this->items;
	}

	function cargarItems() {
		$itemNum = 1;

		while (($item = $this->controlador->{$this->funcionNombre}($itemNum++)) !== false) {
			$this->items[] = $item;
		}
	}

	/**
	 * Genera el c�digo javascript para actualizar las Listas dependientes.
	 *
	 * @param array $hijos Arreglo con las Listas dependientes.
	 */
	function adiHijo($hijos) {
		/* @var $hijo PLista */
		$hijo = $hijos[0];
		$hijoItems = $hijo->conItems();

		$this->hijoHtm = "
			\n\t\t\t<script language=\"JavaScript\" src=\"../../js/ComboBoxGroup.js\"></script>
			<script language=\"JavaScript\" type=\"text/JavaScript\">
			function {$this->nombre}Group(){
				//Variable que almacena los subtemas";

		$this->hijoHtm .= "
				var items = new Array();";

		$i = 0;
		foreach ($hijoItems as $item) {
			$value	= trim($item->value);
			$texto	= trim($item->texto);
			$hijoTam = $hijo->conTextoTam();
			$texto	= (($hijoTam != 0 and strlen($texto) > $hijoTam) ? substr($texto, 0, $hijoTam)."..." : $texto);
			$padre	= trim($item->padreId);

			$this->hijoHtm .= "\n\t\t\t\titems[{$i}] = new subCategoria('{$value}', '{$texto}', '{$padre}');";
			$i++;
		}

		$this->hijoHtm .= "
				var hijos = new Array();";

		$i = 0;
		foreach ($hijos as $nombre => $valor) {
			$this->hijoHtm .= "
				hijos[{$i}] = new Array(\"{$valor->conNombre()}\", \"{$valor->conTitulo()}\", \"{$valor->conTituloId()}\");";
			$i++;
		}

		$this->hijoHtm .= "
				\n\t\t\t\tcambiarSubCategorias(\"{$this->nombre}\", \"{$hijo->conNombre()}\", items, hijos);
			}
			</script>\n";

		$this->adiPropiedad("onChange", "{$this->nombre}Group();");
	}

	/**
	 * Genera el codigo para el "select" HTM y lo pinta. En caso de existir un error en la validaci�n,
	 * pinta igualmente el mensaje de error.
	 * El nombre del tag para la Lista debe ser igual al nombre del componente.
	 * El nombre del tag para el mensaje de error debe ser igual al nombre del componente,
	 * seguido por la cadena de texto "Error" (ej: para el componente "nombreComponente",
	 * entonces ser�: "nombreComponenteError").
	 *
	 * @param NokTemplate $nokTemplate Objeto de la libreria NokTemplate que permite usar templates.
	 */
	function pintar(&$nokTemplate, $arreglo = false) {
		if ($arreglo) {
			$campoTextoHtm = $this->conHtmArr();
		} else {
			$campoTextoHtm = $this->conHtm();
		}

		$nokTemplate->asignar($this->nombre, $campoTextoHtm);

		$errorMensaje_ = $this->conErrorMensaje();
		$errorMensajeTag = $this->nombre."Error";
		$nokTemplate->asignar($errorMensajeTag, $errorMensaje_);
	}

	/**
	 * Retorna el codigo para el "select" HTM.
	 *
	 * @return string
	 */
	function conHtm() {
		$campoTextoHtm = $this->hijoHtm."\n<select";

		// Genera el codigo de las propiedades y sus valores para el "select" HTM.
		foreach ($this->propiedades as $nombre => $valor) {
			$campoTextoHtm .= " {$nombre}=\"{$valor}\"";
		}

		$campoTextoHtm .= ">";

		if ($this->hayTitulo) {
			$campoTextoHtm .= "\n\t<option value=\"{$this->tituloId}\"";

			if ($this->itemSeleccionado == "") {
				$campoTextoHtm .= " selected";
			}

			$campoTextoHtm .= ">{$this->titulo}</option>";
		}

		$itemNum = 1;
		while (($item = $this->controlador->{$this->funcionNombre}($itemNum++)) !== false) {
			/* @var $item PListaItem */
			$value = trim($item->value);
			$texto = trim($item->texto);

			if ($this->textoTam > 0 and strlen($texto) > $this->textoTam) {
				$texto = substr($texto, 0, $this->textoTam)."...";
			}

			$campoTextoHtm .= "\n\t<option value=\"{$value}\"";

			if ($value == $this->itemSeleccionado) {
				$campoTextoHtm .= " selected";
			}

			$campoTextoHtm .= ">{$texto}</option>";
		}

		$campoTextoHtm .= "\n</select>";

		return $campoTextoHtm;
	}

	function conHtmArr() {
		$campoTextoHtm = $this->hijoHtm."\n<select";

		// Genera el codigo de las propiedades y sus valores para el "select" HTM.
		foreach ($this->propiedades as $nombre => $valor) {
			$campoTextoHtm .= " {$nombre}=\"{$valor}\"";
		}

		$campoTextoHtm .= ">";

		if ($this->hayTitulo) {
			$campoTextoHtm .= "\n\t<option value=\"{$this->tituloId}\"";

			if ($this->itemSeleccionado == "") {
				$campoTextoHtm .= " selected";
			}

			$campoTextoHtm .= ">{$this->titulo}</option>";
		}

		for ($i = 0; $i < count($this->items); $i++) {
			$item = $this->items[$i];
			/* @var $item PListaItem */
			$value = trim($item->value);
			$texto = trim($item->texto);

			if ($this->textoTam > 0 and strlen($texto) > $this->textoTam) {
				$texto = substr($texto, 0, $this->textoTam)."...";
			}

			$campoTextoHtm .= "\n\t<option value=\"{$value}\"";

			if ($value == $this->itemSeleccionado) {
				$campoTextoHtm .= " selected";
			}

			$campoTextoHtm .= ">{$texto}</option>";
		}

		$campoTextoHtm .= "\n</select>";

		return $campoTextoHtm;
	}

	/**
	 * Retorna el codigo Javascript para realizar la validaci�n de la Lista en el cliente.
	 *
	 * @return string
	 */
	function conValidacionJs() {
		$esObligatorioStr = ($this->esObligatorio ? "true" : "false");
		$validacionJs = "validarLista(\"{$this->nombre}\", \"{$this->nombre}Error\", {$esObligatorioStr}, \"{$this->tituloId}\", validacion)";

		return $validacionJs;
	}

	/**
	 * Devuelve el resultado de la validaci�n de la Lista.
	 *
	 * @return boolean, string
	 */
	function validar() {
		$this->itemSeleccionado = @$_POST[$this->nombre];
		$this->validacionResultado = @$_POST[$this->nombre];
		$this->esValidado = true;

		return $this->validacionResultado;
	}
}

class PListaItem {
	public $value;
	public $texto;
	public $padreId;

	public function __construct() {
		$this->value = "";
		$this->texto = "";
		$this->padreId = "";
	}
}
?>
