<?php

require_once(Config::PATH . Config::GENERAL . 'GeneralMenu.php');
require_once(Config::PATH . Config::GENERAL . 'AdminSession.php');

require_once (Config::PATH . Config::BACKEND . 'module/ModuleFac.php');
require_once (Config::PATH . Config::BACKEND . 'module/vos/ModuleVo.php');
require_once (Config::PATH . Config::BACKEND . 'userModules/UserModulesFac.php');
require_once (Config::PATH . Config::BACKEND . 'userModules/vos/UserModulesVo.php');

/**
 * AjaxUpdateUserModulesView
 *
 * class responsible for loading the html
 *
 * @author		Harold D Duque C
 * @version		1.0
 * @since		Oct of 2016
 */
class AjaxUpdateUserModulesView {

    /**
     *
     * @var AdminSession
     */
    private $adminSession;

    /**
     * @var String
     */
    private $jqueryControls;

    /**
     * Constructora.
     *
     * @param NokTemplate $nokTemplate
     */
    public function __construct() {
        $this->ValidateLoadData();
    }

    private function ValidateLoadData(){
        if (isset($_POST) && !empty($_POST)) {
            $this->UpdateStateUserModule($_POST['id'],$_POST['state']);
        }
    }

    private function UpdateStateUserModule($id,$state) {
      $userModulesVo = new UserModulesVo();
      $userModulesVo->id = $id;
      $userModulesVo->state = $state;
      $userModulesFac = new UserModulesFac();
      $userModulesFac->UpdateUserModulesState($userModulesVo);
    }

}
