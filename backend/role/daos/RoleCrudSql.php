<?php

require_once (Config::PATH . Config::BACKEND_LIB . 'Sql.php');
require_once (Config::PATH . Config::BACKEND_LIB . 'DataAccess.php');
require_once (Config::PATH . Config::BACKEND . 'role/vos/RoleVo.php');
require_once (Config::PATH . Config::GENERAL . 'ErroresAdministrador.php');

class  RoleCrudSql {

    private $result;

    function SetRole($dataAccess, $roleVo) {
        $data['"name'] = $roleVo->name;

        $sql = new Sql;

        $sentence = $sql->insert("role ", $data);
        try {
            $dataAccess->query($sentence);
            return $dataAccess->insert_id();
        } catch (Exception $exception) {
            new ErroresAdministrador($exception);
        }
    }

    function DeleteRole($dataAccess, $roleVo) {
        $sql = "
        DELETE FROM
        role
        WHERE role.id = ".$roleVo->id;

        try {
                $this->result = $dataAccess->query($sql);
            } catch (Exception $exception) {
                new ErroresAdministrador($exception);
            }
    }

    function UpdateRole($dataAccess, $roleVo) {
        $data['"name'] = $roleVo->name;

        $sql = new Sql;
        $sentence = $sql->update("role ", $data, "role.id", $roleVo->id);
        try {
                $dataAccess->query($sentence);
                return $dataAccess->insert_id();
            } catch (Exception $exception) {
                new ErroresAdministrador($exception);
            }
    }
    function GetRoleById($dataAccess, $roleVo) {

        $sql = "SELECT
        role.id,
        role.name
        FROM
        role";
        if ($roleVo != "" || $roleVo != null) {
            $sql .= " WHERE role.id = " . $roleVo->id;
        }

        try {
            $this->result = $dataAccess->query($sql);
        } catch (Exception $exception) {
            new ErroresAdministrador($exception);
        }
    }

    public function GetRoleVo($dataAccess) {
        $object = $dataAccess->fetch_object($this->result);
        if ($object) {
            $roleVo = new RoleVo();
            $roleVo->id = $object->id;
            $roleVo->name = $object->name;
            return $roleVo;
        } else {
            return false;
        }
    }
}
