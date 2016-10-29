<?php

class  AccessVo {

    public $id;
    public $idUser;
    public $nickName;
    public $password;
    public $state;

    public function __construct() {
           $this->id = null;
           $this->idUser = null;
           $this->nickName = null;
           $this->password = null;
           $this->state = null;
    }
}
