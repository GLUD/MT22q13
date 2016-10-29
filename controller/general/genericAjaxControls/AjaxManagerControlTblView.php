<?php

require_once(Config::PATH . Config::GENERAL . 'AdminSession.php');
require_once(Config::PATH . Config::GENERAL . 'ManageArrays.php');
require_once(Config::PATH . Config::BACKEND . 'general/vos/GeneralSearchVo.php');
require_once(Config::PATH . Config::GENERAL . 'genericControls/ManagerTbls.php');
require_once(Config::PATH . Config::GENERAL . 'genericAjaxControls/dataConfigControls/AjaxControlConfigDataTbl.php');

/**
 * AjaxManagerControlTblView
 *
 * class responsible for creating all tables html
 *
 * @author		Harold D Duque C
 * @version		1.0
 * @since		Oct of 2016
 */
class AjaxManagerControlTblView {

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
     * array of all identifiers tables html
     * @var array
     */
    private $idsTblsArray;

    /**
     * @var AjaxControlConfigDataTbl
     */
    private $ajaxControlConfigDataTbl;

    /**
     * object table
     * @var mixed
     */
    private $tbl;

    /**
     * Constructor.
     *
     * @param NokTemplate $nokTemplate
     */
    public function __construct($nokTemplate) {
        $this->nokTemplate = $nokTemplate;
        //$this->adminSession = new AdminSession();
        //$this->adminSession->ValidateSession();
        $this->ValidateLoadPost();
    }

    private function ValidateLoadPost(){
        if (isset($_POST) && !empty($_POST)) {
            $this->LoadDataPost($_POST);
            $this->LoadTablesIds();
            $this->LoadComponent();
            $this->Paint();
        }else{
            header("Location: " . Config::REDIRECTS . Config::GENERAL . "index.php?view=home");
        }
    }

    private function LoadDataPost($post) {
        $this->ajaxControlConfigDataTbl = new AjaxControlConfigDataTbl();
        ManageArrays::LoadDataPostControl($post["ajaxControlConfigDataTbl"],$this->ajaxControlConfigDataTbl);
    }

    private function LoadTablesIds() {
        $this->idsTblsArray[] = "userTbl";
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
     *
     */
    private function PrintHTML() {
        $this->nokTemplate->expandir("FINAL", "content");
        $this->nokTemplate->imprimir("FINAL");
    }

}
