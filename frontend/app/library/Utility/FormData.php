<?php

class FormData extends \Phalcon\Mvc\User\Component
{
    static public function parseFormDataByKey($formData) {
        $data = array();
        foreach ($formData as $key => $value) {
            if (is_numeric(stripos($key, "_"))) {
                $keyParsers = explode("_", $key);
                $identity   = end($keyParsers);

                if (is_numeric($identity)) {
                    array_pop($keyParsers);
                    $key                   = implode('_', $keyParsers);
                    $data[$identity][$key] = $value;
                }
            }
        }

        return $data;
    }
}
