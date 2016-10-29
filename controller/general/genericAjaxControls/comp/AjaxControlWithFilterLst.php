<?php
require_once(Config::PATH . Config::CONTROLLER_LIB . 'PLista.php');
require_once(Config::PATH . Config::CONTROLLER_LIB . 'PCampoTexto.php');
require_once(Config::PATH . Config::BACKEND . 'general/GeneralFac.php');
require_once(Config::PATH . Config::BACKEND . 'general/vos/GeneralSearchVo.php');
require_once(Config::PATH . Config::GENERAL . 'UtilJquery.php');

/**
 * AjaxControlWithFilterLst
 *
 * @autor               GekkoIns.
 * @desarrollado por  Harold Duque
 * @version             1.0
 * @fecha creacion      Oct of 2016
 * @descripcion         Input type text
 */
class AjaxControlWithFilterLst {
     /**
     * @var PLista
     */
    public $lst;
     /**
     * @var PCampoTexto
     */
    public $ctx;
    /**
     * @var GeneralFac
     */
    public $fac;    
    /**
     * @var GeneralSearchVo
     */
    public $generalSearchVo;
    /**
     * @var AjaxControlConfigDataLst
     */
    private $ajaxControlsConfigurationData;
    /**
    * @var UtilJQ
    */
    private $utilJQ;    

    /**
     * @param GeneralSearchVo $generalSearchVo
     * @param AjaxControlConfigDataLst $ajaxControlsConfigurationData
     */
    public function __construct($ajaxControlsConfigurationData,$generalSearchVo) {
        $this->utilJQ = new UtilJquery("ajaxControlWithFilterLst");        
        $this->generalSearchVo = $generalSearchVo;
        $this->ajaxControlsConfigurationData = $ajaxControlsConfigurationData;
        $this->lst = new PLista($this->ajaxControlsConfigurationData->idLst);
        $this->ctx = new PCampoTexto($this->ajaxControlsConfigurationData->idCtx);
        $this->lst->modEsObligatorio(false);
    }
    
     /**
     * @param NokTemplate $nokTemplate
     */
    public function Paint($nokTemplate){
        $this->fac = new GeneralFac();
        $this->fac->GetDataCharacterIni($this->generalSearchVo);
        $this->lst->modErrorMensaje("*Por favor seleccione una opcion");
        $this->ctx->adiPropiedad("class", "form-control");
        $this->ctx->adiPropiedad("placeholder", "Buscar Usuario");
        $this->lst->adiPropiedad("class", "form-control");
        $this->lst->modControlador($this, "GetItem");
        $this->CreateAllControlHtml($nokTemplate);
    }
    
    private function CreateAllControlHtml($nokTemplate){
        $ctxHtml = $this->ctx->conHtm();
        $lstHtml = $this->lst->conHtm();        
        $onClickButton = "
                var usersAjaxControlsConfigurationData = {$this->DataConfigurationComponentAjax()};
                var usersDataQueryComponentAjax = {$this->DataQueryComponentAjax()};
                AjaxControlWithFilterLstFunc(usersAjaxControlsConfigurationData,usersDataQueryComponentAjax);";        
        
        $htmlControl = "
                    <form class=\"form-inline\" role=\"form\">
                        <div id=\"{$this->ajaxControlsConfigurationData->idContainerCtx}\" class=\"form-group\">
                          <label class=\"sr-only\" for=\"inputSearch\">Buscar</label>
                          $ctxHtml
                        </div>
                        <div id=\"{$this->ajaxControlsConfigurationData->idContainerButton}\" class=\"form-group\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Buscar\">
                            <button id=\"{$this->ajaxControlsConfigurationData->idButton}\" type=\"button\" class=\"btn btn-primary btn-block tooltipstered\" onclick=\"{$this->ajaxControlsConfigurationData->idButton}Onclick();\"><span class=\"glyphicon glyphicon-search\"></span></button>  
                        </div>
                        <div id=\"{$this->ajaxControlsConfigurationData->idContainerLst}\" class=\"form-group\">
                          $lstHtml
                        </div>
                    </form>    
                        ";
        $jqueryAjaxFunction = "<script>
                               function {$this->ajaxControlsConfigurationData->idButton}Onclick(){
                                    $onClickButton;
                               }
                               $(\"#{$this->ajaxControlsConfigurationData->idLst}\").change(function(){
                                    {$this->ajaxControlsConfigurationData->funcOnchange}
                               });
                               </script>";
        $htmlControl .= $jqueryAjaxFunction;
        $nokTemplate->asignar("ajaxControlWithFilterLst",  $htmlControl);                        
    }

    public function DataConfigurationComponentAjax() {
        return json_encode(ManageArrays::ObjectToArray($this->ajaxControlsConfigurationData));
    }
    
    public function DataQueryComponentAjax() {
        return json_encode(ManageArrays::ObjectToArray($this->generalSearchVo));
    }         

    /**
     * @return PListaItem
     */
    public function GetItem($itemNum) {
        if ($generalVo = $this->fac->GetVo()) {
            $item = new PListaItem();
            $item->value = $generalVo->id;
            $item->texto = $generalVo->id . "-" . $generalVo->name;            
            return $item;
        }else{
            return false;
        } 
    }    
}