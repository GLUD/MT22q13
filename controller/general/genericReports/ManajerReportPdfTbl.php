<?php

require_once(Config::PATH . Config::GENERAL . 'AdminSession.php');
require_once(Config::PATH . Config::GENERAL . 'ManageArrays.php');
require_once(Config::PATH . Config::GENERAL . 'genericControls/ManagerTbls.php');
require_once(Config::PATH . Config::GENERAL . 'genericAjaxControls/AjaxManagerControlTblView.php');
require_once(Config::PATH . Config::GENERAL . 'genericAjaxControls/dataConfigControls/AjaxControlConfigDataTbl.php');
require_once(Config::PATH . Config::CONTROLLER_LIB . 'tcpdf/tcpdf.php');

//header("Content-type:  application/x-msexcel");
//header("Content-Disposition: attachment; filename=\"Report_Table_".date("d-m-Y H:i:s").".xls\"");
/**
 * ManajerReportPdfTbl
 *
 * class responsible for creating all tables html  
 * 
 * @author		Harold D Duque C
 * @version		1.0
 * @since		Oct of 2016
 */
class ManajerReportPdfTbl {
    
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
            $this->CreateFilePdf();
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
        $this->PaintComponent();
    }     
    
    private function CreateFilePdf() {
        
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Report_MT22q13');
        
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING, array(3,126,140), array(2,73,89));
        $pdf->setFooterData(array(3,126,140), array(2,73,89));        
        
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        
        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        
        $pdf->AddPage();
        
        
        $html = $this->tbl->tbl->conHtm();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('report_Scrummini.pdf', 'I');        
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
