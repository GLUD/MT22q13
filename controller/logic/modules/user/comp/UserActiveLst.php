<?php
require_once(Config::PATH . Config::CONTROLLER_LIB . 'PLista.php');

/**
 * UserActiveLst
 *
 * @autor               GekkoIns.
 * @desarrollado por 	Harold Duque
 * @version             1.0
 * @fecha creacion      Oct of 2016
 * @descripcion         Input type text
 */
class UserActiveLst {
    
    /**
     * @var PLista
     */
    public $lst;
    
    /**
     * @var GeneralFac
     */
    public $fac;    

    /**
     * @param PForma $forma
     */
    public function __construct($forma) {
        $this->lst = new PLista("userActiveLst");
        $this->lst->modEsObligatorio(true);
        $forma->adiElemento($this->lst);
    }
    
     /**
     * @param NokTemplate $nokTemplate
     */
    public function Paint($nokTemplate){
        $this->lst->modErrorMensaje("*Por favor seleccione una opcion");
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
            $item->value = 0;
            $item->texto = "NO";

            return $item;
        } else if ($itemNum == 2) {
            $item = new PListaItem();
            $item->value = 1;
            $item->texto = "SI";

            return $item;
        } else {
            return false;
        }
    }    
    
}
