<?php

require_once(Config::PATH . Config::GENERAL . 'GeneralMenu.php');
require_once(Config::PATH . Config::GENERAL . 'AdminSession.php');

require_once (Config::PATH . Config::BACKEND . 'module/ModuleFac.php');
require_once (Config::PATH . Config::BACKEND . 'module/vos/ModuleVo.php');
require_once (Config::PATH . Config::BACKEND . 'userModules/UserModulesFac.php');
require_once (Config::PATH . Config::BACKEND . 'userModules/vos/UserModulesVo.php');

/**
 * UpdateUserModulesView
 *
 * class responsible for loading the html
 *
 * @author		Harold D Duque C
 * @version		1.0
 * @since		Oct of 2016
 */
class UpdateUserModulesView {

    /**
     * Class for paint the html.
     *
     * @var NokTemplate
     */
    private $nokTemplate;

     /**
     *
     * @var GeneralMenu
     */
    private $generalMenu;

    /**
     *
     * @var AdminSession
     */
    private $adminSession;

    /**
     * @var UserVo
     */
    private $userVo;

    /**
     * @var String
     */
    private $htmlControls;

    /**
     * @var String
     */
    private $jqueryControls;

    /**
     * Constructora.
     *
     * @param NokTemplate $nokTemplate
     */
    public function __construct($nokTemplate) {
        $this->nokTemplate = $nokTemplate;
        $this->generalMenu = new GeneralMenu($this->nokTemplate);
        $this->adminSession = new AdminSession();
        //$this->adminSession->ValidateSession();
        //$this->adminSession->PaintNameUser($this->nokTemplate);
        $this->ValidateLoadData();
    }

    private function ValidateLoadData(){
        if (isset($_GET) && !empty($_GET)) {
            $this->GetUserData($_GET['id']);
            $this->PaintDataUser();
            $this->Paint();
        }
    }

    private function GetUserData($id) {
        $userVo = new UserVo();
        $userVo->id = $_GET['id'];
        $userFac = new UserFac();
        $userFac->GetUserById($userVo);
        if($this->userVo = $userFac->GetUserVo()){
           $this->GetUserModules($userVo);
        }
    }

    private function GetUserModules($userVo) {
      $userModulesVo = new UserModulesVo();
      $userModulesVo->idUser = $userVo->id;
      $userModulesFac = new UserModulesFac();
      $userModulesFac->GetUserModulesByIdUser($userModulesVo);
      while($userModulesVo = $userModulesFac->GetUserModulesVo()){
        $this->CreateHtmlControls($userModulesVo);
        $this->CreateJqueryControls($userModulesVo);
      }
    }

    public function CreateHtmlControls($userModulesVo) {
      $htmlControl = "";
      if($userModulesVo->state == 1){
        $htmlControl = "<input id=\"toggle-{$userModulesVo->id}\" name=\"toggle-{$userModulesVo->id}\" type=\"checkbox\" data-onstyle=\"success\" data-width=\"100%\" data-height=\"35\" checked=\"checked\">";
      }else {
        $htmlControl = "<input id=\"toggle-{$userModulesVo->id}\" name=\"toggle-{$userModulesVo->id}\" type=\"checkbox\" data-onstyle=\"success\" data-width=\"100%\" data-height=\"35\">";
      }
      $this->htmlControls .= "
      <div class=\"form-group\">
        <label>Modulo {$this->GetNameModule($userModulesVo)}:</label>
        <div>
          $htmlControl
        </div>
        <div id=\"togglemensaje-{$userModulesVo->id}\" ></div>
      </div>
      ";
    }

    public function CreateJqueryControls($userModulesVo) {
      $url = Config::REDIRECTION."ajaxUpdateUserModules";
      $this->jqueryControls .= "
        <script>
          var state = null;
          $('#toggle-{$userModulesVo->id}').bootstrapToggle();
          $('#toggle-{$userModulesVo->id}').change(function() {
            if($( '#toggle-{$userModulesVo->id}' ).prop( \"checked\" ))	{
              state = 1;
            }else{
              state = 0;
            }
            AjaxControlJQ($userModulesVo->id,state,'$url');
          });
        </script>
      ";
    }

    private function CreateFunctionJQAjax() {
      $ajaxControlJQ = "
        <script>
          function AjaxControlJQ (id,state,url){

              var parameters = {
          				\"id\" : id,
                  \"state\" : state,
                  \"url\" : url
          		};
          		$.ajax({
          				data:  parameters,
          				url:   url,
          				type:  'post',
                  success:  function (response) {
                      $(\"#togglemensaje-\"+id).html(response);
                  }
          		});
          }
        </script>
      ";
      return $ajaxControlJQ;
    }

    private function GetNameModule($userModulesVo) {
      $name = "";
      $moduleVo = new ModuleVo();
      $moduleVo->id = $userModulesVo->idModule;
      $moduleFac = new ModuleFac();
      $moduleFac->GetModuleById($moduleVo);
      if($moduleVo = $moduleFac->GetModuleVo()){
        $name = $moduleVo->name;
      }
      return $name;
    }

    private function PaintDataUser() {
      $this->htmlControls .=$this->jqueryControls;
      $this->nokTemplate->asignar("fullName",$this->userVo->fullName);
      $this->nokTemplate->asignar("htmlControls",$this->htmlControls);
      $this->nokTemplate->asignar("jquery",$this->CreateFunctionJQAjax());
      $this->nokTemplate->asignar("document",$this->userVo->documentType." : ".$this->userVo->documentNumber);
    }

    /**
     * Pinta la vista.
     *
     */
    private function Paint() {
        $this->Cargar();
        $this->Imprimir();
    }

    /**
     * Carga los html.
     *
     */
    private function Cargar() {
        $this->nokTemplate->cargar("template", "html/template.html");
        $this->nokTemplate->cargar("content", "html/userModules/userModulesView.html");
    }

    /**
     * Imprime la vista.
     *
     */
    private function Imprimir() {
        $this->nokTemplate->expandir("content", "content");
        $this->nokTemplate->expandir("FINAL", "template");
        $this->nokTemplate->imprimir("FINAL");
    }


}
