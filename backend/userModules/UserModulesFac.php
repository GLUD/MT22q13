<?php

require_once (Config::PATH . Config::BACKEND_LIB . 'DataAccess.php');
require_once (Config::PATH . Config::BACKEND . 'userModules/vos/UserModulesVo.php');
require_once (Config::PATH . Config::BACKEND . 'userModules/daos/UserModulesCrudSql.php');

class UserModulesFac {

    private $sentence;
    private $dataAccess;

    public function __construct() {
        $this->dataAccess = new DataAccess(Config::HOST, Config::USUARIO, Config::CLAVE, Config::BD);
    }

    public function SetUserModules($userModulesVo) {
        $this->sentence = new UserModulesCrudSql();
        return $this->sentence->SetUserModules($this->dataAccess, $userModulesVo);
    }

    public function DeleteUserModules($userModulesVo) {
        $this->sentence = new UserModulesCrudSql();
        return $this->sentence->DeleteUserModules($this->dataAccess, $userModulesVo);
    }

    public function UpdateUserModules($userModulesVo) {
        $this->sentence = new UserModulesCrudSql();
        return $this->sentence->UpdateUserModules($this->dataAccess, $userModulesVo);
    }

    public function UpdateUserModulesState($userModulesVo) {
        $this->sentence = new UserModulesCrudSql();
        return $this->sentence->UpdateUserModulesState($this->dataAccess, $userModulesVo);
    }

    public function GetUserModulesById($userModulesVo) {
        $this->sentence = new UserModulesCrudSql();
        return $this->sentence->GetUserModulesById($this->dataAccess, $userModulesVo);
    }

    public function GetUserModulesByIdUser($userModulesVo) {
        $this->sentence = new UserModulesCrudSql();
        return $this->sentence->GetUserModulesByIdUser($this->dataAccess, $userModulesVo);
    }

    public function GetUserModulesVo() {
        return $this->sentence->GetUserModulesVo($this->dataAccess);
    }
}
