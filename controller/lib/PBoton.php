<?php
/**
 * PBoton -- Componente encargado de administrar el elemento "Bot�n" (input).
 *
 * @author		Harold D. Duque C. - middlebrainco@gmail.com
 * @version		1.0
 * @since		2013.
 */
class PBoton {
	/**
	 * Nombre del Bot�n (valor de la propiedad "name" del elemento "input" HTM).
	 *
	 * @var string
	 */
	var $nombre;
	
	/**
	 * Arreglo con el nombre y valor de las propiedades del Bot�n.
	 * Cada elemento del arreglo tiene como nombre la propiedad HTM y el dato asignado tiene
	 * el valor de la propiedad (ej. $propiedades['name'] = "nombreBoton", asignara
	 * el valor "nombreBoton" a la propiedad "name" del elemento "input" HTM).
	 *
	 * @var array
	 */
	var $propiedades;

	/**
	 * Constructora. Inicializa los atributos.
	 *
	 * @param string $nombre Nombre del Bot�n (valor de la propiedad "name" del elemento "input" HTM).
	 * @param string $value Texto que aparece en el Bot�n (valor de la propiedad "value" del elemento "input" HTM).
	 * @return PBoton
	 */
	function PBoton($nombre, $texto) {
		$this->nombre		= $nombre;
		
		$this->propiedades['id']		= $nombre;
		$this->propiedades['name']		= $nombre;
		$this->propiedades['value']		= $texto;
		$this->propiedades['type']		= "submit"; // Por defecto la propiedad "type" del elemento "input" HTM es "submit".
	}
	
	/**
	 * Retorna el nombre del Bot�n (valor de la propiedad "name" del elemento "input" HTM).
	 * 
	 * @return string
	 */
	function conNombre() {
		return $this->nombre;
	}
	
	/**
	 * Adiciona una propiedad y su valor al Bot�n.
	 *
	 * @param string $nombre Nombre de la propiedad.
	 * @param string $valor Valor de la propiedad.
	 */
	function adiPropiedad($nombre, $valor) {
		$this->propiedades[$nombre] = $valor;
	}
	
	/**
	 * Genera el codigo para el "input" HTM y lo pinta.
	 * El nombre del tag para el Bot�n debe ser igual al nombre del Bot�n.
	 *
	 * @param NokTemplate $nokTemplate Objeto de la libreria NokTemplate que permite usar templates.
	 */
	function pintar(&$nokTemplate) {
		$botonHtm = $this->conHtm();

		$nokTemplate->asignar($this->nombre, $botonHtm);
	}
	
	/**
	 * Retorna el codigo para el "input" HTM.
	 *
	 * @return string
	 */
	function conHtm() {
		$botonHtm = "<input";

		// Genera el codigo de las propiedades y sus valores para el "input" HTM.
		foreach ($this->propiedades as $nombre => $valor) {
			$botonHtm .= " {$nombre}=\"{$valor}\"";
		}

		$botonHtm .= ">";

		return $botonHtm;
	}
}
?>