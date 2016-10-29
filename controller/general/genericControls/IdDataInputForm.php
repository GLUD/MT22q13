<?php
require_once(Config::PATH . Config::CONTROLLER_LIB . 'PCampoTexto.php');

/**
 * IdDataInputForm
 *
 * @autor               GekkoIns.
 * @desarrollado por 	Harold Duque
 * @version             1.0
 * @fecha creacion      Oct of 2016
 * @descripcion         Input type hidden
 */
class IdDataInputForm {

    /**
     * Campo de texto.
     *
     * @var PCampoTexto
     */
    public $ctx;

    /**
     * Constructor.
     *
     * @param PForma $forma
     */
    public function __construct($forma) {
        $this->ctx = new PCampoTexto("idDataInputForm");
        $this->ctx->modEsObligatorio(false);
        $forma->adiElemento($this->ctx);
    }

    /**
     * Pinta el campo.
     *
     * @param NokTemplate $nokTemplate
     */
    public function pintar($nokTemplate) {
        $this->ctx->adiPropiedad("type", "hidden");
        $this->ctx->adiPropiedad("value", "0");
        $this->ctx->pintar($nokTemplate);
    }
}  