<?php

require_once (Config::PATH . Config::BACKEND . 'role/vos/RoleVo.php');
require_once (Config::PATH . Config::BACKEND . 'module/vos/ModuleVo.php');
require_once (Config::PATH . Config::BACKEND . 'userModules/vos/UserModulesVo.php');
require_once (Config::PATH . Config::BACKEND . 'access/vos/AccessVo.php');
require_once (Config::PATH . Config::BACKEND . 'user/vos/UserVo.php');

/**
 * UserGroupVo
 *
 * class contains objects data for user
 *
 * @author		Harold D Duque C
 * @version		1.0
 * @since		Oct of 2016
 */

class UserGroupVo {

   /**
     * @var RoleVo
     */
    public $roleVo;
    /**
     * @var ModuleVo
     */
    public $moduleVo;
    /**
     * @var UserModulesVo
     */
    public $userModulesVo;
    /**
     * @var AccessVo
     */
    public $accessVo;
    /**
     * @var UserVo
     */
    public $userVo;

    public function __construct() {
      $this->roleVo = new RoleVo();
      $this->moduleVo = new ModuleVo();
      $this->userModulesVo = new UserModulesVo();
      $this->accessVo = new AccessVo();
      $this->userVo = new UserVo();
      
    }

}
