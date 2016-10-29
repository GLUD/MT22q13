<?php

require_once (Config::PATH . Config::CONTROLLER_LIB . 'PTabla2.php');

require_once(Config::PATH . Config::BACKEND . 'user/UserFac.php');
require_once (Config::PATH . Config::BACKEND . 'user/vos/UserVo.php');
require_once (Config::PATH . Config::BACKEND . 'user/vos/UserGroupVo.php');
require_once (Config::PATH . Config::BACKEND . 'role/vos/RoleVo.php');

/**
 * UserTbl
 *
 * @autor               GekkoIns.
 * @desarrollado por 	Harold Duque
 * @version             1.0
 * @fecha creacion      Oct of 2016
 * @descripcion         Table Html
 */
class UserTbl {

    /**
     * @var UserFac
     */
    private $fac;

    /**
     * @var UserVo
     */
    public  $userVo;

    /**
     * @var UserGroupVo
     */
    public  $userGroupVo;

    /**
     * @var Int
     */
    public  $idFilter;

    /**
     * @var boolean
     */
    public  $isReport;

    /**
     * @var Ptabla2
     */
    public  $tbl;

    /**
     * @param PForma $forma
     */
    public function __construct() {
        $this->userVo = new UserVo();
    }

     /**
     * @param NokTemplate $nokTemplate
     */
    public function Paint($nokTemplate) {
        $this->tablaPropiedades();

        $this->IdCol();
        $this->NamesCol();
        $this->LastNamesCol();
        $this->EmailCol();
        $this->DocumentCol();
        $this->RolCol();
        if(!$this->isReport){
            $this->DeleteCol();
            $this->ModifyCol();
        }

        $this->tbl->filasLimitePaginacion = 6;
        $this->tbl->modControlador($this, "conDatos", "conFila");
        $this->tbl->pintar($nokTemplate);
    }
     /**
     * Inicializa las propiedades de la tabla.
     */
    private function tablaPropiedades() {
        $this->tbl = new PTabla2("userTbl");
        $this->tbl->adiPropiedad("id", "userTbl");
        $this->tbl->adiPropiedad("width", "100%");
        $this->tbl->adiPropiedad("class", "table table-striped table-bordered table-hover table-condensed");
        $this->tbl->adiPropiedad("cellpadding", "0");
        $this->tbl->adiPropiedad("cellspacing", "0");
        $this->tbl->adiSinDatosMensaje("No hay usuarios.");
    }

    private function IdCol() {
        $col = new PTablaColumna2();
        $col->adiTitulo("Id");
        $this->StyleColForReport($col,'30px');
        $this->tbl->adiColumna($col);
    }

    private function NamesCol() {
        $col = new PTablaColumna2();
        $col->adiTitulo("Nombres");
        $this->StyleColForReport($col,'80px');
        $this->tbl->adiColumna($col);
    }

    private function LastNamesCol() {
        $col = new PTablaColumna2();
        $col->adiTitulo("Apellidos");
        $this->StyleColForReport($col,'80px');
        $this->tbl->adiColumna($col);
    }

    private function EmailCol() {
        $col = new PTablaColumna2();
        $col->adiTitulo("Email");
        $this->StyleColForReport($col,'170px');
        $this->tbl->adiColumna($col);
    }

    private function DocumentCol() {
        $col = new PTablaColumna2();
        $col->adiTitulo("Documento");
        $this->StyleColForReport($col,'90px');
        $this->tbl->adiColumna($col);
    }

    private function RolCol() {
        $col = new PTablaColumna2();
        $col->adiTitulo("Rol");
        $this->StyleColForReport($col,'60px');
        $this->tbl->adiColumna($col);
    }

    private function DeleteCol() {
        $col = new PTablaColumna2();
        $col->adiTdPropiedad("style", "width:30px;text-align:center;");
        $col->adiTitulo("Eliminar");
        $this->tbl->adiColumna($col);
    }

    private function ModifyCol() {
        $col = new PTablaColumna2();
        $col->adiTdPropiedad("style", "width:30px;text-align:center;");
        $col->adiTitulo("Modificar");
        $this->tbl->adiColumna($col);
    }

     /**
     * @param int $filaNumero
     * @param string $ordens
     * @return int
     */
    public function conDatos() {
        $this->fac = new UserFac();
        if($this->idFilter == null){
            return $this->fac->GetUserAndRolById(null);
        }else{
            $this->userVo->id = $this->idFilter;
            return $this->fac->GetUserAndRolById($this->userVo);
        }
    }

    /**
     * @return array(string)
     */
    public function conFila() {
        if ($this->userGroupVo = $this->fac->GetUserGroupVo()) {
            $id = $this->userGroupVo->userVo->id;
            $fila = array();
            $fila[] = $this->userGroupVo->userVo->id;
            $fila[] = $this->userGroupVo->userVo->names;
            $fila[] = $this->userGroupVo->userVo->lastNames;
            $fila[] = $this->userGroupVo->userVo->email;
            $fila[] = $this->userGroupVo->userVo->documentNumber;
            $fila[] = $this->userGroupVo->roleVo->name;
            if(!$this->isReport){
                $fila[] = "<button data-toggle=\"tooltip\" data-placement=\"top\" title=\"Eliminar\" data-placement=\"top\" onclick=\"DeleteData($id)\" class=\"btn btn-danger\"  type=\"button\" name=\"boton\" id=\"crearBtn\">
                            <span class=\"glyphicon glyphicon-minus-sign\"></span>
                           </button>";
                $fila[] = "<button data-toggle=\"tooltip\" title=\"Modificar\" onclick=\"UpdateData($id);\" class=\"btn btn-warning\" type=\"button\" name=\"boton\" id=\"crearBtn\">
                            <span class=\"glyphicon glyphicon-edit\"></span>
                           </button>";
            }
            $fila['id'] = $id;
            return $fila;
        } else{
            return false;
        }
    }

    public function StyleColForReport($col,$width){
        if($this->isReport){
             $col->adiTdPropiedad("style", "font-size: 10px;
                                            color: #003E4C;
                                            background-color: #ffffff;
                                            text-align:center;
                                            border-width: 1px;
                                            padding: 0px 5px 0px 5px;
                                            border-style: solid;
                                            border-color: #037E8C;
                                            border-left-color: #037E8C;
                                            border-bottom-color: #037E8C;
                                            border-right-color:  #037E8C;
                                            vertical-align:middle;
                                            width:$width");
             $col->adiTdTituloPropiedad("style", "font-size: 12px;
                                                  color: #003E4C;
                                                  background-color: #ffffff;
                                                  border-width: 1px;
                                                  padding: 10px;
                                                  border-style: solid;
                                                  border-color: #037E8C;
                                                  border-left-color: #037E8C;
                                                  border-bottom-color: #037E8C;
                                                  border-right-color: #037E8C;
                                                  text-align: center;
                                                  vertical-align:middle;
                                                  width:$width");

        }
    }
}
