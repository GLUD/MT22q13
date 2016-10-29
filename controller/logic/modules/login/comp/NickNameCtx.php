<?php
require_once(Config::PATH . Config::CONTROLLER_LIB . 'PCampoTexto.php');

/**
 * UserCtx
 *
 * @autor               GekkoIns.
 * @desarrollado por 	Harold Duque
 * @version             1.0
 * @fecha creacion      Oct of 2016
 * @descripcion         Input type text
 */
class NickNameCtx {

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
        $this->ctx = new PCampoTexto("nickNameCtx");
        $this->ctx->modEsObligatorio(false);
        $forma->adiElemento($this->ctx);
    }

    /**
     * Pinta el campo.
     *
     * @param NokTemplate $nokTemplate
     */
    public function pintar($nokTemplate) {
        $this->ctx->modErrorMensaje("*Por favor ingrese su nombre de usurio");
        $this->ctx->adiPropiedad("class", "form-control");
        $this->ctx->adiPropiedad("style", "width: 255px");
        $this->ctx->adiPropiedad("maxlength", "50");
        $this->ctx->adiPropiedad("placeholder", "User");
        $this->ctx->pintar($nokTemplate);
    }
}
