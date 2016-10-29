<?php
require_once(Config::PATH . Config::BACKEND . 'role/RoleFac.php');
require_once(Config::PATH . Config::BACKEND . 'user/UserFac.php');
require_once(Config::PATH . Config::BACKEND . 'access/AccessFac.php');
require_once (Config::PATH . Config::BACKEND . 'module/ModuleFac.php');
require_once (Config::PATH . Config::BACKEND . 'userModules/UserModulesFac.php');

require_once(Config::PATH . Config::BACKEND . 'role/vos/RoleVo.php');
require_once(Config::PATH . Config::BACKEND . 'user/vos/UserVo.php');
require_once(Config::PATH . Config::BACKEND . 'access/vos/AccessVo.php');
require_once(Config::PATH . Config::BACKEND . 'user/vos/UserGroupVo.php');
require_once (Config::PATH . Config::BACKEND . 'userModules/vos/UserModulesVo.php');


/**
 * Description of AdminSession
 *
 * @author HAROLD
 */
class AdminSession {
    /**
     * @var AccessFac
     */
    public $accessFac;

    /**
     * @var UserGroupVo
     */
    public $userGroupVo;

    /**
     * @var boolean
     */
    public $isSessionCreate = false;

    /**
     * inactivity time
     *
     * @var int
     */
    public $timeForDuration = 3600;

    public function __construct() {
        $this->accessFac = new AccessFac();
        $this->userGroupVo = new UserGroupVo();
    }

    /**
     *
     * @param string $user
     * @param string $password
     * @return boolean
     */
    public function ValitateCreateSesion($nickName, $password) {
        $accessVo = new AccessVo();
        $accessVo->nickName = md5($nickName);
        $accessVo->password = md5($password);
        $this->accessFac->GetAccessByNickNameAndPassword($accessVo);
        $this->userGroupVo->accessVo = $this->accessFac->GetAccessVo();
        if (empty($this->userGroupVo->accessVo)) {
            $this->isSessionCreate = false;
        }  else {
            $this->GetUserData();
            $this->GetRoleData();
            $this->GetUserModulesData();
            $this->isSessionCreate = $this->CreateSession();
        }
    }

    public function CreateSession() {
      if (!session_id()) {
          session_start();
      }

      $_SESSION['user']['role']['id'] = $this->userGroupVo->roleVo->id;
      $_SESSION['user']['role']['name'] = $this->userGroupVo->roleVo->name;

      $_SESSION['user']['access']['id'] = $this->userGroupVo->accessVo->id;
      $_SESSION['user']['access']['idUser'] = $this->userGroupVo->accessVo->idUser;
      $_SESSION['user']['access']['nickName'] = $this->userGroupVo->accessVo->nickName;
      $_SESSION['user']['access']['password'] = $this->userGroupVo->accessVo->password;
      $_SESSION['user']['access']['state'] = $this->userGroupVo->accessVo->state;

      $_SESSION['user']['user']['id'] = $this->userGroupVo->userVo->id;
      $_SESSION['user']['user']['names'] = $this->userGroupVo->userVo->names;
      $_SESSION['user']['user']['lastNames'] = $this->userGroupVo->userVo->lastNames;
      $_SESSION['user']['user']['fullName'] = $this->userGroupVo->userVo->fullName;
      $_SESSION['user']['user']['documentType'] = $this->userGroupVo->userVo->documentType;
      $_SESSION['user']['user']['documentNumber'] = $this->userGroupVo->userVo->documentNumber;
      $_SESSION['user']['user']['cellPhoneNumber'] = $this->userGroupVo->userVo->cellPhoneNumber;
      $_SESSION['user']['user']['telePhoneNumber'] = $this->userGroupVo->userVo->telePhoneNumber;
      $_SESSION['user']['user']['email'] = $this->userGroupVo->userVo->email;
      $_SESSION['user']['user']['idRole'] = $this->userGroupVo->userVo->idRole;

      return true;
    }

    public function GetUserData() {
        $userVo = new UserVo();
        $userVo->id = $this->userGroupVo->accessVo->idUser;
        $userFac = new UserFac();
        $userFac->GetUserById($userVo);
        if($userVo = $userFac->GetUserVo()){
          $this->userGroupVo->userVo = $userVo;
        }
    }

    public function GetRoleData() {
        $roleVo = new RoleVo();
        $roleVo->id = $this->userGroupVo->userVo->idRole;
        $roleFac = new RoleFac();
        $roleFac->GetRoleById($roleVo);
        if($roleVo = $roleFac->GetRoleVo()){
          $this->userGroupVo->roleVo = $roleVo;
        }
    }

