<?php
/**
 * PForma -- Componente encargado de administrar el elemento "Forma" (form).
 *
 * @author		Harold D. Duque C. - middlebrainco@gmail.com
 * @version		1.0
 * @since		2013.
 */
class PForma {
	/**
	 * Nombre de la Forma (valor de la propiedad "name" del elemento "form" HTM).
	 *
	 * @var string
	 */
	var $nombre;

	/**
	 * Arreglo con los elementos que pertenecen a la forma.
	 *
	 * @var array
	 */
	var $elementos;

	/**
	 * Arreglo con los botones que pertenecen a la forma.
	 *
	 * @var array
	 */
	var $botones;

	/**
	 * Ruta del archivo "Validaciones.js".
	 *
	 * @var string
	 */
	var $validacionesJsSrc;

	/**
	 * Arreglo con el nombre y valor de las propiedades de la Forma.
	 * Cada elemento del arreglo tiene como nombre la propiedad HTM y el dato asignado tiene
	 * el valor de la propiedad (ej. $propiedades['name'] = "nombreForma", asignara
	 * el valor "nombreForma" a la propiedad "name" del elemento "form" HTM).
	 *
	 * @var array
	 */
	var $propiedades;

	/**
	 * Constructora. Inicializa los atributos.
	 *
	 * @param string $nombre Nombre de la Forma (valor de la propiedad "name" del elemento "form" HTM).
	 * @return PForma
	 */
	function PForma($nombre) {
		$this->nombre					= $nombre;

		$this->elementos				= array();
		$this->botones					= array();
		$this->validacionesJsSrc		= "../../view/js/";

		$this->propiedades['id']		= $nombre;
		$this->propiedades['name']		= $nombre;
		$this->propiedades['method']	= "post"; // Por defecto la propiedad "method" del elemento "form" HTM es "post".
	}

	/**
	 * Modifica la ruta del archivo "Validaciones.js".
	 *
	 * @param string $src Ruta del archivo "Validaciones.js".
	 */
	function modValidacionesJsSrc($src) {
		$this->validacionesJsSrc = $src;
	}

	/**
	 * Adiciona una elemento a la Forma.
	 *
	 * @param phpf_object $elemento Objeto phpf (ej. pCampoTexto, pComboBox, etc.).
	 */
	function adiElemento($elemento) {
		$this->elementos[] = $elemento;
	}

	/**
	 * Adiciona una Bot�n a la Forma.
	 *
	 * @param PBoton $boton Objeto PBoton de phpf.
	 */
	function adiBoton($boton) {
		$this->botones[] = $boton;
	}

	/**
	 * Adiciona una propiedad y su valor a la Forma.
	 *
	 * @param string $nombre Nombre de la propiedad.
	 * @param string $valor Valor de la propiedad.
	 */
	function adiPropiedad($nombre, $valor) {
		$this->propiedades[$nombre] = $valor;
	}

	/**
	 * Genera el codigo para el "form" HTM y lo pinta.
	 * El nombre del tag para la Forma debe ser igual al nombre del componente.
	 * Adem�s, se debe incluir el tag de cierre de la forma. Este tag debe comenzar con el nombre del componente, seguido por la cadena de texto "Cierre" (ej. para el componente con el nombre "nombreForma", el tag de cierre se debe llamar "nombreFormaCierre").
	 *
	 * @param NokTemplate $nokTemplate Objeto de la libreria NokTemplate que permite usar templates.
	 */
	function pintar(&$nokTemplate) {
		$formaHtm = $this->conHtm();

		$formaCierreTagNombre = $this->nombre."Cierre";

		$nokTemplate->asignar($this->nombre, $formaHtm);
		$nokTemplate->asignar($formaCierreTagNombre, "</form>");
	}

	/**
	 * Retorna el codigo para el "form" HTM.
	 *
	 * @return string
	 */
	function conHtm() {
		$formaHtm = "
			<script language=\"javascript\" src=\"{$this->validacionesJsSrc}Validaciones.js\"></script>
			<script language=\"javascript\">
				function {$this->nombre}Validacion() {
					var validacion = true;";

		foreach ($this->elementos as $id => $elemento) {
			$formaHtm .= "
					validacion = ".$elemento->conValidacionJs()." && validacion;";
		}

		$formaHtm .= "
					return validacion;
				}
				
				function {$this->nombre}Submit(boton) {
					if ({$this->nombre}Validacion()) {
						boton.disabled = 'disabled';
						boton.onclick = '';
						document.{$this->nombre}.submit();
					}
				}
			</script>
			<form";

		// Genera el codigo de las propiedades y sus valores para el "input" HTM.
		foreach ($this->propiedades as $nombre => $valor) {
			$formaHtm .= " {$nombre}=\"{$valor}\"";
		}

		$formaHtm .= "><input id=\"".$this->nombre."\" name=\"$this->nombre\" type=\"hidden\" value=\"1\">";

		return $formaHtm;
	}
        
	/**
	 * Retorna el resultado de la validaci�n de todos los campos que pertenecen a la Forma.
	 * Si el resultado no es correcto, retorna false.
	 * Si el resultado es correcto, devuelveretorna un arreglo con los datos ingresados 
	 * por el usuario en cada uno de los campos.
	 *
	 * @return boolean, array
	 */
	function validar() {
		if (count($_POST) > 0) {

			// Buscando el Bot�n por el cual enviaron la forma
			$botonSubmit = NULL;
			foreach ($this->botones as $id => $boton) {
				$botonNombre = $boton->conNombre();

				if (isset($_POST[$botonNombre]) or isset($_POST[$botonNombre.'_x'])) {
					$botonSubmit = $boton;
					break;
				}
			}

			if ($botonSubmit != NULL) {

				$validacionResultado = true;

				// Se validan los elementos que pertenecen a la Forma con respecto al Bot�n por el que enviaron la forma.
				foreach ($this->elementos as $id => $elemento) {
					$elementoValidacion = $elemento->validar();
					$validacionResultado = (
					$elementoValidacion != false and
					$validacionResultado
					? true : false);

					$datos[$elemento->conNombre()] = $elementoValidacion;
				}

				if ($validacionResultado != false) {
					$datos['botonSubmit'] = $botonSubmit->conNombre();
					return $datos;
				} else {
					return false;
				}

			} else {

				// Si no se encuentra ningun Bot�n de los que pertenecen a la forma, se retorna "false".
				return false;
			}

		} else {
			return false;
		}
	}

	function validarSinBoton() {
		if (isset($_POST[$this->nombre])) {
			$validacionResultado = true;
			$datos = array();

			// Se validan los elementos que pertenecen a la Forma con respecto al Bot�n por el que enviaron la forma.
			foreach ($this->elementos as $id => $elemento) {
				$elementoValidacion = $elemento->validar();
				$validacionResultado = (
				$elementoValidacion !== false and
				$validacionResultado
				? true : false);

				$datos[$elemento->conNombre()] = $elementoValidacion;
			}

			if ($validacionResultado != false) {
				return $datos;
			} else {
				return false;
			}

		} else {
			return false;
		}
	}
}
?>