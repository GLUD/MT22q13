<?php

require_once (Config::PATH . Config::BACKEND_LIB . 'Sql.php');
require_once (Config::PATH . Config::BACKEND_LIB . 'DataAccess.php');
require_once (Config::PATH . Config::BACKEND . 'access/vos/AccessVo.php');
require_once (Config::PATH . Config::GENERAL . 'ErroresAdministrador.php');

class  AccessCrudSql {

    private $result;

    function SetAccess($dataAccess, $accessVo) {
        $data['idUser'] = $accessVo->idUser;
        $data['"nickName'] = $accessVo->nickName;
        $data['"password'] = $accessVo->password;
        $data['state'] = $accessVo->state;

        $sql = new Sql;

        $sentence = $sql->insert("access ", $data);
        try {
            $dataAccess->query($sentence);
            return $dataAccess->insert_id();
        } catch (Exception $exception) {
            new ErroresAdministrador($exception);
        }
    }

    function DeleteAccess($dataAccess, $accessVo) {
        $sql = "
        DELETE FROM
        access
        WHERE access.id = ".$accessVo->id;

        try {
                $this->result = $dataAccess->query($sql);
            } catch (Exception $exception) {
                new ErroresAdministrador($exception);
            }
    }

    function UpdateAccess($dataAccess, $accessVo) {
        $data['idUser'] = $accessVo->idUser;
        $data['"nickName'] = $accessVo->nickName;
        $data['"password'] = $accessVo->password;
        $data['state'] = $accessVo->state;

        $sql = new Sql;
        $sentence = $sql->update("access ", $data, "access.id", $accessVo->id);
        try {
                $dataAccess->query($sentence);
                return $dataAccess->insert_id();
            } catch (Exception $exception) {
                new ErroresAdministrador($exception);
            }
    }
    function GetAccessById($dataAccess, $accessVo) {

        $sql = "SELECT
        access.id,
        access.idUser,
        access.nickName,
        access.password,
        access.state
        FROM
        access";
        if ($accessVo != "" || $accessVo != null) {
            $sql .= " WHERE access.id = " . $accessVo->id;
        }

        try {
            $this->result = $dataAccess->query($sql);
        } catch (Exception $exception) {
            new ErroresAdministrador($exception);
        }
    }

    function GetAccessByNickNameAndPassword($dataAccess, $accessVo) {

        $sql = "SELECT
        access.id,
        access.idUser,
        access.nickName,
        access.password,
        access.state
        FROM
        access";
        if ($accessVo != "" || $accessVo != null) {
            $sql .= " WHERE access.nickName = '" . $accessVo->nickName . "'";
            $sql .= " AND access.password = '" . $accessVo->password . "'";
        }

        try {
            $this->result = $dataAccess->query($sql);
        } catch (Exception $exception) {
            new ErroresAdministrador($exception);
        }
    }

    public function GetAccessVo($dataAccess) {
        $object = $dataAccess->fetch_object($this->result);
        if ($object) {
            $accessVo = new AccessVo();
            $accessVo->id = $object->id;
            $accessVo->idUser = $object->idUser;
            $accessVo->nickName = $object->nickName;
            $accessVo->password = $object->password;
            $accessVo->state = $object->state;
            return $accessVo;
        } else {
            return false;
        }
    }
}
