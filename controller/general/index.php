<?php
require_once ('Config.php');
require_once (Config::PATH.Config::CONTROLLER_LIB.'NokTemplate.php');

setlocale(LC_ALL, "Spanish");
ini_set("default_charset", "utf-8");
date_default_timezone_set("America/Bogota");
header("Content-Type: text/html;charset=utf-8");
//htmlentities();
$nokTemplate = new NokTemplate("../../view");

switch (@$_GET['view']) {

    case "login": {
            require_once(Config::PATH . Config::MODULES . 'login/LoginView.php');
            $vista = new LoginView($nokTemplate);
            break;
        }
    case "home": {
            require_once(Config::PATH . Config::MODULES . 'homesystem/HomeSystemView.php');
            $vista = new HomeSystemView($nokTemplate);
            break;
        }
    case "reports": {
            require_once(Config::PATH . Config::MODULES . 'reportes/ReportesView.php');
            $vista = new ReportesView($nokTemplate);
            break;
        }
     case "setUser": {
               require_once(Config::PATH . Config::MODULES . 'user/SetUserView.php');
               $vista = new SetUserView($nokTemplate);
               break;
          }
      case "musicalSecuence": {
                require_once(Config::PATH . Config::MODULES . 'musicalSecuence/MusicalSecuenceView.php');
                $vista = new MusicalSecuenceView($nokTemplate);
                break;
           }
     case "updateUserModules": {
               require_once(Config::PATH . Config::MODULES . 'userModules/UpdateUserModulesView.php');
               $vista = new UpdateUserModulesView($nokTemplate);
               break;
         }

     case "ajaxUpdateUserModules": {
               require_once(Config::PATH . Config::MODULES . 'userModules/AjaxUpdateUserModulesView.php');
               $vista = new AjaxUpdateUserModulesView();
               break;
         }

     case "setAccessView": {
               require_once(Config::PATH . Config::MODULES . 'access/SetAccessView.php');
               $vista = new SetAccessView($nokTemplate);
               break;
          }
     case "ajaxControlLst": {
            require_once(Config::PATH . Config::GENERAL . 'genericAjaxControls/AjaxControlLstView.php');
            $vista = new AjaxControlLstView($nokTemplate);
            break;
          }
     case "ajaxControlWithFilterLst": {
            require_once(Config::PATH . Config::GENERAL . 'genericAjaxControls/AjaxControlWithFilterLstView.php');
            $vista = new AjaxControlWithFilterLstView($nokTemplate);
            break;
          }
    case "ajaxControlTbl": {
            require_once(Config::PATH . Config::GENERAL . 'genericAjaxControls/AjaxManagerControlTblView.php');
            $vista = new AjaxManagerControlTblView($nokTemplate);
            break;
        }
    case "manajerReportExcelTbl": {
            require_once(Config::PATH . Config::GENERAL . 'genericReports/ManajerReportExcelTbl.php');
            $vista = new ManajerReportExcelTbl($nokTemplate);
            break;
        }
    case "manajerReportPdfTbl": {
            require_once(Config::PATH . Config::GENERAL . 'genericReports/ManajerReportPdfTbl.php');
            $vista = new ManajerReportPdfTbl($nokTemplate);
            break;
        }
    case "exit": {
              require_once(Config::PATH . Config::GENERAL . 'AdminSession.php');
              $adminSession = new AdminSession();
              $adminSession->DestroySesion();
              header("Location: ".  Config::REDIRECTS . Config::GENERAL . 'index.php');
              break;
         }
    default: {
            require_once(Config::PATH . Config::MODULES . 'login/LoginView.php');
            $vista = new LoginView($nokTemplate);
            break;
        }
}
