<?php

class  UserVo {

    public $id;
    public $names;
    public $lastNames;
    public $fullName;
    public $documentType;
    public $documentNumber;
    public $cellPhoneNumber;
    public $telePhoneNumber;
    public $email;
    public $idRole;

    public function __construct() {
           $this->id = null;
           $this->names = null;
           $this->lastNames = null;
           $this->fullName = null;
           $this->documentType = null;
           $this->documentNumber = null;
           $this->cellPhoneNumber = null;
           $this->telePhoneNumber = null;
           $this->email = null;
           $this->idRole = null;
    }
}
