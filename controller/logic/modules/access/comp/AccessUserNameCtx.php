<?php
require_once(Config::PATH . Config::CONTROLLER_LIB . 'PCampoTexto.php');

/**
 * AccessUserNameCtx
 *
 * @autor               GekkoIns.
 * @desarrollado por 	Andres Angarita
 * @version             1.0
 * @fecha creacion      Oct of 2016
 * @descripcion         Input type text
 */
class AccessUserNameCtx {

    /**
     * @var PCampoTexto
     */
    public $ctx;

    /**
     * @param PForma $forma
     */
    public function __construct($forma) {
        $this->ctx = new PCampoTexto("accessUserNameCtx");
        $this->ctx->modEsObligatorio(true);
        $forma->adiElemento($this->ctx);
    }

    /**
     * @param NokTemplate $nokTemplate
     */
    public function Paint($nokTemplate) {
        $this->ctx->modErrorMensaje("*Por favor ingrese un usuario.");
        $this->ctx->adiPropiedad("class", "form-control");
        $this->ctx->adiPropiedad("maxlength", "50");
        $this->ctx->adiPropiedad("placeholder", "Cambiar Usuario");
        $this->ctx->pintar($nokTemplate);
    }
}