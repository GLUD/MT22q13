<?php
require_once(Config::PATH . Config::CONTROLLER_LIB . 'PCampoTexto.php');

/**
 * AccessPassCtx
 *
 * @autor               GekkoIns.
 * @desarrollado por 	Andres Angarita
 * @version             1.0
 * @fecha creacion      Oct of 2016
 * @descripcion         Input type text
 */
class AccessPassCtx {

    /**
     * @var PCampoTexto
     */
    public $ctx;

    /**
     * @param PForma $forma
     */
    public function __construct($forma) {
        $this->ctx = new PCampoTexto("accessPassCtx");
        $this->ctx->modEsObligatorio(true);
        $forma->adiElemento($this->ctx);
    }

    /**
     * @param NokTemplate $nokTemplate
     */
    public function Paint($nokTemplate) {
        $this->ctx->modErrorMensaje("*Por favor ingrese un password.");
        $this->ctx->adiPropiedad("class", "form-control");
        $this->ctx->adiPropiedad("maxlength", "50");
        $this->ctx->adiPropiedad("placeholder", "Cambiar Password");
        $this->ctx->pintar($nokTemplate);
    }
}