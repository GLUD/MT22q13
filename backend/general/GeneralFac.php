<?php

require_once(Config::PATH . Config::BACKEND_LIB . 'DataAccess.php');
require_once(Config::PATH . Config::BACKEND . 'general/daos/OptionCrudSql.php');
require_once(Config::PATH . Config::BACKEND . 'general/daos/GeneralCrudSql.php');

/**
 * GeneralFac
 *
 * Conection to backend, contains all kinds of transactions of modules for backend
 * 
 * @author		Harold D Duque C
 * @version		1.0
 * @since		Oct of 2016
 */
class GeneralFac {
     /**
     * @var DataAccess
     */
    private $datosAcceso;

    /**
     * @var mixed
     */
    private $sentence;

    public function __construct() {
        $this->datosAcceso = new DataAccess(Config::HOST, Config::USUARIO, Config::CLAVE, Config::BD);
    }
    
     /**
     * @var GeneralVo $generalVo
     */
    public function GetOption($generalVo) {
        $this->sentence = new OptionCrudSql();
        return $this->sentence->GetOption($this->datosAcceso, $generalVo);
    }  
    
    /**
     * @return bool
     */
    public function IsExistData($nameTable, $nameField, $searchField, $isSearchFieldString){
        $this->sentence = new GeneralCrudSql();
        $datos = $this->sentence->IsExistData($this->datosAcceso, $nameTable, $nameField, $searchField, $isSearchFieldString);
        if ($datos == null || $datos == "") {
            return false;
        } else {
            return true;
        }
    }    
    
    /**
     * @param GeneralSearchVo $generalSearchVo
     * @return int
     */
    public function GetData($generalSearchVo){
        $this->sentence = new GeneralCrudSql();
        return $this->sentence->GetData($this->datosAcceso, $generalSearchVo);
    }    
    
    /**
     * @param GeneralSearchVo $generalSearchVo
     * @return int
     */
    public function GetDataCharacterIni($generalSearchVo){
        $this->sentence = new GeneralCrudSql();
        return $this->sentence->GetDataCharacterIni($this->datosAcceso, $generalSearchVo);
    }    
    
    /**
    * @var mixed
    */
    public function GetVo() {
        return $this->sentence->GetVo($this->datosAcceso);
    } 
    
    /**
    * @var mixed
    */
    public function GetDataCharacterIniVo() {
        return $this->sentence->GetVo($this->datosAcceso);
    } 
}
