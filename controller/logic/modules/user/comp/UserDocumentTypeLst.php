<?php
require_once(Config::PATH . Config::CONTROLLER_LIB . 'PLista.php');

/**
 * UserDocumentTypeLst
 *
 * @autor               GekkoIns.
 * @desarrollado por 	Harold Duque
 * @version             1.0
 * @fecha creacion      Oct of 2016
 * @descripcion         Input type text
 */
class UserDocumentTypeLst {
     /**
     * @var PLista
     */
    public $lst;
    /**
     * @var UserFac
     */
    public $fac;

    /**
     * @param PForma $forma
     */
    public function __construct($forma) {
        $this->lst = new PLista("userDocumentTypeLst");
        $this->lst->modEsObligatorio(true);
        $forma->adiElemento($this->lst);
    }

     /**
     * @param NokTemplate $nokTemplate
     */
    public function Paint($nokTemplate){
        $this->lst->modErrorMensaje("*Por favor seleccion el tipo de documento");
        $this->lst->adiPropiedad("class", "form-control");
        $this->lst->modControlador($this, "GetItem");
        $this->lst->pintar($nokTemplate);
    }

    /**
     * @return PListaItem
     */
    public function GetItem($itemNum) {
        if ($itemNum == 1) {
            $item = new PListaItem();
            $item->value = "CC";
            $item->texto = "CC";

            return $item;
        } else if ($itemNum == 2) {
            $item = new PListaItem();
            $item->value = "CE";
            $item->texto = "CE";

            return $item;
        } else if ($itemNum == 3) {
            $item = new PListaItem();
            $item->value = "TI";
            $item->texto = "TI";

            return $item;
        } else if ($itemNum == 4) {
            $item = new PListaItem();
            $item->value = "Otro";
            $item->texto = "Otro";

            return $item;
        }
        else {
            return false;
        }
    }
}
