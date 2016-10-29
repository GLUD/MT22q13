<?php
require_once(Config::PATH . Config::CONTROLLER_LIB . 'PLista.php');

/**
 * UserDocumentNumberCtx
 *
 * @autor               GekkoIns.
 * @desarrollado por 	Harold Duque
 * @version             1.0
 * @fecha creacion      Oct of 2016
 * @descripcion         Input type text
 */
class UserTelePhoneNumberCtx {

    /**
     * @var PCampoTexto
     */
    public $ctx;

    /**
     * @param PForma $forma
     */
    public function __construct($forma) {
        $this->ctx = new PCampoTexto("userTelePhoneNumberCtx");
        $this->ctx->modEsObligatorio(true);
        $this->ctx->modValidacionTipo("entero");
        $forma->adiElemento($this->ctx);
    }

    /**
     * @param NokTemplate $nokTemplate
     */
    public function Paint($nokTemplate) {
        $this->ctx->modErrorMensaje("*Por favor ingrese el numero de telefono");
        $this->ctx->adiPropiedad("class", "form-control");
        $this->ctx->adiPropiedad("maxlength", "50");
        $this->ctx->adiPropiedad("placeholder", "Numero de telefono");
        $this->ctx->pintar($nokTemplate);
    }
}
