<?php
/**
 * Xml -- Administra la administraci�n de archivos XML.
 * 
 * @author		I.T. Group Ltda.
 * @author		Diego M. Garc�a M. - diego@itgroup.com.co
 * @version		1.0
 * @since		Marzo de 2009
 */
class Xml {
	/**
	 * Objeto que permite administrar un arhcivo XML.
	 *
	 * @var DOMDocument
	 */
	private $domDocument;

	/**
	 * Constructora.
	 *
	 */
	public function __construct() {
		$this->domDocument = new DOMDocument("1.0");
	}

	/**
	 * Creal el nodo ra�z de la estructura XML.
	 *
	 * @param string $nombre
	 * @return DOMElement
	 */
	public function crearNodoRaiz($nombre) {
		$nodo = $this->domDocument->createElement($nombre);
		$this->domDocument->appendChild($nodo);
		return $nodo;
	}

	/**
	 * Adiciona un nodo a la estructura XML.
	 *
	 * @param DOMNode $padre
	 * @param string $nombre
	 * @param string $texto
	 * @return DOMElement
	 */
	public function crearNodo($padre, $nombre, $texto = null) {
		$nodo = $this->domDocument->createElement($nombre);
		$padre->appendChild($nodo);

		if ($texto !== null) {
			$nodoVal = $this->domDocument->createTextNode($texto);
			$nodo->appendChild($nodoVal);
		}
		
		return $nodo;
	}
	
	/**
	 * Guarda la estructura XML en un archivo.
	 *
	 * @param string $arcNombre
	 */
	public function guardar($arcNombre) {
		$this->domDocument->save($arcNombre);
	}
	
	/**
	 * Guarda el objeto SimpleXml en un archivo.
	 *
	 * @param SimpleXMLElement $simpleXml
	 */
	public function guardarSimpleXml($simpleXml) {
		$dom_sxe = dom_import_simplexml($simpleXml);
		$dom_sxe = $this->domDocument->importNode($dom_sxe, true);
		$dom_sxe = $this->domDocument->appendChild($dom_sxe);
	}
}
