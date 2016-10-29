<?php

/**
 * PBotonRadio -- Componente encargado de administrar el elemento "Bot�n de radio" (radio).
 *
 * @author		Harold D. Duque C. - middlebrainco@gmail.com
 * @version		1.0
 * @since		2013.
 */
class PBotonRadio {

    /**
     * Nombre del Bot�n de radio (valor de la propiedad "name" del elemento "radio" HTM).
     *
     * @var string
     */
    var $nombre;
    /**
     * Indica si se debe seleccionar obligatoriamente un item del Bot�n de radio.
     *
     * @var boolean
     */
    var $esObligatorio;
    /**
     * Indica si el Bot�n de radio ya fue validado.
     *
     * @var boolean
     */
    var $esValidado;
    /**
     * Indica el resultado de la validacion del Bot�n de radio.
     *
     * @var boolean
     */
    var $validacionResultado;
    /**
     * El mensaje de error que se debe mostrar cuando el resultado de la validaci�n del Bot�n de radio es incorrecto.
     *
     * @var string
     */
    var $errorMensaje;
    /**
     * Arreglo con el nombre y valor de las propiedades del Bot�n de radio.
     * Cada elemento del arreglo tiene como nombre la propiedad HTM y el dato asignado tiene el valor
     * de la propiedad (ej. $propiedades['name'] = "nombreBotonRadio", asignar� el valor "nombreBotonRadio"
     * a la propiedad "name" del elemento "radio" HTM).
     *
     * @var array
     */
    var $propiedades;
    /**
     * El item seleccionado en el Bot�n de radio.
     *
     * @var string
     */
    var $itemSeleccionado;
    /**
     * Objeto que tiene el m�todo que administra los items.
     *
     * @var object
     */
    var $controlador;
    /**
     * Nombre del m�todo que administra los items.
     * Esta funci�n debe devolver un objeto de tipo PBotonRadioItem o false cuando no existan m�s registros
     *
     * @var string
     */
    var $funcionNombre;
    /**
     * La plantilla con el HTM que se quiere mostrar cada item del Bot�n de radio.
     * El tag {BOTONRADIO} se reemplazar� por el Bot�n de radio del item.
     * El tag {TEXTO} se reemplazar� por el texto del item.
     * Los tags deben estar en may�sculas.
     * Ej.: con la plantilla "{BOTONRADIO} {TEXTO}<br>", para el item con value="1", texto="nombreItem"
     * aparecer� "<input type=radio" name="nombreBotonRadio" value="1"> nombreItem<br>".
     *
     * @var string
     */
    var $plantilla;

    /**
     * Constructora. Inicializa los atributos.
     *
     * @param string $nombre Nombre del Bot�n de radio (valor de la propiedad "name" del elemento "radio" HTM).
     * @return PBotonRadio
     */
    function PBotonRadio($nombre) {
        // Inicializacion de atributos.
        $this->nombre = $nombre;

        $this->esObligatorio = true;
        $this->esValidado = false;
        $this->validacionResultado = true;
        $this->errorMensaje = "*";
        $this->itemSeleccionado = "";
        $this->plantilla = "{BOTONRADIO} {TEXTO} ";

        $this->controlador = null;
        $this->funcionNombre = null;

        $this->propiedades['id'] = $nombre;
        $this->propiedades['name'] = $nombre;
    }

    /**
     * Modifica la plantilla con el HTM que se quiere mostrar cada item del Bot�n de radio.
     * El tag {BOTONRADIO} se reemplazar� por el Bot�n de radio del item.
     * El tag {TEXTO} se reemplazar� por el texto del item.
     * Los tags deben estar en may�sculas.
     * Ej.: con la plantilla "{BOTONRADIO} {TEXTO}<br>", para el item con value="1", texto="nombreItem"
     * aparecer� "<input type=radio" name="nombreBotonRadio" value="1"> nombreItem<br>".
     *
     * @param string $plantilla La plantilla con el HTM que se quiere mostrar cada item del Bot�n de radio.
     */
    function modPlantilla($plantilla) {
        $this->plantilla = $plantilla;
    }

    /**
     * Modifica el item seleccionado en el Bot�n de radio.
     *
     * @param string $itemSeleccionado El item seleccionado en el Bot�n de radio.
     */
    function modItemSeleccionado($itemSeleccionado) {
        if ($this->esValidado == false) {
            $this->itemSeleccionado = $itemSeleccionado;
        }
    }

