<?php

/**
 * AjaxControlsConfigurationData
 *
 * class that contains all the fields in a table
 * 
 * @author		Harold D Duque C
 * @version		1.0
 * @since		Oct of 2016
 */
class AjaxControlConfigDataLst {
    
    /**
     * id input type text
     * @var string 
     */
    public $idCtx;
    /**
     * id input type select
     * @var string 
     */
    public $idLst;
    /**
     * @var string 
     */
    public $idButton;    
    /**
     * id container input ctx
     * @var string
     */
    public $idContainerCtx;
    /**
     * id container input lst
     * @var string
     */    
    public $idContainerLst;
    /**
     * id container input button
     * @var string
     */    
    public $idContainerButton;
    /**
     * id container control
     * @var string
     */    
    public $idContainerGeneral;
    /**
     * @var string 
     */
    public $loadUrl;
    /**
     * @var string 
     */
    public $funcOnchange;    
    
    /**
     * Constructor.
     */
    public function __construct() {
        $this->idCtx = null;
        $this->idLst = null;
        $this->idButton = null;
        $this->idContainerCtx = null;
        $this->idContainerLst = null;
        $this->idContainerButton = null;
        $this->idContainerGeneral = null;
        $this->loadUrl = null;
        $this->funcOnchange = null;
    }
    
}
