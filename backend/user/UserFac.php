<?php

require_once (Config::PATH . Config::BACKEND_LIB . 'DataAccess.php');
require_once (Config::PATH . Config::BACKEND . 'user/vos/UserVo.php');
require_once (Config::PATH . Config::BACKEND . 'user/daos/UserCrudSql.php');

class UserFac {

    private $sentence;
    private $dataAccess;

    public function __construct() {
        $this->dataAccess = new DataAccess(Config::HOST, Config::USUARIO, Config::CLAVE, Config::BD);
    }

    public function SetUser($userVo) {
        $this->sentence = new UserCrudSql();
        return $this->sentence->SetUser($this->dataAccess, $userVo);
    }

    public function DeleteUser($userVo) {
        $this->sentence = new UserCrudSql();
        return $this->sentence->DeleteUser($this->dataAccess, $userVo);
    }

    public function UpdateUser($userVo) {
        $this->sentence = new UserCrudSql();
        return $this->sentence->UpdateUser($this->dataAccess, $userVo);
    }

    public function GetUserById($userVo) {
        $this->sentence = new UserCrudSql();
        return $this->sentence->GetUserById($this->dataAccess, $userVo);
    }

    public function GetUserAndRolById($userVo) {
        $this->sentence = new UserCrudSql();
        return $this->sentence->GetUserAndRolById($this->dataAccess, $userVo);
    }

    public function GetUserVo() {
        return $this->sentence->GetUserVo($this->dataAccess);
    }

    public function GetUserGroupVo() {
        return $this->sentence->GetUserGroupVo($this->dataAccess);
    }
}