    /**
     * Modifica el mensaje de error que se debe mostrar cuando el resultado de la validaci�n del Bot�n de radio es incorrecto.
     *
     * @param string $errorMensaje Texto del mensaje de error que se debe mostrar cuando el resultado de la validaci�n del Bot�n de radio es incorrecto.
     */
    function modErrorMensaje($errorMensaje) {
        $this->errorMensaje = $errorMensaje;
    }

    /**
     * Modifica si se debe seleccionar obligatoriamente un item en el Bot�n de radio.
     *
     * @param boolean $esObligatorio Indica si se debe seleccionar obligatoriamente un item en el Bot�n de radio.
     */
    function modEsObligatorio($esObligatorio) {
        if (is_bool($esObligatorio)) {
            $this->esObligatorio = $esObligatorio;
        }
    }

    /**
     * Modifica el objeto y el nombre del m�todo que administra los items.
     * Este m�todo debe devolver un objeto de tipo PBotonRadioItem o false cuando no existan m�s registros.
     *
     * @param object $controlador Objeto que tiene el m�todo que administra los items.
     * @param string $funcionNombre Nombre de la funci�n que administra los items.
     */
    function modControlador($controlador, $funcionNombre) {
        $this->controlador = $controlador;
        $this->funcionNombre = $funcionNombre;
    }

    /**
     * Adiciona una propiedad y su valor al Bot�n de radio.
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
     * Retorna el nombre del Bot�n de radio.
     *
     * @return string
     */
    function conNombre() {
        return $this->nombre;
    }

    /**
     * Retorna el mensaje de error que se debe mostrar cuando el resultado de la validaci�n del Bot�n de radio es incorrecto.
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
     * Genera el codigo para el "radio" HTM y lo pinta. En caso de existir un error en la validaci�n,
     * pinta igualmente el mensaje de error.
     * El nombre del tag para el Bot�n de radio debe ser igual al nombre del componente.
     * El nombre del tag para el mensaje de error debe ser igual al nombre del componente,
     * seguido por la cadena de texto "Error" (ej: para el componente "nombreComponente",
     * entonces ser�: "nombreComponenteError").
     *
     * @param NokTemplate $nokTemplate Objeto de la libreria NokTemplate que permite usar templates.
     */
    function pintar(&$nokTemplate) {
        $botonRadioHtm = $this->conHtm();

        $nokTemplate->asignar($this->nombre, $botonRadioHtm);

        $errorMensaje_ = $this->conErrorMensaje();
        $errorMensajeTag = $this->nombre . "Error";
        $nokTemplate->asignar($errorMensajeTag, $errorMensaje_);
    }

    /**
     * Retorna el codigo para el "radio" HTM.
     *
     * @return string
     */
    function conHtm() {
        $itemBotonRadioHtm = "";

        $i = 1;
        while (($item = $this->controlador->{$this->funcionNombre}($i++)) !== false) {
            /* @var $item PBotonRadioItem */
            $value = trim($item->value);
            $texto = trim($item->texto);

            $botonRadioHtm = "<input type=\"radio\" value=\"{$value}\"";

            // Genera el codigo de las propiedades y sus valores para el "checkbox" HTM.
            foreach ($this->propiedades as $nombre => $valor) {
                $botonRadioHtm .= " {$nombre}=\"{$valor}\"";
            }

            if ($value == $this->itemSeleccionado) {
                $botonRadioHtm .= " checked";
            }

            $botonRadioHtm .= ">";

            $plantillaTemp = str_replace("{BOTONRADIO}", $botonRadioHtm, $this->plantilla);
            $itemBotonRadioHtm .= str_replace("{TEXTO}", $texto, $plantillaTemp);
        }

        return $itemBotonRadioHtm;
    }

    /**
     * Retorna el codigo Javascript para realizar la validaci�n del Bot�n de radio en el cliente.
     *
     * @return string
     */
    function conValidacionJs() {
        $esObligatorioStr = ($this->esObligatorio ? "true" : "false");
        $validacionJs = "validarBotonRadio(\"{$this->nombre}\", \"{$this->nombre}Error\", {$esObligatorioStr}, validacion)";

        return $validacionJs;
    }

    /**
     * Devuelve el resultado de la validaci�n del Bot�n de radio.
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

class PBotonRadioItem {

    public $value;
    public $texto;

    public function __construct() {
        $this->value = "";
        $this->texto = "";
    }

}

?>