    public function GetUserModulesData() {
        $userModulesVo = new UserModulesVo();
        $userModulesVo->idUser = $this->userGroupVo->userVo->id;
        $userModulesFac = new UserModulesFac();
        $userModulesFac->GetUserModulesByIdUser($userModulesVo);
        if($userModulesVo = $userModulesFac->GetUserModulesVo()){
            $this->userGroupVo->userModulesVo = $userModulesVo;
        }
    }

    private function GetRoleVo() {
      if (!isset($_SESSION['user'])) {
          session_start();
      }
       $roleVo = new RoleVo();
       $roleVo->id = $_SESSION['user']['role']['id'];
       $roleVo->name = $_SESSION['user']['role']['name'];
       return $roleVo;
    }

    private function GetAccessVo() {
      if (!isset($_SESSION['user'])) {
          session_start();
      }
       $accessVo = new AccessVo();
       $accessVo->id = $_SESSION['user']['access']['id'];
       $accessVo->idUser = $_SESSION['user']['access']['idUser'];
       $accessVo->nickName = $_SESSION['user']['access']['nickName'];
       $accessVo->password = $_SESSION['user']['access']['password'];
       $accessVo->state = $_SESSION['user']['access']['state'];
       return $accessVo;
    }

    public function GetUserVo() {
      if (!isset($_SESSION['user'])) {
          session_start();
      }
      $userVo = new UserVo();
      $userVo->id = $_SESSION['user']['user']['id'];
      $userVo->names = $_SESSION['user']['user']['names'];
      $userVo->lastNames = $_SESSION['user']['user']['lastNames'];
      $userVo->fullName = $_SESSION['user']['user']['fullName'];
      $userVo->documentType = $_SESSION['user']['user']['documentType'];
      $userVo->documentNumber = $_SESSION['user']['user']['documentNumber'];
      $userVo->cellPhoneNumber = $_SESSION['user']['user']['cellPhoneNumber'];
      $userVo->telePhoneNumber = $_SESSION['user']['user']['telePhoneNumber'];
      $userVo->email = $_SESSION['user']['user']['email'];
      $userVo->idRole = $_SESSION['user']['user']['idRole'];
      return $userVo;
    }

    private function GetUserGroupVo() {
      $userGroupVo = new UserGroupVo();
      $userGroupVo->roleVo = $this->GetRoleVo();
      $userGroupVo->accessVo = $this->GetAccessVo();
      $userGroupVo->userVo = $this->GetUserVo();
      return $userModulesVo;
    }

    public function GetNameUser() {
      if (!isset($_SESSION['user'])) {
          session_start();
      }
      return $_SESSION['user']['user']['fullName'];
    }

    public function DestroySesion() {
        if (!isset($_SESSION["user"])) {
            session_start();
        }
        session_unset();
        session_destroy();
        $_SESSION["user"] = null;
        unset($_SESSION['user']);
    }

    /**
     * validate if session is create or data user is current
     */
    /*public function ValidateSession() {
        if(!isset($_SESSION['user'])){
            session_start();
        }
        if($_SESSION['time'] + $this->timeForDuration < time() || !isset($_SESSION['user'])){
            $this->DestroySesion();
            header("Location: " . Config::REDIRECTS . Config::GENERAL . "index.php?view=login");
        }
    }*/

    /*public function IsSessionCreate() {
        session_start();
        if (!empty($_SESSION['user'])) {
            header("Location: " . Config::REDIRECTS . Config::GENERAL . "index.php?view=home");
        }
    }*/

     /**
     * return all data of session
     * @return Array
     */
    /*public function DatosSesion() {
        if (!isset($_SESSION["user"])) {
            session_start();
        }
        return $_SESSION['user'];
    }*/

    /**
     * Return id user
     *
     * @param NokTemplate $nokTemplate
     * @return string
     */
    /*public function IdUser() {
        if (!isset($_SESSION["user"])) {
            session_start();
        }
        return $_SESSION['user']['id'];
    }*/

    /**
     * Return name user
     */
    /*public function GetNameUser() {
        if (!session_id()) {
            session_start();
            return $_SESSION['user']['names_us'];
        }else if(session_id()){
            if($_SESSION){
                return $_SESSION['user']['names_us'];
            }
        }
    }*/

