<?php

/**
 * ManageDates
 *
 * class responsible for manage Arrays
 *
 * @author		Harold D Duque C
 * @version		1.0
 * @since		Oct of 2016
 */
class ManageArrays {

    public static function RemoveDuplicateElements($array) {
        $tempObj = null;
        $newArray = Array();
        $exists = false;

        for ($i = 0; $i < count($array); $i++) {
            $tempObj = $array[$i];
            if ($i == 0) {
                $newArray[] = $tempObj;
            } else if ($i > 0) {
                $exists = false;
                for ($j = 0; $j < count($newArray); $j++) {
                    if ($tempObj == $newArray[$j]) {
                        $exists = true;
                    }
                }
                if (!$exists) {
                    $newArray[] = $tempObj;
                }
            }
        }
        return $newArray;
    }

    public static function ObjectToArray($obj)
    {
        if(is_array($obj) || is_object($obj))
        {
            $result = array();
            foreach($obj as $key => $value) {
                $result[$key] = ManageArrays::ObjectToArray($value);
            }
            return $result;
        }
        return $obj;
    }

    public static function LoadDataPostControl($array,$obj) {
            foreach ($array as $key => $value){
                $obj->$key = $value;
            }
            return $obj;
    }

    public static function ObjectToJson($object) {
        return json_encode(ManageArrays::ObjectToArray($object));
    }

}
