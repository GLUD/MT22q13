<?php

require_once (Config::PATH . Config::BACKEND_LIB . 'Sql.php');
require_once (Config::PATH . Config::BACKEND_LIB . 'DataAccess.php');
require_once (Config::PATH . Config::BACKEND . 'userModules/vos/UserModulesVo.php');
require_once (Config::PATH . Config::GENERAL . 'ErroresAdministrador.php');

class  UserModulesCrudSql {

    private $result;

    function SetUserModules($dataAccess, $userModulesVo) {
        $data['idUser'] = $userModulesVo->idUser;
        $data['idModule'] = $userModulesVo->idModule;
        $data['state'] = $userModulesVo->state;

        $sql = new Sql;

        $sentence = $sql->insert("userModules ", $data);
        try {
            $dataAccess->query($sentence);
            return $dataAccess->insert_id();
        } catch (Exception $exception) {
            new ErroresAdministrador($exception);
        }
    }

    function DeleteUserModules($dataAccess, $userModulesVo) {
        $sql = "
        DELETE FROM
        userModules
        WHERE userModules.id = ".$userModulesVo->id;

        try {
                $this->result = $dataAccess->query($sql);
            } catch (Exception $exception) {
                new ErroresAdministrador($exception);
            }
    }

    function UpdateUserModules($dataAccess, $userModulesVo) {
        $data['idUser'] = $userModulesVo->idUser;
        $data['idModule'] = $userModulesVo->idModule;
        $data['state'] = $userModulesVo->state;

        $sql = new Sql;
        $sentence = $sql->update("userModules ", $data, "userModules.id", $userModulesVo->id);
        try {
                $dataAccess->query($sentence);
                return $dataAccess->insert_id();
            } catch (Exception $exception) {
                new ErroresAdministrador($exception);
            }
    }

    function UpdateUserModulesState($dataAccess, $userModulesVo) {
        $data['state'] = $userModulesVo->state;

        $sql = new Sql;
        $sentence = $sql->update("userModules ", $data, "userModules.id", $userModulesVo->id);

        try {
                $dataAccess->query($sentence);
                return $dataAccess->insert_id();
            } catch (Exception $exception) {
                new ErroresAdministrador($exception);
            }
    }

    function GetUserModulesById($dataAccess, $userModulesVo) {

        $sql = "SELECT
        userModules.id,
        userModules.idUser,
        userModules.idModule,
        userModules.state
        FROM
        userModules";
        if ($userModulesVo != "" || $userModulesVo != null) {
            $sql .= " WHERE userModules.id = " . $userModulesVo->id;
        }

        try {
            $this->result = $dataAccess->query($sql);
        } catch (Exception $exception) {
            new ErroresAdministrador($exception);
        }
    }

    function GetUserModulesByIdUser($dataAccess, $userModulesVo) {

        $sql = "SELECT
        userModules.id,
        userModules.idUser,
        userModules.idModule,
        userModules.state
        FROM
        userModules";
        if ($userModulesVo != "" || $userModulesVo != null) {
            $sql .= " WHERE userModules.idUser = " . $userModulesVo->idUser;
        }

        try {
            $this->result = $dataAccess->query($sql);
        } catch (Exception $exception) {
            new ErroresAdministrador($exception);
        }
    }

    public function GetUserModulesVo($dataAccess) {
        $object = $dataAccess->fetch_object($this->result);
        if ($object) {
            $userModulesVo = new UserModulesVo();
            $userModulesVo->id = $object->id;
            $userModulesVo->idUser = $object->idUser;
            $userModulesVo->idModule = $object->idModule;
            $userModulesVo->state = $object->state;
            return $userModulesVo;
        } else {
            return false;
        }
    }
}
