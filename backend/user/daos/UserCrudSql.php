<?php

require_once (Config::PATH . Config::BACKEND_LIB . 'Sql.php');
require_once (Config::PATH . Config::BACKEND_LIB . 'DataAccess.php');
require_once (Config::PATH . Config::BACKEND . 'user/vos/UserVo.php');
require_once (Config::PATH . Config::GENERAL . 'ErroresAdministrador.php');

class  UserCrudSql {

    private $result;

    function SetUser($dataAccess, $userVo) {
        $data['"names'] = $userVo->names;
        $data['"lastNames'] = $userVo->lastNames;
        $data['"fullName'] = $userVo->fullName;
        $data['"documentType'] = $userVo->documentType;
        $data['"documentNumber'] = $userVo->documentNumber;
        $data['"cellPhoneNumber'] = $userVo->cellPhoneNumber;
        $data['"telePhoneNumber'] = $userVo->telePhoneNumber;
        $data['"email'] = $userVo->email;
        $data['idRole'] = $userVo->idRole;

        $sql = new Sql;

        $sentence = $sql->insert("user ", $data);
        try {
            $dataAccess->query($sentence);
            return $dataAccess->insert_id();
        } catch (Exception $exception) {
            new ErroresAdministrador($exception);
        }
    }

    function DeleteUser($dataAccess, $userVo) {
        $sql = "
        DELETE FROM
        user
        WHERE user.id = ".$userVo->id;

        try {
                $this->result = $dataAccess->query($sql);
            } catch (Exception $exception) {
                new ErroresAdministrador($exception);
            }
    }

    function UpdateUser($dataAccess, $userVo) {
        $data['"names'] = $userVo->names;
        $data['"lastNames'] = $userVo->lastNames;
        $data['"fullName'] = $userVo->fullName;
        $data['"documentType'] = $userVo->documentType;
        $data['"documentNumber'] = $userVo->documentNumber;
        $data['"cellPhoneNumber'] = $userVo->cellPhoneNumber;
        $data['"telePhoneNumber'] = $userVo->telePhoneNumber;
        $data['"email'] = $userVo->email;
        $data['idRole'] = $userVo->idRole;

        $sql = new Sql;
        $sentence = $sql->update("user ", $data, "user.id", $userVo->id);
        try {
                $dataAccess->query($sentence);
                return $dataAccess->insert_id();
            } catch (Exception $exception) {
                new ErroresAdministrador($exception);
            }
    }
    function GetUserById($dataAccess, $userVo) {

        $sql = "SELECT
        user.id,
        user.names,
        user.lastNames,
        user.fullName,
        user.documentType,
        user.documentNumber,
        user.cellPhoneNumber,
        user.telePhoneNumber,
        user.email,
        user.idRole
        FROM
        user";
        if ($userVo != "" || $userVo != null) {
            $sql .= " WHERE user.id = " . $userVo->id;
        }

        try {
            $this->result = $dataAccess->query($sql);
        } catch (Exception $exception) {
            new ErroresAdministrador($exception);
        }
    }

    function GetUserAndRolById($dataAccess, $userVo) {

          $sql = "SELECT
          user.id,
          user.names,
          user.lastNames,
          user.fullName,
          user.documentType,
          user.documentNumber,
          user.cellPhoneNumber,
          user.telephoneNumber,
          user.email,
          user.idRole,
          role.id as idRole,
          role.name as nameRole
          FROM
          user
          INNER JOIN role ON user.idRole = role.id";

          if ($userVo != null) {
              $sql .= " WHERE user.id = " . $userVo->id;
          }

          $sql .= " ORDER BY user.names ASC";

          try {
              $this->result = $dataAccess->query($sql);
          } catch (Exception $exception) {
              new ErroresAdministrador($exception);
          }
      }

    public function GetUserVo($dataAccess) {
        $object = $dataAccess->fetch_object($this->result);
        if ($object) {
            $userVo = new UserVo();
            $userVo->id = $object->id;
            $userVo->names = $object->names;
            $userVo->lastNames = $object->lastNames;
            $userVo->fullName = $object->fullName;
            $userVo->documentType = $object->documentType;
            $userVo->documentNumber = $object->documentNumber;
            $userVo->cellPhoneNumber = $object->cellPhoneNumber;
            $userVo->telePhoneNumber = $object->telePhoneNumber;
            $userVo->email = $object->email;
            $userVo->idRole = $object->idRole;
            return $userVo;
        } else {
            return false;
        }
    }

    public function GetUserGroupVo($dataAccess) {
      $object = $dataAccess->fetch_object($this->result);

      while ($object) {

          $roleVo = new RoleVo();
          $userVo = new UserVo();
          $userGroupVo = new UserGroupVo();

          $userVo->id = $object->id;
          $userVo->names = $object->names;
          $userVo->lastNames = $object->lastNames;
          $userVo->fullName = $object->fullName;
          $userVo->documentType = $object->documentType;
          $userVo->documentNumber = $object->documentNumber;
          $userVo->cellPhoneNumber = $object->cellPhoneNumber;
          $userVo->telephoneNumber = $object->telephoneNumber;
          $userVo->email = $object->email;
          $userVo->idRole = $object->idRole;

          $roleVo->id = $object->idRole;
          $roleVo->name = $object->nameRole;

          $userGroupVo->userVo = $userVo;
          $userGroupVo->roleVo = $roleVo;

          return $userGroupVo;
      }
      return false;
    }

}
