<?php

require_once (Config::PATH . Config::BACKEND_LIB . 'Sql.php');
require_once (Config::PATH . Config::BACKEND_LIB . 'DataAccess.php');
require_once (Config::PATH . Config::BACKEND . 'module/vos/ModuleVo.php');
require_once (Config::PATH . Config::GENERAL . 'ErroresAdministrador.php');

class  ModuleCrudSql {

    private $result;

    function SetModule($dataAccess, $moduleVo) {
        $data['"name'] = $moduleVo->name;

        $sql = new Sql;

        $sentence = $sql->insert("module ", $data);
        try {
            $dataAccess->query($sentence);
            return $dataAccess->insert_id();
        } catch (Exception $exception) {
            new ErroresAdministrador($exception);
        }
    }

    function DeleteModule($dataAccess, $moduleVo) {
        $sql = "
        DELETE FROM
        module
        WHERE module.id = ".$moduleVo->id;

        try {
                $this->result = $dataAccess->query($sql);
            } catch (Exception $exception) {
                new ErroresAdministrador($exception);
            }
    }

    function UpdateModule($dataAccess, $moduleVo) {
        $data['"name'] = $moduleVo->name;

        $sql = new Sql;
        $sentence = $sql->update("module ", $data, "module.id", $moduleVo->id);
        try {
                $dataAccess->query($sentence);
                return $dataAccess->insert_id();
            } catch (Exception $exception) {
                new ErroresAdministrador($exception);
            }
    }
    function GetModuleById($dataAccess, $moduleVo) {

        $sql = "SELECT
        module.id,
        module.name
        FROM
        module";
        if ($moduleVo != "" || $moduleVo != null) {
            $sql .= " WHERE module.id = " . $moduleVo->id;
        }

        try {
            $this->result = $dataAccess->query($sql);
        } catch (Exception $exception) {
            new ErroresAdministrador($exception);
        }
    }

    public function GetModuleVo($dataAccess) {
        $object = $dataAccess->fetch_object($this->result);
        if ($object) {
            $moduleVo = new ModuleVo();
            $moduleVo->id = $object->id;
            $moduleVo->name = $object->name;
            return $moduleVo;
        } else {
            return false;
        }
    }
}