    /*public function DestroySesion() {
        if (!isset($_SESSION["user"])) {
            session_start();
        }
        session_unset();
        session_destroy();
        $_SESSION["user"] = null;
        unset($_SESSION['user']);
    }*/

    /*public function IsUserAdmin(){
        $AccessVo = new AccessVo();
        $AccessVo->id = $this->IdUser();
        $this->userFac->GetUserAndRolById($AccessVo);
        $userGroupVo = $this->usersFac->GetUserGroupVo();
        if($userGroupVo->RolVo->name_rol == "Admin"){
            return true;
        }
        return false;
    }*/

    /**
     * @param NokTemplate $nokTemplate
     */
    /*public function PaintNameUser($nokTemplate) {
        if (!isset($_SESSION["user"])) {
            session_start();
        }
        $nokTemplate->asignar("NameUser", $this->GetNameUser());
        $this->PaintRedirectionLogo($nokTemplate);
    }*/

    /*private function PaintRedirectionLogo($nokTemplate) {
        $nokTemplate->asignar("RedirectionLogo", Config::REDIRECTS . Config::GENERAL . "index.php?vista=homeSystem");
    }*/

}


/************************************************************************************************************************/
/*
<?php
require_once(Config::PATH . Config::BACKEND . 'user/UserFac.php');
require_once(Config::PATH . Config::BACKEND . 'role/vos/RoleVo.php');
require_once(Config::PATH . Config::BACKEND . 'user/vos/UserVo.php');
require_once(Config::PATH . Config::BACKEND . 'access/vos/AccessVo.php');
require_once(Config::PATH . Config::BACKEND . 'user/vos/UserGroupVo.php');
require_once(Config::PATH . Config::BACKEND . 'typeAccess/vos/TypeAccessVo.php');
require_once(Config::PATH . Config::BACKEND . 'typeAccess/TypeAccessFac.php');
require_once (Config::PATH . Config::BACKEND . 'userModules/UserModulesFac.php');
require_once (Config::PATH . Config::BACKEND . 'userModules/vos/UserModulesVo.php');
require_once (Config::PATH . Config::BACKEND . 'module/ModuleFac.php');
require_once (Config::PATH . Config::BACKEND . 'module/vos/ModuleVo.php');

class AdminSession {

    public function __construct() {

    }

    /**
     * @param AccessVo $accessVo
     */
