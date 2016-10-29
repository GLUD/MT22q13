<?php

require_once(Config::PATH . Config::GENERAL . 'GeneralMenu.php');
require_once(Config::PATH . Config::GENERAL . 'UtilJquery.php');
require_once(Config::PATH . Config::GENERAL . 'AdminSession.php');

require_once (Config::PATH . Config::BACKEND . 'general/GeneralFac.php');
require_once(Config::PATH . Config::BACKEND . 'general/vos/GeneralVo.php');

require_once(Config::PATH . Config::BACKEND . 'access/AccessFac.php');
require_once(Config::PATH . Config::BACKEND . 'access/vos/AccessVo.php');
require_once(Config::PATH . Config::BACKEND . 'user/UserFac.php');
require_once(Config::PATH . Config::BACKEND . 'user/vos/UserVo.php');

require_once(Config::PATH . Config::CONTROLLER_LIB . 'PForma.php');
require_once(Config::PATH . Config::GENERAL . 'genericControls/OptionInputForm.php');
require_once(Config::PATH . Config::GENERAL . 'genericControls/IdDataInputForm.php');
require_once(Config::PATH . Config::MODULES . 'access/comp/AccessUserNameCtx.php');
require_once(Config::PATH . Config::MODULES . 'access/comp/AccessPassCtx.php');
require_once(Config::PATH . Config::MODULES . 'access/comp/AccessConfPassCtx.php');

/**
 * SetAccessView
 *
 * class responsible for Controlling the html
 *
 * @author		Andres F Angarita E
 * @version		1.0
 * @since		Oct of 2016
 */
class SetAccessView {

    /**
     * @var PForma
     */
    private $form;

    /**
     * @var AccessFac
     */
    private $accessFac;

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
     * @param NokTemplate $nokTemplate
     */
    public function __construct($nokTemplate) {
        $this->nokTemplate = $nokTemplate;
        $this->utilJQ = new UtilJquery("setAccess");
        $this->generalMenu = new GeneralMenu($this->nokTemplate);
        $this->adminSession = new AdminSession();
        $this->AddNamesControlsToArrayJquery();
        $this->CreateComponents();
        $this->ValidateForm();
        $this->Paint();
    }

    private function AddNamesControlsToArrayJquery() {
        $this->utilJQ->AddNameControlForm("accessUserNameCtx");
        $this->utilJQ->AddNameControlForm("accessPassCtx");
        $this->utilJQ->AddNameControlForm("accessConfPassCtx");
        $this->AddFunctionsDataJavaScript();
    }

    private function AddFunctionsDataJavaScript() {
        $userVo = $this->adminSession->GetUserVo();

        $LoadDataOnStartFJQ = "
                        function LoadDataOnStart(id){
                            SetValueToInputText(\"idDataInputForm\",id);
                            SetValueToInputText('{$this->utilJQ->GetNameControlForIndex(0)}','{$this->GetDataForInsertInControls('id')}');                            
                            }
                        LoadDataOnStart($userVo->id);
                         
                        ";
        $UpdateDataFJQ = "
                        function UpdateData(id){
                            SetValueToInputText(\"optionInputForm\",4);
                            SetValueToInputText(\"idDataInputForm\",id);
                            document.form.submit();
                        }
                        ";
        $this->utilJQ->AddFunctionJavaScript($LoadDataOnStartFJQ);
        $this->utilJQ->AddFunctionJavaScript($UpdateDataFJQ);
        $this->utilJQ->PaintJQ($this->nokTemplate);
    }

    private function CreateComponents() {
        $this->form = new PForma("form");
        $this->optionInputForm = new OptionInputForm($this->form);
        $this->idDataInputForm = new IdDataInputForm($this->form);
        $this->accessUserNameCtx = new AccessUserNameCtx($this->form);
        $this->accessPassCtx = new AccessPassCtx($this->form);
        $this->accessConfPassCtx = new AccessConfPassCtx($this->form);
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
        if ($option != "") {
             if ($option == 4) {
                $this->UpdateData($idDataInputForm, $post);
            }
        }
    }

    private function UpdateData($id, $post) {
        $accessVo = new AccessVo();
        $this->accessFac = new AccessFac();

        $accessVo->id = $id;
        $accessVo->nickName = md5($post[$this->utilJQ->GetNameControlForIndex(0)]);
        $accessVo->password = md5($post[$this->utilJQ->GetNameControlForIndex(1)]);
        $accessVo->state = 1;

        if ($this->ValidateExistanceOfUser($accessVo)) {
            $this->utilJQ->AddFunctionJquery($this->utilJQ->ShowPopUpWithRedirectionFJQ("alerta", "$id"));
        } else {
            $this->accessFac->UpdateAccess($accessVo);
            $this->utilJQ->AddFunctionJquery($this->utilJQ->ShowPopUpWithRedirectionFJQ("exito", "Historia modificada con exito!"));
        }
        $this->Jquery();
    }

    private function GetDataForInsertInControls($id) {
        $accessVo = new AccessVo();
        $accessVo->id = $id;
        $this->accessFac = new AccessFac();
        $this->accessFac->GetAccessById($accessVo);
        $accessVo = $this->accessFac->GetAccessVo();
        return $accessVo->nickName;
    }
    
    private function ValidateExistanceOfUser($accessVo) {
        $nickNameTest = $accessVo->nickName;
        $this->accessFac->GetAccessById(null);

        while ($accessVo = $this->accessFac->GetAccessVo()) {
            if ($nickNameTest == $accessVo->nickName) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    private function Jquery() {
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
        $this->accessUserNameCtx->paint($this->nokTemplate);
        $this->accessPassCtx->paint($this->nokTemplate);
        $this->accessConfPassCtx->paint($this->nokTemplate);
    }

    private function LoadHTML() {
        $this->nokTemplate->cargar("template", "html/template.html");
        $this->nokTemplate->cargar("content", "html/access/setAccessView.html");
    }

    private function PrintHTML() {
        $this->nokTemplate->expandir("content", "content");
        $this->nokTemplate->expandir("FINAL", "template");
        $this->nokTemplate->imprimir("FINAL");
    }

}
