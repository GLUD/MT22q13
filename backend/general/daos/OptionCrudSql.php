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
class OptionCrudSql {
    
    private $result;
    
     /**
     * Function get user for by user and password
     *
     * @param DataAccess $dataAccess
     * @param GeneralVo $generalVo
     */
    function GetOption($dataAccess, $generalVo) {
        
        $sql = "
            SELECT option_enabled_disabled.id_opt,
                   option_enabled_disabled.nombre_opt
            FROM option_enabled_disabled ";

        if ($generalVo != "" || $generalVo != null) {
            $sql .= " WHERE option_enabled_disabled.id_opt = '" . $generalVo->id_opt;
        }

        try {
            $this->result = $dataAccess->query($sql);
        } catch (Exception $exception) {
            new ErroresAdministrador($exception);
        }
    }
    
    /**
     * Returns an object loaded with data
     *
     * @return OptionDto $optionDto
     */
    public function GetDto($dataAccess) {

        $object = $dataAccess->fetch_object($this->result);
        if ($object) {

            $generalVo = new GeneralVo();

            $generalVo->id = $object->id_opt;
            $generalVo->nombre = $object->nombre_opt;

            return $generalVo;
        } else {
            return false;
        }
    } 
}