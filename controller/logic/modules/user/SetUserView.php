<?php
require_once(Config::PATH . Config::GENERAL . 'GeneralMenu.php');
require_once(Config::PATH . Config::GENERAL . 'UtilJquery.php');
require_once(Config::PATH . Config::GENERAL . 'AdminSession.php');

require_once (Config::PATH . Config::BACKEND . 'module/ModuleFac.php');
require_once (Config::PATH . Config::BACKEND . 'module/vos/ModuleVo.php');

require_once (Config::PATH . Config::BACKEND . 'userModules/UserModulesFac.php');
require_once (Config::PATH . Config::BACKEND . 'userModules/vos/UserModulesVo.php');

require_once (Config::PATH . Config::BACKEND . 'user/UserFac.php');
require_once (Config::PATH . Config::BACKEND . 'access/AccessFac.php');
require_once (Config::PATH . Config::BACKEND . 'general/GeneralFac.php');
require_once(Config::PATH . Config::BACKEND . 'user/vos/UserVo.php');
require_once(Config::PATH . Config::BACKEND . 'user/vos/UserGroupVo.php');
require_once(Config::PATH . Config::BACKEND . 'general/vos/GeneralVo.php');
require_once(Config::PATH . Config::BACKEND . 'general/vos/GeneralSearchVo.php');
require_once(Config::PATH . Config::GENERAL . 'genericAjaxControls/dataConfigControls/AjaxControlConfigDataLst.php');
require_once(Config::PATH . Config::GENERAL . 'genericAjaxControls/dataConfigControls/AjaxControlConfigDataTbl.php');

require_once(Config::PATH . Config::CONTROLLER_LIB . 'PForma.php');
require_once(Config::PATH . Config::GENERAL . 'genericControls/OptionInputForm.php');
require_once(Config::PATH . Config::GENERAL . 'genericControls/IdDataInputForm.php');
require_once(Config::PATH . Config::MODULES . 'user/comp/UserNamesCtx.php');
require_once(Config::PATH . Config::MODULES . 'user/comp/UserLastNamesCtx.php');
require_once(Config::PATH . Config::MODULES . 'user/comp/UserEmailCtx.php');
require_once(Config::PATH . Config::MODULES . 'user/comp/UserCellNumberCtx.php');
require_once(Config::PATH . Config::MODULES . 'user/comp/UserTelePhoneNumberCtx.php');
require_once(Config::PATH . Config::MODULES . 'user/comp/UserDocumentTypeLst.php');
require_once(Config::PATH . Config::MODULES . 'user/comp/UserDocumentNumberCtx.php');
require_once(Config::PATH . Config::MODULES . 'user/comp/UserRolLst.php');
require_once(Config::PATH . Config::GENERAL . 'genericAjaxControls/comp/AjaxControlWithFilterLst.php');

/**
 * SetUserView
 *
 * class responsible for Controlling the html
 *
 * @author		Harold D Duque C
 * @version		1.0
 * @since		Oct of 2016
 */
class SetUserView {

    /**
     * @var PForma
     */
    private $form;

    /**
     * @var NokTemplate
     */
    private $nokTemplate;

    /**
     * @var AdminSession
     */
    private $adminSession;

    /**
     * @var GeneralMenu
     */
    private $generalMenu;

    /**
     * @var UtilJQ
     */
    private $utilJQ;

    /**
     * @var UsersFac
     */
    private $usersFac;

    /**
     * @var GeneralSearchVo
     */
    private $generalSearchVo;

    /**
     * @var AjaxControlConfigDataLst
     */
    private $ajaxControlConfigDataLst;

    /**
     * @var AjaxControlConfigDataTbl
     */
    private $ajaxControlConfigDataTbl;


    /**
     * @param NokTemplate $nokTemplate
     */
    public function __construct($nokTemplate) {
        $this->nokTemplate = $nokTemplate;
        $this->utilJQ = new UtilJquery("setUser");
        $this->adminSession = new AdminSession();
        $this->generalMenu = new GeneralMenu($this->nokTemplate);
        //$this->adminSession->ValidateSession();
        $this->AddNamesControlsToArrayJquery();
        $this->CreateComponents();
        $this->ValidateForm();
        //$this->adminSession->PaintNameUser($this->nokTemplate);
        $this->Paint();
    }

