<?php

require_once (Config::PATH . Config::BACKEND_LIB . 'DataAccess.php');
require_once (Config::PATH . Config::BACKEND . 'module/vos/ModuleVo.php');
require_once (Config::PATH . Config::BACKEND . 'module/daos/ModuleCrudSql.php');

class ModuleFac {

    private $sentence;
    private $dataAccess;

    public function __construct() {
        $this->dataAccess = new DataAccess(Config::HOST, Config::USUARIO, Config::CLAVE, Config::BD);
    }

    public function SetModule($moduleVo) {
        $this->sentence = new ModuleCrudSql();
        return $this->sentence->SetModule($this->dataAccess, $moduleVo);
    }

    public function DeleteModule($moduleVo) {
        $this->sentence = new ModuleCrudSql();
        return $this->sentence->DeleteModule($this->dataAccess, $moduleVo);
    }

    public function UpdateModule($moduleVo) {
        $this->sentence = new ModuleCrudSql();
        return $this->sentence->UpdateModule($this->dataAccess, $moduleVo);
    }

    public function GetModuleById($moduleVo) {
        $this->sentence = new ModuleCrudSql();
        return $this->sentence->GetModuleById($this->dataAccess, $moduleVo);
    }

    public function GetModuleVo() {
        return $this->sentence->GetModuleVo($this->dataAccess);
    }
}
