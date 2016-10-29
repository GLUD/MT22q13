<?php
require_once(Config::PATH . Config::CONTROLLER_LIB . 'PCampoTexto.php');

/**
 * PasswordCtx
 *
 * @autor               GekkoIns.
 * @desarrollado por 	Harold Duque
 * @version             1.0
 * @fecha creacion      Oct of 2016
 * @descripcion         Input type text
 */
class PasswordCtx {

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
        $this->ctx = new PCampoTexto("passwordCtx");
        $this->ctx->modEsObligatorio(false);
        $forma->adiElemento($this->ctx);
    }

    /**
     * Pinta el campo.
     *
     * @param NokTemplate $nokTemplate
     */
    public function pintar($nokTemplate) {
        $this->ctx->modErrorMensaje("*Por favor ingrese su password");
        $this->ctx->adiPropiedad("class", "form-control");
        $this->ctx->adiPropiedad("style", "width: 255px");
        $this->ctx->adiPropiedad("type", "password");
        $this->ctx->adiPropiedad("maxlength", "50");
        $this->ctx->adiPropiedad("placeholder", "Password");
        $this->ctx->pintar($nokTemplate);
    }
}
