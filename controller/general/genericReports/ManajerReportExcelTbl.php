<?php

require_once(Config::PATH . Config::GENERAL . 'AdminSession.php');
require_once(Config::PATH . Config::GENERAL . 'ManageArrays.php');
require_once(Config::PATH . Config::GENERAL . 'genericControls/ManagerTbls.php');
require_once(Config::PATH . Config::GENERAL . 'genericAjaxControls/AjaxManagerControlTblView.php');
require_once(Config::PATH . Config::GENERAL . 'genericAjaxControls/dataConfigControls/AjaxControlConfigDataTbl.php');

header("Content-type:  application/x-msexcel");
header("Content-Disposition: attachment; filename=\"Report_Table_".date("d-m-Y H:i:s").".xls\"");
/**
 * ManajerReportExcelTbl
 *
 * class responsible for creating all tables html  
 * 
 * @author		Harold D Duque C
 * @version		1.0
 * @since		Oct of 2016
 */
class ManajerReportExcelTbl {
    
    /**
     * Class for paint the html.
     * @var NokTemplate
     */
    private $nokTemplate;
    
    /**
     * @var AdminSession 
     */
    private $adminSession;    
    
    /**
     * object table 
     * @var mixed 
     */
    private $tbl;    
    
    /**
     * @var AjaxControlConfigDataTbl
     */
    private $ajaxControlConfigDataTbl;
    
    /**
     * Constructor.
     *
     * @param NokTemplate $nokTemplate
     */
    public function __construct($nokTemplate) {
        $this->nokTemplate = $nokTemplate;
        $this->adminSession = new AdminSession();
//        $this->adminSession->ValidateSession();
        $this->ValidateLoadPost();
    }    
    
    
    private function ValidateLoadPost(){
        if (isset($_GET) && !empty($_GET)) {
            $this->LoadDataPost();
            $this->LoadComponent();
            $this->Paint();
        }
    }
    
    private function LoadDataPost() {
        $this->ajaxControlConfigDataTbl = new AjaxControlConfigDataTbl();       
        $this->ajaxControlConfigDataTbl->idTbl = $_GET['idTbl'];
        $this->ajaxControlConfigDataTbl->idFilter = $_GET["idFilter"];
        $this->ajaxControlConfigDataTbl->isReport = true;
    }    

    private function LoadComponent() {
        $this->tbl = ManagerTbls::ReturnComponentTbl($this->ajaxControlConfigDataTbl);
    }     
    
    /**
     * Paint the view.
     */
    private function Paint() {
        $this->LoadHTML();
        $this->PaintComponent();
        $this->PrintHTML();
    }    
    
    private function PaintComponent() {    
        $this->tbl->Paint($this->nokTemplate);
    }
    
    /**
     * Load the html.
     */
    private function LoadHTML() {
        $this->nokTemplate->cargar("content", "html/ajaxControlTbl.html");
    }

    /**
     * Imprime la vista.
     */
    private function PrintHTML() {
        $this->nokTemplate->expandir("FINAL", "content");
        $this->nokTemplate->imprimir("FINAL");
    }      
    
}
