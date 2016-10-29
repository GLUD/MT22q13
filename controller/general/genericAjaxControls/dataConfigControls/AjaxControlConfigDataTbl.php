<?php

/**
 * AjaxControlConfigDataTbl
 *
 * class that contains all the fields in a table
 * 
 * @author		Harold D Duque C
 * @version		1.0
 * @since		Oct of 2016
 */
class AjaxControlConfigDataTbl {

    /**
     * id table html
     * @var string 
     */
    public $idTbl;    
    
    /**
     * id data filter of data base
     * @var string 
     */
    public $idFilter;    
    
    /**
     * is report ?
     * @var string 
     */
    public $isReport;    
    
    /**
     * id container table html
     * @var string
     */
    public $idContainerTbl;
    
    /**
     * @var string 
     */
    public $loadUrl;
    
    /**
     * Constructor.
     */
    public function __construct() {
        $this->idTbl = null;
        $this->idFilter = null;
        $this->isReport = null;
        $this->idContainerTbl = null;
        $this->loadUrl = null;
    }
    
}
