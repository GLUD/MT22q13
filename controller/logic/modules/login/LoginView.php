<?php

require_once(Config::PATH . Config::GENERAL . 'AdminSession.php');

require_once (Config::PATH . Config::BACKEND . 'user/UserFac.php');
require_once(Config::PATH . Config::BACKEND . 'user/vos/UserVo.php');
require_once(Config::PATH . Config::BACKEND . 'user/vos/UserGroupVo.php');

require_once(Config::PATH . Config::CONTROLLER_LIB . 'PForma.php');
require_once(Config::PATH . Config::MODULES . 'login/comp/NickNameCtx.php');
require_once(Config::PATH . Config::MODULES . 'login/comp/PasswordCtx.php');

/**
 * LoginView
 *
 * class responsible for loading the html
 *
 * @author		Harold D Duque C
 * @version		1.0
 * @since		Oct of 2016
 */
class LoginView {

    /**
     * Class for paint the html.
     *
     * @var NokTemplate
     */
    private $nokTemplate;

    /**
     * form.
     *
     * @var PForma
     */
    private $form;

    /**
     * input.
     *
     * @var NickNameCtx
     */
    private $nickNameCtx;

    /**
     * input of type password.
     *
     * @var PasswordCtx
     */
    private $passwordCtx;

    /**
     *
     * @var UsersFac
     */
    private $usersFac;

    /**
     *
     * @var AdminSession
     */
    private $adminSession;

    /**
     * Constructora.
     *
     * @param NokTemplate $nokTemplate
     */
    public function __construct($nokTemplate) {
        $this->nokTemplate = $nokTemplate;
        $this->adminSession = new AdminSession();
        //$this->adminSession->IsSessionCreate();
        $this->CrateForm();
        $this->ValidateForm();
        $this->Paint();
    }

    /**
     * Create the form.
     *
     */
    private function CrateForm() {
        $this->form = new PForma("form");
        $this->nickNameCtx = new NickNameCtx($this->form);
        $this->passwordCtx = new PasswordCtx($this->form);
    }

    /**
     * Validate form data.
     *
     */
    private function ValidateForm() {
        $post = $this->form->validarSinBoton();
        if ($post) {
            $this->UseDataFromThePostback($post);
        }
    }

    private function UseDataFromThePostback($post) {
        $nickName = $post['nickNameCtx'];
        $password = $post['passwordCtx'];
        $this->adminSession = new AdminSession();
        $this->adminSession->ValitateCreateSesion($nickName, $password);
        if($this->adminSession->isSessionCreate){
          //print_r("REDIRECCIONA");
          header("Location: " . Config::REDIRECTS . Config::GENERAL . "index.php?view=home");
        }else {
          $this->nokTemplate->asignar("jquery", "
             <script type=\"text/javascript\">
              $(document).ready(function(){
                  $(\"#nickNameCtx\").attr('value','');
                  $(\"#passwordCtx\").attr('value', '');
                  $('#mensajeErrorLogueo').fadeIn(1000);
              });
             </script>
            ");
        }
        /*if (!$this->adminSession->isSessionCreate) {//---user not exist in database
            $this->nokTemplate->asignar("jquery", "
               <script type=\"text/javascript\">
                $(document).ready(function(){
                    $(\"#userCtx\").attr('value','');
                    $(\"#passwordCtx\").attr('value', '');
                    $('#mensajeErrorLogueo').fadeIn(1000);
                });
               </script>
              ");
        }*/
    }

    /**
     * Pinta la vista.
     *
     */
    private function Paint() {
        $this->LoadHTML();
        $this->PaintComponents();
        $this->PrintHTML();
    }

    private function PaintComponents() {
        $this->form->pintar($this->nokTemplate);
        $this->nickNameCtx->pintar($this->nokTemplate);
        $this->passwordCtx->pintar($this->nokTemplate);
    }

    /**
     * Carga los html.
     *
     */
    private function LoadHTML() {
        $this->nokTemplate->cargar("contenido", "html/login.html");
    }

    /**
     * Imprime la vista.
     *
     */
    private function PrintHTML() {
        $this->nokTemplate->expandir("FINAL", "contenido");
        $this->nokTemplate->imprimir("FINAL");
    }


}
