<?php

require_once (Config::PATH . Config::BACKEND_LIB . 'Sql.php');
require_once (Config::PATH . Config::BACKEND_LIB . 'DataAccess.php');
require_once (Config::PATH . Config::BACKEND . 'general/vos/GeneralVo.php');

require_once (Config::PATH . Config::GENERAL . 'ErroresAdministrador.php');

/**
 * OptionCrudSql
 *
 * Class that controls add, get, modify and delete
 * 
 * @author		Harold D Duque C
 * @version		1.0
 * @since		Oct of 2016
 */
class GeneralCrudSql {
    
    private $result;
    
    /**
     * 
     * @param DataAccess $dataAccess
     * @param string $nameTable
     * @param string $nameField
     * @param string $searchField
     * @param bool $isSearchFieldString
     */
    function IsExistData($dataAccess, $nameTable, $nameField, $searchField, $isSearchFieldString){
        $sql = "
            SELECT
            id,
            $nameTable.$nameField AS $nameField
            FROM
            $nameTable";        
        
        if($isSearchFieldString)
        {
            $sql .= " WHERE $nameTable.$nameField = '$searchField' ";
        }else{
            $sql .= " WHERE $nameTable.$nameField = $searchField ";
        }
        
        
        try {
            $this->result = $dataAccess->query($sql);
        } catch (Exception $exception) {
            new ErroresAdministrador($exception);
        }        
    }
    
    /**
     * GetData
     * @param DataAccess $dataAccess
     * @param GeneralSearchVo $generalSearchVo
     */
    function GetData($dataAccess, $generalSearchVo){
        
        $sql = "
            SELECT
            $generalSearchVo->nameTable.$generalSearchVo->nameFieldId AS $generalSearchVo->nameFieldId,
            $generalSearchVo->nameTable.$generalSearchVo->nameField AS $generalSearchVo->nameField
            FROM
            $generalSearchVo->nameTable";        
        
        if($generalSearchVo->searchField != null){
            if($generalSearchVo->isSearchFieldString)
            {
                $sql .= " WHERE $generalSearchVo->nameTable.$generalSearchVo->searchField = '$generalSearchVo->valueSearchField' ";
            }else{
                $sql .= " WHERE $generalSearchVo->nameTable.$generalSearchVo->searchField = $generalSearchVo->valueSearchField ";
            }            
        }
        
        if($generalSearchVo->numOrderField == 1){
            $sql .= " ORDER BY $generalSearchVo->nameTable.$generalSearchVo->nameFieldId ".$generalSearchVo->typeOrderField;
        }else if($generalSearchVo->numOrderField == 2){
            $sql .= " ORDER BY $generalSearchVo->nameTable.$generalSearchVo->nameField ".$generalSearchVo->typeOrderField;    
        }
        
        
        try {
            $this->result = $dataAccess->query($sql);
        } catch (Exception $exception) {
            new ErroresAdministrador($exception);
        }        
    }
    
    /**
     * GetDataCharacterIni
     * @param DataAccess $dataAccess
     * @param GeneralSearchVo $generalSearchVo
     */
    function GetDataCharacterIni($dataAccess, $generalSearchVo){
        $sql = "
            SELECT
            $generalSearchVo->nameTable.$generalSearchVo->nameFieldId AS $generalSearchVo->nameFieldId,
            $generalSearchVo->nameTable.$generalSearchVo->nameField AS $generalSearchVo->nameField
            FROM
            $generalSearchVo->nameTable";        
        
        if($generalSearchVo->valueSearchField != null){
            $sql .= " WHERE $generalSearchVo->nameTable.$generalSearchVo->searchField like '$generalSearchVo->valueSearchField%'";
        }
        
        try {
            $this->result = $dataAccess->query($sql);
        } catch (Exception $exception) {
            new ErroresAdministrador($exception);
        }        
    }
    
    /**
     * @param DataAccess $dataAccess
     * @return boolean|\GeneralVo
     */
    public function GetVo($dataAccess) {
        $array = $dataAccess->fetch_array($this->result);
        while ($array) {
            $vo = new GeneralVo();
            $vo->id = $array[0];
            $vo->name = $array[1];
            return $vo;
        }
        return false;
        
    }     
}