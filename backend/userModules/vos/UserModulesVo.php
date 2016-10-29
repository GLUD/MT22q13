<?php

class  UserModulesVo {

    public $id;
    public $idUser;
    public $idModule;
    public $state;

    public function __construct() {
           $this->id = null;
           $this->idUser = null;
           $this->idModule = null;
           $this->state = null;
    }
}
