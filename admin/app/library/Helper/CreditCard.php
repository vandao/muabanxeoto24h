<?php

abstract class CreditCard extends \Phalcon\Mvc\User\Component {
    
    static public function getCardType($number) {
        $number=preg_replace('/[^\d]/','',$number);
        if (preg_match('/^3[47]/',$number)) {
            return 'American Express';
        } elseif (preg_match('/^3(?:0[0-5]|[68][0-9])[0-9]{11}/',$number)) {
            return 'Diners Club';
        } elseif (preg_match('/^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)/',$number)) {
            return 'Discover';
        } elseif (preg_match('/^35(2[89]|[3-8][0-9])/',$number)) {
            return 'JCB';
        } elseif (preg_match('/^5[1-5]/',$number)) {
            return 'MasterCard';
        } elseif (preg_match('/^4/',$number)) {
            return 'Visa';
        } elseif (preg_match('/^(4026|417500|4508|4844|491(3|7))/',$number)) {
            return 'Visa Electron';
        } else {
            return 'Unknown';
        }
    }
}