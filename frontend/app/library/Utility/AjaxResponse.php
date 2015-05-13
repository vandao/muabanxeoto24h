<?php

class AjaxResponse extends \Phalcon\Mvc\User\Component {

    static public function toJson($status = '', $message = array(), $data = array())
    {
        $response = array(
            'status'  => $status,
            'message' => $message,
            'data'    => $data
        );

        return json_encode($response);
    }

    static public function toArray($status = '', $message = array(), $data = array())
    {
        $response = array(
            'status'  => $status,
            'message' => $message,
            'data'    => $data
        );

        return $response;
    }
}