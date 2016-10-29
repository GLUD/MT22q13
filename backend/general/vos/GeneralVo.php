<?php

/**
 * GeneralVo
 *
 * class that contains all the fields in a table
 * 
 * @author		Harold D Duque C
 * @version		1.0
 * @since		Oct of 2016
 */
class GeneralVo {

    /**
     * id.
     *
     * @var int
     */
    public $id;
    /**
     * name.
     *
     * @var string
     */
    public $name;
 
    /**
     * Constructora.
     *
     */
    public function __construct() {
        $this->id = null;
        $this->name = null;
    }

}
