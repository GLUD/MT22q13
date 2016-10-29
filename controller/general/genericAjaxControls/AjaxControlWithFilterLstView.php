<?php

require_once(Config::PATH . Config::GENERAL . 'AdminSession.php');
require_once(Config::PATH . Config::GENERAL . 'ManageArrays.php');
require_once(Config::PATH . Config::BACKEND . 'general/vos/GeneralSearchVo.php');
require_once(Config::PATH . Config::GENERAL . 'genericAjaxControls/dataConfigControls/AjaxControlConfigDataLst.php');
require_once(Config::PATH . Config::GENERAL . 'genericAjaxControls/comp/AjaxControlWithFilterLst.php');

/**
 * AjaxControlWithFilterLstView
 *
 * class responsible for loading the html
 *
 * @author		Harold D Duque C
 * @version		1.0
 * @since		Oct of 2016
 */
class AjaxControlWithFilterLstView {

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
     * @var AjaxControlWithFilterLst
     */
    private $ajaxControlWithFilterLst;

    /**
     * @var AjaxControlConfigDataLst
     */
    private $ajaxControlConfigDataLst;

    /**
     * @var GeneralSearchVo
     */
    private $generalSearchVo;

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
        //$this->adminSession->PaintNameUser($this->nokTemplate);
    }

    private function ValidateLoadPost(){
        if (isset($_POST) && !empty($_POST)) {
            $this->LoadDataPost($_POST);
            $this->CreateComponent();
            $this->Paint();
        }else{
            header("Location: " . Config::REDIRECTS . Config::GENERAL . "index.php?view=home");
        }
    }

    private function LoadDataPost($post) {
        $this->ajaxControlConfigDataLst = new AjaxControlConfigDataLst();
        ManageArrays::LoadDataPostControl($post["ajaxControlConfigDataLst"],$this->ajaxControlConfigDataLst);
        $this->generalSearchVo = new GeneralSearchVo();
        ManageArrays::LoadDataPostControl($post["dataQueryComponentAjax"],$this->generalSearchVo);
    }

    private function LoadDataPostControl($array,$obj) {
            foreach ($array as $key => $value){
                $obj->$key = $value;
            }
            return $obj;
    }

    private function CreateComponent() {
        $this->ajaxControlWithFilterLst = new AjaxControlWithFilterLst($this->ajaxControlConfigDataLst,$this->generalSearchVo);
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
        $this->ajaxControlWithFilterLst->Paint($this->nokTemplate);
    }

    /**
     * Carga los html.
     *
     */
    private function LoadHTML() {
        $this->nokTemplate->cargar("content", "html/ajaxControls.html");
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
