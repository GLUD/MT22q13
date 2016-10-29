<?php

require_once (Config::PATH . Config::BACKEND_LIB . 'DataAccess.php');
require_once (Config::PATH . Config::BACKEND . 'role/vos/RoleVo.php');
require_once (Config::PATH . Config::BACKEND . 'role/daos/RoleCrudSql.php');

class RoleFac {

    private $sentence;
    private $dataAccess;

    public function __construct() {
        $this->dataAccess = new DataAccess(Config::HOST, Config::USUARIO, Config::CLAVE, Config::BD);
    }

    public function SetRole($roleVo) {
        $this->sentence = new RoleCrudSql();
        return $this->sentence->SetRole($this->dataAccess, $roleVo);
    }

    public function DeleteRole($roleVo) {
        $this->sentence = new RoleCrudSql();
        return $this->sentence->DeleteRole($this->dataAccess, $roleVo);
    }

    public function UpdateRole($roleVo) {
        $this->sentence = new RoleCrudSql();
        return $this->sentence->UpdateRole($this->dataAccess, $roleVo);
    }

    public function GetRoleById($roleVo) {
        $this->sentence = new RoleCrudSql();
        return $this->sentence->GetRoleById($this->dataAccess, $roleVo);
    }

    public function GetRoleVo() {
        return $this->sentence->GetRoleVo($this->dataAccess);
    }
}