    private function AddNamesControlsToArrayJquery(){
        $this->utilJQ->AddNameControlForm("userNamesCtx");
        $this->utilJQ->AddNameControlForm("userLastNamesCtx");
        $this->utilJQ->AddNameControlForm("userDocumentTypeLst");
        $this->utilJQ->AddNameControlForm("userDocumentNumberCtx");
        $this->utilJQ->AddNameControlForm("userCellNumberCtx");
        $this->utilJQ->AddNameControlForm("userTelePhoneNumberCtx");
        $this->utilJQ->AddNameControlForm("userEmailCtx");
        $this->utilJQ->AddNameControlForm("userRolLst");
        $this->CreateObjectsControlsConfigData();
        $this->AddFunctionsDataJavaScript();
    }

    private function AddFunctionsDataJavaScript(){
        $redirection = Config::REDIRECTION;
        $this->utilJQ->html = $this->utilJQ->PopPupConfirmationHtml("alerta", "Desea eliminar el registro?");
        $GenerateExcelFJQ = "
                        function GenerateExcelFJQ(){
                           var idFilter = GetValueSelect('{$this->ajaxControlConfigDataLst->idLst}');
                           location.href='?view=manajerReportExcelTbl&idTbl={$this->ajaxControlConfigDataTbl->idTbl}&idFilter='+idFilter
                        }";
        $GeneratePdfFJQ = "
                        function GeneratePdfFJQ(){
                           var idFilter = GetValueSelect('{$this->ajaxControlConfigDataLst->idLst}');
                           window.open('?view=manajerReportPdfTbl&idTbl={$this->ajaxControlConfigDataTbl->idTbl}&idFilter='+idFilter,'_blank');
                        }";
        $DeleteDataFJQ = "
                        function DeleteData(id){
                            SetValueToInputText(\"optionInputForm\",2);
                            SetValueToInputText(\"idDataInputForm\",id);
                            $('#popupConfirmation').bPopup({easing: 'easeOutBack',
                                        speed: 450,
                                        transition: 'slideDown'});
                        }";

        $UpdateDataFJQ = "function UpdateData(id){
                            SetValueToInputText(\"optionInputForm\",3);
                            SetValueToInputText(\"idDataInputForm\",id);
                            document.form.submit();
                        }";
        $GeneratePrintFJQ = "
                        function GeneratePrintFJQ(){
                          var objeto = document.getElementById('{$this->ajaxControlConfigDataTbl->idContainerTbl}');  //obtenemos el objeto a imprimir
                          var ventana = window.open('', '_blank');  //abrimos una ventana vacía nueva
                          ventana.document.write(objeto.innerHTML);  //imprimimos el HTML del objeto en la nueva ventana
                          ventana.document.close();  //cerramos el documento
                          ventana.print();  //imprimimos la ventana
                          ventana.close();  //cerramos la ventana
                        }";
        $RedirectToPermissionsUserFJQ = "
                        function RedirectToPermissionsUser(id){
                            window.location.href = \"$redirection\"+\"updateUserModules\"+\"&id=\"+id;
                        }";
        $RedirectToConfigUserTimeFJQ = "
                        function RedirectToConfigUserTime(id){
                            window.location.href = \"$redirection\"+\"configUserTime\"+\"&id=\"+id;
                        }";

        $this->utilJQ->AddFunctionJavaScript($GenerateExcelFJQ);
        $this->utilJQ->AddFunctionJavaScript($GeneratePdfFJQ);
        $this->utilJQ->AddFunctionJavaScript($DeleteDataFJQ);
        $this->utilJQ->AddFunctionJavaScript($UpdateDataFJQ);
        $this->utilJQ->AddFunctionJavaScript($GeneratePrintFJQ);
        $this->utilJQ->AddFunctionJavaScript($RedirectToPermissionsUserFJQ);
        $this->utilJQ->AddFunctionJavaScript($RedirectToConfigUserTimeFJQ);
        $this->utilJQ->PaintJQ($this->nokTemplate);
    }

    private function CreateObjectsControlsConfigData() {
        $this->AjaxDataConfigComponentTbl();
        $this->AjaxDataConfigComponentLst();
        $this->AjaxDataQueryComponentLst();
    }

    private function AjaxDataConfigComponentTbl() {
        $this->ajaxControlConfigDataTbl = new AjaxControlConfigDataTbl();
        $this->ajaxControlConfigDataTbl->idFilter = null;
        $this->ajaxControlConfigDataTbl->idTbl = "userTbl";
        $this->ajaxControlConfigDataTbl->idContainerTbl = "userTblConten";
        $this->ajaxControlConfigDataTbl->loadUrl = Config::REDIRECTS. Config::GENERAL ."index.php?view=ajaxControlTbl";
    }

    public function AjaxDataConfigComponentLst() {
        $this->ajaxControlConfigDataLst = new AjaxControlConfigDataLst();
        $this->ajaxControlConfigDataLst->idCtx = "userSearchCtx";
        $this->ajaxControlConfigDataLst->idLst = "userSearchLst";
        $this->ajaxControlConfigDataLst->idButton = "userSearchButton";
        $this->ajaxControlConfigDataLst->idContainerGeneral = "userSearchControl";
        $this->ajaxControlConfigDataLst->idContainerButton = "userSearchContainerButton";
        $this->ajaxControlConfigDataLst->idContainerCtx = "userSearchContainerCtx";
        $this->ajaxControlConfigDataLst->idContainerLst = "userSearchContainerLst";
        $this->ajaxControlConfigDataLst->loadUrl = Config::REDIRECTS. Config::GENERAL ."index.php?view=ajaxControlWithFilterLst";
        $funcOnchange = "
                        var idFilter = GetValueSelect('{$this->ajaxControlConfigDataLst->idLst}');
                        var ajaxControlConfigDataTbl = ".ManageArrays::ObjectToJson($this->ajaxControlConfigDataTbl).";
                        ajaxControlConfigDataTbl.idFilter = idFilter;
                        AjaxControlTbl(ajaxControlConfigDataTbl);";
        $this->ajaxControlConfigDataLst->funcOnchange = $funcOnchange;
    }

    public function AjaxDataQueryComponentLst() {
        $this->generalSearchVo = new GeneralSearchVo();
        $this->generalSearchVo->nameTable = "user";
        $this->generalSearchVo->nameFieldId = "id";
        $this->generalSearchVo->nameField = "names";
        $this->generalSearchVo->isSearchFieldString = "true";
        $this->generalSearchVo->searchField = "names";
        $this->generalSearchVo->valueSearchField = "";
    }

    private function CreateComponents() {
        $this->form = new PForma("form");
        $this->optionInputForm = new OptionInputForm($this->form);
        $this->idDataInputForm = new IdDataInputForm($this->form);

        $this->userNamesCtx = new UserNamesCtx($this->form);
        $this->userLastNamesCtx = new UserLastNamesCtx($this->form);
        $this->userEmailCtx = new UserEmailCtx($this->form);
        $this->userDocumentTypeLst = new UserDocumentTypeLst($this->form);
        $this->userDocumentNumberCtx = new UserDocumentNumberCtx($this->form);
        $this->userCellNumberCtx = new UserCellNumberCtx($this->form);
        $this->userTelePhoneNumberCtx = new UserTelePhoneNumberCtx($this->form);
        $this->userRolLst = new UserRolLst($this->form);

        $this->CreateComponentsAjax();
    }

    private function CreateComponentsAjax(){
        $usersSearchContainerHtml = "
                                <div id=\"userSearchControl\">
                                </div>
                                ";
        $this->nokTemplate->asignar("userSearchControl", $usersSearchContainerHtml);
        $usersSearchFunctionJQ = "
                var ajaxControlConfigDataLst = ".ManageArrays::ObjectToJson($this->ajaxControlConfigDataLst).";
                var dataQueryComponentAjax = ".ManageArrays::ObjectToJson($this->generalSearchVo).";
                AjaxControlWithFilterLstFunc(ajaxControlConfigDataLst,dataQueryComponentAjax);";
        $usersTblFunctionJQ = "
                var ajaxControlConfigDataTbl = ".ManageArrays::ObjectToJson($this->ajaxControlConfigDataTbl).";
                AjaxControlTbl(ajaxControlConfigDataTbl);";
        $this->utilJQ->AddFunctionJquery($usersSearchFunctionJQ);
        $this->utilJQ->AddFunctionJquery($usersTblFunctionJQ);
        $this->Jquery();
    }

    private function ValidateForm() {
        $post = $this->form->validarSinBoton();
        if ($post) {
            $this->UseDataFromThePostback($post);
        }
    }

    private function UseDataFromThePostback($post) {
        $option = $post['optionInputForm'];
        $idDataInputForm = $post['idDataInputForm'];

        if($option != ""){
            if($option == 1){ // registra
                $this->SetData($post);
            }else if($option == 2){ // elimina un registro
                $this->DeleteData($idDataInputForm);
            }else if($option == 3){ // Carga los datos en los controles
                $this->SetDataInControls($idDataInputForm);
            }else if($option == 4){
                $this->UpdateData($idDataInputForm, $post);
            }
        }
    }

    private function SetData($post){
        $userVo = new UserVo();

        $userVo->names = $post[$this->utilJQ->GetNameControlForIndex(0)];
        $userVo->lastNames = $post[$this->utilJQ->GetNameControlForIndex(1)];
        $userVo->fullName = $userVo->names." ".$userVo->lastNames;
        $userVo->documentType = $post[$this->utilJQ->GetNameControlForIndex(2)];
        $userVo->documentNumber = $post[$this->utilJQ->GetNameControlForIndex(3)];
        $userVo->cellPhoneNumber = $post[$this->utilJQ->GetNameControlForIndex(4)];
        $userVo->telePhoneNumber = $post[$this->utilJQ->GetNameControlForIndex(5)];
        $userVo->email = $post[$this->utilJQ->GetNameControlForIndex(6)];
        $userVo->idRole = $post[$this->utilJQ->GetNameControlForIndex(7)];

        if($this->ValidateExistence($userVo->documentNumber, false)){
            $this->utilJQ->AddFunctionJquery($this->utilJQ->ShowPopUpWithRedirectionFJQ("alerta", "El Usuario ya existe"));
        }else{
            $this->userFac = new UserFac();
            $idUser = $this->userFac->SetUser($userVo);
            $userVo->id = $idUser;
            $this->SetUserAccess($userVo);
            $this->SetModulesByUsers($userVo);
            $this->utilJQ->AddFunctionJquery($this->utilJQ->ShowPopUpWithRedirectionFJQ("exito", "Usuario registrado con exito!"));
        }
        $this->Jquery();
    }

    private function SetUserAccess($userVo) {
        $accessVo = new AccessVo();
        $accessVo->idUser = $userVo->id;
        $accessVo->nickName = md5($userVo->documentNumber);
        $accessVo->password = md5($userVo->documentNumber);
        $accessVo->state = 0;
        $accessFac = new AccessFac();
        $accessFac->SetAccess($accessVo);
    }

    private function SetModulesByUsers($userVo) {
      $moduleVo = new ModuleVo();
      $moduleFac = new ModuleFac();
      $moduleFac->GetModuleById(null);
      while($moduleVo = $moduleFac->GetModuleVo()){
        $this->SetUserModules($userVo,$moduleVo);
      }
    }

    private function SetUserModules($userVo,$moduleVo) {
        $userModulesVo = new UserModulesVo();
        $userModulesVo->idUser = $userVo->id;
        $userModulesVo->idModule = $moduleVo->id;
        $userModulesVo->state = 1;
        $userModulesFac = new UserModulesFac();
        $userModulesFac->SetUserModules($userModulesVo);
    }

    private function DeleteData($id){
          $userVo = new UserVo();
          $userVo->id = $id;
          $this->userFac = new UserFac();

          $this->userFac->DeleteUser($userVo);
          $this->utilJQ->AddFunctionJquery($this->utilJQ->ShowPopUpWithRedirectionFJQ("exito", "Usuario eliminado con exito!"));
          $this->Jquery();
          /*
          if($this->adminSession->GetUserVo->$userVo->id === $userVo->id)
          {
            $this->adminSession->DestroySesion();
          }*/
    }

    private function SetDataInControls($id){
        $userVo = new UserVo();
        $userVo->id = $id;
        $this->userFac = new UserFac();
        $this->userFac->GetUserById($userVo);
        $userVo = $this->userFac->GetUserVo();
        $this->utilJQ->ResetFunctionsJquery();

        $SetInputsDataFJQ = " SetValueToInputText('{$this->utilJQ->GetNameControlForIndex(0)}','{$userVo->names}');
                              SetValueToInputText('{$this->utilJQ->GetNameControlForIndex(1)}','{$userVo->lastNames}');
                              SetValueToSelect('{$this->utilJQ->GetNameControlForIndex(2)}','{$userVo->documentType}',true);
                              SetValueToInputText('{$this->utilJQ->GetNameControlForIndex(3)}','{$userVo->documentNumber}');
                              SetValueToInputText('{$this->utilJQ->GetNameControlForIndex(4)}','{$userVo->cellPhoneNumber}');
                              SetValueToInputText('{$this->utilJQ->GetNameControlForIndex(5)}','{$userVo->telePhoneNumber}');
                              SetValueToInputText('{$this->utilJQ->GetNameControlForIndex(6)}','{$userVo->email}');
                              SetValueToSelect('{$this->utilJQ->GetNameControlForIndex(7)}','{$userVo->idRole}',true);
                              HideElement('registrarBtn');
                              ShowElement('modificarBtn');
                            ";
        $this->utilJQ->AddFunctionJquery($SetInputsDataFJQ);
        $this->utilJQ->PaintJQ($this->nokTemplate);

    }

    private function UpdateData($id,$post){
      $userVo = new UserVo();

      $userVo->id = $id;
      $userVo->names = $post[$this->utilJQ->GetNameControlForIndex(0)];
      $userVo->lastNames = $post[$this->utilJQ->GetNameControlForIndex(1)];
      $userVo->fullName = $userVo->names." ".$userVo->lastNames;
      $userVo->documentType = $post[$this->utilJQ->GetNameControlForIndex(2)];
      $userVo->documentNumber = $post[$this->utilJQ->GetNameControlForIndex(3)];
      $userVo->cellPhoneNumber = $post[$this->utilJQ->GetNameControlForIndex(4)];
      $userVo->telePhoneNumber = $post[$this->utilJQ->GetNameControlForIndex(5)];
      $userVo->email = $post[$this->utilJQ->GetNameControlForIndex(6)];
      $userVo->idRole = $post[$this->utilJQ->GetNameControlForIndex(7)];

      $this->userFac = new UserFac();
      $this->userFac->UpdateUser($userVo);
      $this->utilJQ->AddFunctionJquery($this->utilJQ->ShowPopUpWithRedirectionFJQ("exito", "Usuario modificado con exito!"));
      $this->Jquery();
    }

    private function ValidateExistence($searchField, $isSearchFieldString){
        $generalFac = new GeneralFac();
        $generalFac->IsExistData("user", "documentNumber", $searchField, $isSearchFieldString);
        $result = $generalFac->GetVo();
        return $result;
    }

    private function Jquery(){
        $this->utilJQ->AddFunctionJquery($this->utilJQ->ResetControlsFormFJQ());
        $this->utilJQ->PaintJQ($this->nokTemplate);
    }

    private function Paint() {
        $this->LoadHTML();
        $this->PaintComponents();
        $this->PrintHTML();
    }

    private function PaintComponents() {
        $this->form->pintar($this->nokTemplate);
        $this->optionInputForm->pintar($this->nokTemplate);
        $this->idDataInputForm->pintar($this->nokTemplate);
        $this->userNamesCtx->Paint($this->nokTemplate);
        $this->userLastNamesCtx->Paint($this->nokTemplate);
        $this->userDocumentTypeLst->Paint($this->nokTemplate);
        $this->userDocumentNumberCtx->Paint($this->nokTemplate);
        $this->userCellNumberCtx->Paint($this->nokTemplate);
        $this->userTelePhoneNumberCtx->Paint($this->nokTemplate);
        $this->userEmailCtx->Paint($this->nokTemplate);
        $this->userRolLst->Paint($this->nokTemplate);
    }

    private function LoadHTML() {
        $this->nokTemplate->cargar("template", "html/template.html");
        $this->nokTemplate->cargar("content", "html/user/setUserView.html");
    }

    private function PrintHTML() {
        $this->nokTemplate->expandir("content", "content");
        $this->nokTemplate->expandir("FINAL", "template");
        $this->nokTemplate->imprimir("FINAL");
    }
}
