<?php

require_once(Config::PATH . Config::GENERAL . 'genericAjaxControls/dataConfigControls/AjaxControlConfigDataTbl.php');

require_once(Config::PATH . Config::MODULES . 'user/comp/UserTbl.php');


/**
 * AjaxManagerTbls
 *
 * class responsible for creating all tables html
 *
 * @author		Harold D Duque C
 * @version		1.0
 * @since		Oct of 2016
 */
class ManagerTbls {

    /**
     * ReturnComponent
     * @param AjaxControlConfigDataTbl $ajaxControlConfigDataTbl
     */
    public static function ReturnComponentTbl($ajaxControlConfigDataTbl) {
        if($ajaxControlConfigDataTbl->idTbl === "userTbl"){
            $tbl = new UserTbl();
        }

        if($ajaxControlConfigDataTbl->idFilter !=null || $ajaxControlConfigDataTbl->idFilter !=""){
            $tbl->idFilter = $ajaxControlConfigDataTbl->idFilter;
        }
        if($ajaxControlConfigDataTbl->isReport !=null || $ajaxControlConfigDataTbl->isReport !=""){
            $tbl->isReport = $ajaxControlConfigDataTbl->isReport;
        }
        return $tbl;
    }

}