/*    public function CreateSession($accessVo,$typeAccessVo,$roleVo,$userVo,$arrayModulesVo,$arrayUserModulesVo) {
        if(!session_id()){
            session_start();
        }

        $_SESSION['user']['access']['id']=$accessVo->id;
        $_SESSION['user']['access']['idUser']=$accessVo->idUser;
        $_SESSION['user']['access']['nickName']=$accessVo->nickName;
        $_SESSION['user']['access']['state']=$accessVo->state;

        $_SESSION['user']['typeAccess']['id']=$typeAccessVo->id;
        $_SESSION['user']['typeAccess']['name']=$typeAccessVo->name;
        $_SESSION['user']['typeAccess']['url']=$typeAccessVo->url;

        $_SESSION['user']['role']['id']=$roleVo->id;
        $_SESSION['user']['role']['name']=$roleVo->name;
        $_SESSION['user']['role']['idTypeAccess']=$roleVo->idTypeAccess;

        $_SESSION['user']['user']['id']=$userVo->id;
        $_SESSION['user']['user']['names']=$userVo->names;
        $_SESSION['user']['user']['lastNames']=$userVo->lastNames;
        $_SESSION['user']['user']['documentType']=$userVo->documentType;
        $_SESSION['user']['user']['documentNumber']=$userVo->documentNumber;
        $_SESSION['user']['user']['cellphoneNumber']=$userVo->cellphoneNumber;
        $_SESSION['user']['user']['telephoneNumber']=$userVo->telephoneNumber;
        $_SESSION['user']['user']['email']=$userVo->email;
        $_SESSION['user']['user']['idRole']=$userVo->idRole;
        $_SESSION['user']['user']['idUser']=$userVo->idUser;

        $this->CreateSessionModules($arrayModulesVo,$arrayUserModulesVo);
        //print_r($_SESSION['user']);
        //$this->ValidateModuleRedirect();

        $this->ValidateSession();
    }

    private function CreateSessionModules($arrayModulesVo,$arrayUserModulesVo) {
        $_SESSION['user']['module']['totalModules'] = count($arrayUserModulesVo);
        for ($i=0; $i < count($arrayUserModulesVo); $i++) {
          $moduleVo = $arrayModulesVo[$i];
          $userModulesVo = $arrayUserModulesVo[$i];
          $_SESSION['user']['module']['name'.$i] = $moduleVo->name;
          $_SESSION['user']['module'][$moduleVo->name] = $userModulesVo->state;
        }
    }

    public function ValidateSession() {
        if(!session_id()){
            session_start();
        }
        if (isset($_SESSION['user'])){
            header("Location: " . Config::REDIRECTS . Config::GENERAL . "index.php".$_SESSION['user']['typeAccess']['url']);
        }
    }

    public function ValidateModuleRedirect() {
        if(!session_id()){
            session_start();
        }
        if (isset($_SESSION['user'])){
            for ($i=0; $i < $_SESSION['user']['module']['totalModules'] ; $i++) {
                $name = $_SESSION['user']['module']['name'.$i];
                if($_SESSION['user']['typeAccess']['id'] == 1){
                  if($_SESSION['user']['module'][$name] == 1){
                      if($name == "Perfiles"){
                          header("Location: " . Config::REDIRECTS . Config::GENERAL . "index.php?view=adminUser");
                      }else if($name == "Mi Proyecto"){
                          header("Location: " . Config::REDIRECTS . Config::GENERAL . "index.php?view=adminProjects");
                      }else if($name == "Post Venta"){
                          header("Location: " . Config::REDIRECTS . Config::GENERAL . "index.php?view=adminSales");
                      }else if($name == "Acabados"){
                          header("Location: " . Config::REDIRECTS . Config::GENERAL . "index.php?view=adminFinished");
                      }else if($name == "News"){
                          header("Location: " . Config::REDIRECTS . Config::GENERAL . "index.php?view=adminNews");
                      }else if($name == "Reportes"){
                          header("Location: " . Config::REDIRECTS . Config::GENERAL . "index.php?view=adminNews");
                      }
                  }
                }else if($_SESSION['user']['typeAccess']['id'] == 2){
                  if($_SESSION['user']['module'][$name] == 1){
                      if($name == "Perfiles"){
                          header("Location: " . Config::REDIRECTS . Config::GENERAL . "index.php?view=clientUser");
                      }else if($name == "Mis Documentos"){
                          header("Location: " . Config::REDIRECTS . Config::GENERAL . "index.php?view=clientDocuments");
                      }else if($name == "Mi Proyecto"){
                          header("Location: " . Config::REDIRECTS . Config::GENERAL . "index.php?view=clientMyProject");
                      }else if($name == "Estado Cuenta"){
                          header("Location: " . Config::REDIRECTS . Config::GENERAL . "index.php?view=clientAccount");
                      }else if($name == "Post Venta"){
                          header("Location: " . Config::REDIRECTS . Config::GENERAL . "index.php?view=clientRequest");
                      }
                  }
                }
            }
        }
    }

    public function DestroySesion() {
        if (!isset($_SESSION["user"])) {
            session_start();
        }
        session_unset();
        session_destroy();
        $_SESSION["user"] = null;
        unset($_SESSION['user']);
    }

    public function GetSessionRoleVo() {
        if (!isset($_SESSION['user']['role'])) {
            session_start();
        }
        $roleVo = new RoleVo();
        $roleVo->id = $_SESSION['user']['role']['id'];
        $roleVo->names = $_SESSION['user']['role']['name'];
        $roleVo->idTypeAccess = $_SESSION['user']['role']['idTypeAccess'];
        return $roleVo;
    }

    public function GetSessionTypeAccessVo() {
        if (!isset($_SESSION['user']['typeAccess'])) {
            session_start();
        }
        $typeAccessVo = new TypeAccessVo();
        $typeAccessVo->id = $_SESSION['user']['typeAccess']['id'];
        $typeAccessVo->name = $_SESSION['user']['typeAccess']['name'];
        $typeAccessVo->url = $_SESSION['user']['typeAccess']['url'];

        return $typeAccessVo;
    }

    public function GetSessionAccessVo() {
        if (!isset($_SESSION['user']['access'])) {
            session_start();
        }
        $accessVo = new AccessVo();
        $accessVo->id = $_SESSION['user']['access']['id'];
        $accessVo->idUser = $_SESSION['user']['access']['idUser'];
        $accessVo->nickName = $_SESSION['user']['access']['nickName'];
        $accessVo->state = $_SESSION['user']['access']['state'];
        return $accessVo;
    }


}
*/
