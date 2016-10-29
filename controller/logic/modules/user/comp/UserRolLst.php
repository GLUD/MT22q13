<?php
require_once(Config::PATH . Config::CONTROLLER_LIB . 'PLista.php');
require_once(Config::PATH . Config::BACKEND . 'role/RoleFac.php');

/**
 * UserRolLst
 *
 * @autor               GekkoIns.
 * @desarrollado por 	Harold Duque
 * @version             1.0
 * @fecha creacion      Oct of 2016
 * @descripcion         Input type text
 */
class UserRolLst {
     /**
     * @var PLista
     */
    public $lst;
    /**
     * @var RoleFac
     */
    public $fac;

    /**
     * @param PForma $forma
     */
    public function __construct($forma) {
        $this->lst = new PLista("userRolLst");
        $this->lst->modEsObligatorio(true);
        $forma->adiElemento($this->lst);
    }

     /**
     * @param NokTemplate $nokTemplate
     */
    public function Paint($nokTemplate){
        $this->fac = new RoleFac();
        $this->fac->GetRoleById(null);
        $this->lst->modErrorMensaje("*Por favor seleccion el tipo de usuario");
        $this->lst->adiPropiedad("class", "form-control");
        $this->lst->modControlador($this, "GetItem");
        $this->lst->pintar($nokTemplate);
    }

    /**
     * @return PListaItem
     */
    public function GetItem() {
        if ($roleVo = $this->fac->GetRoleVo()) {

            $item = new PListaItem();
            $item->value = $roleVo->id;
            $item->texto = $roleVo->name;

            return $item;
        }else{
            return false;
        }
    }
}
