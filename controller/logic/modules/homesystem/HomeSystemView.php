<?php

require_once(Config::PATH . Config::GENERAL . 'GeneralMenu.php');
require_once(Config::PATH . Config::GENERAL . 'AdminSession.php');

/**
 * HomeSystemView
 *
 * class responsible for loading the html
 *
 * @author		Harold D Duque C
 * @version		1.0
 * @since		Oct of 2016
 */
class HomeSystemView {

    /**
     * Class for paint the html.
     *
     * @var NokTemplate
     */
    private $nokTemplate;

     /**
     *
     * @var GeneralMenu
     */
    private $generalMenu;

    /**
     *
     * @var AdminSession
     */
    private $adminSession;

    /**
     * Constructora.
     *
     * @param NokTemplate $nokTemplate
     */
    public function __construct($nokTemplate) {
        $this->nokTemplate = $nokTemplate;
        $this->generalMenu = new GeneralMenu($this->nokTemplate);

        $this->adminSession = new AdminSession();
        //$this->adminSession->ValidateSession();
        //$this->adminSession->PaintNameUser($this->nokTemplate);
        $this->Paint();
    }

    /**
     * Pinta la vista.
     *
     */
    private function Paint() {
        $this->Cargar();
        $this->Imprimir();
    }

    /**
     * Carga los html.
     *
     */
    private function Cargar() {
        $this->nokTemplate->cargar("template", "html/template.html");
        $this->nokTemplate->cargar("content", "html/homeSystem.html");
    }

    /**
     * Imprime la vista.
     *
     */
    private function Imprimir() {
        $this->nokTemplate->expandir("content", "content");
        $this->nokTemplate->expandir("FINAL", "template");
        $this->nokTemplate->imprimir("FINAL");
    }


}
