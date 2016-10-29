<?php

/**
 * GeneralSearchVo
 *
 * class that contains all the fields in a table
 * 
 * @author		Harold D Duque C
 * @version		1.0
 * @since		Oct of 2016
 */
class GeneralSearchVo {

    public $nameTable; 
    public $nameFieldId;
    public $nameField;
    public $searchField;
    public $valueSearchField;
    public $isSearchFieldString;
    public $numOrderField;
    public $typeOrderField;


    /**
     * Construct
     */
    public function __construct() {
        $this->nameTable = null; 
        $this->nameFieldId = null;
        $this->nameField = null;
        $this->searchField = null;
        $this->valueSearchField = null;
        $this->isSearchFieldString = null;
        $this->numOrderField = null;
        $this->typeOrderField = null;
    }

}
