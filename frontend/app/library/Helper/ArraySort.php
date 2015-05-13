<?php

abstract class ArraySort extends \Phalcon\Mvc\User\Component {

    static public function byNatsort ($arrayIn = array(), $index = null, $type = 'low') {
        $arrTemp  = array();
        $arrayOut = array();

        foreach ($arrayIn as $key => $value) {
            reset($value);
            $arrTemp[$key] = is_null($index)
                                ? current($value)
                                : $value[$index];
        }

        if ($type == 'low') {
            natsort($arrTemp);
        } else {
            arsort($arrTemp);
        }

        foreach ( $arrTemp as $key=>$value ) {
            $arrayOut[$key] = $arrayIn[$key];
        }

        return $arrayOut;
    }
}