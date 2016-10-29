<?php
/**
 * PListaGrupo -- Componente encargado de administrar un grupo de "Listas" anidadas (select).
 *
 * @author		Harold D. Duque C. - middlebrainco@gmail.com
 * @version		1.0
 * @since		2013.
 */
class PListaGrupo {
	/**
	 * Arreglo con las Listas que pertenecen al grupo.
	 *
	 * @var array
	 */
	var $listas;

	/**
	 * Constructora. Inicializa los atributos.
	 *
	 * @return PListaGrupo
	 */
	function PListaGrupo() {
		$this->listas	= array();
	}

	/**
	 * Adiciona una Lista al arreglo de Listas que pertenecen al grupo.
	 *
	 * @param PLista $lista La Lista que se quiere adicionar al grupo.
	 */
	function adiHijo($lista) {
		$this->listas[] = $lista;
	}

	/**
	 * Se encarga de invocar el metodo "pintar()" de cada Lista que pertenece al grupo.
	 * Adicionalmente, carga los items correspondientes al item seleccionado en la Lista de la que depende.
	 *
	 * @param NokTemplate $nokTemplate Objeto de la libreria NokTemplate que permite usar templates.
	 */
	function pintar(&$nokTemplate) {
		$listasTam = count($this->listas);
		
		for ($i = 0; $i < $listasTam; $i++) {
			$lista = $this->listas[$i];
			$lista->cargarItems();
		}
		
		for ($i = 0; $i < $listasTam; $i++) {
			/* @var $lista PLista */
			$lista = $this->listas[$i];
			$listaItems = $lista->conItems();

			$this->adiListaHijo($lista, $i + 1);

			if ($i == 0) {
				$lista->pintar($nokTemplate, true);
			} else {
				$itemsSeleccionados = array();
				$padre = $this->listas[$i - 1];
				if ($padre->conItemSeleccionado() != "") {
					$padreValue = $padre->conItemSeleccionado();

					foreach ($listaItems as $item) {
						$value = trim($item->padreId);

						if ($value == $padreValue) {
							$itemsSeleccionados[] = $item;
						}
					}
				}
				$lista->items = $itemsSeleccionados;
				$lista->pintar($nokTemplate, true);
			}
		}
	}

	/**
	 * Adiciona las Listas dependientes a la Lista.
	 *
	 * @param PLista $lista Lista en la que se evaluan las Listas dependientes.
	 * @param int $hijoPos Valor de la posicion de la siguiente Lista dependiente.
	 */
	function adiListaHijo($lista, $hijoPos) {
		$listasTam = count($this->listas);
		if ($hijoPos != ($listasTam)) {
			$hijos	= array();

			for ($j = ($hijoPos); $j < $listasTam; $j++){
				$hijos[] = $this->listas[$j];
			}

			$lista->adiHijo($hijos);
		}
	}
}
?>