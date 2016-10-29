<?php

require_once (Config::PATH . Config::BACKEND_LIB . 'DataAccess.php');
require_once (Config::PATH . Config::BACKEND . 'access/vos/AccessVo.php');
require_once (Config::PATH . Config::BACKEND . 'access/daos/AccessCrudSql.php');

class AccessFac {

    private $sentence;
    private $dataAccess;

    public function __construct() {
        $this->dataAccess = new DataAccess(Config::HOST, Config::USUARIO, Config::CLAVE, Config::BD);
    }

    public function SetAccess($accessVo) {
        $this->sentence = new AccessCrudSql();
        return $this->sentence->SetAccess($this->dataAccess, $accessVo);
    }

    public function DeleteAccess($accessVo) {
        $this->sentence = new AccessCrudSql();
        return $this->sentence->DeleteAccess($this->dataAccess, $accessVo);
    }

    public function UpdateAccess($accessVo) {
        $this->sentence = new AccessCrudSql();
        return $this->sentence->UpdateAccess($this->dataAccess, $accessVo);
    }

    public function GetAccessById($accessVo) {
        $this->sentence = new AccessCrudSql();
        return $this->sentence->GetAccessById($this->dataAccess, $accessVo);
    }

    public function GetAccessByNickNameAndPassword($accessVo) {
        $this->sentence = new AccessCrudSql();
        return $this->sentence->GetAccessByNickNameAndPassword($this->dataAccess, $accessVo);
    }

    public function GetAccessVo() {
        return $this->sentence->GetAccessVo($this->dataAccess);
    }
}
