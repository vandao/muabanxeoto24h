<?php

abstract class Alert extends \Phalcon\Mvc\User\Component {
    
    static public function success($string) {
        $html = '
            <div class="alert alert-success">
                <i class="fa fa-check-circle"></i>
                ' . $string . '
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            </div>';

        return $html;
    }

    static public function info($string) {
        $html = '
            <div class="alert alert-info">
                <i class="fa fa-exclamation-circle"></i>
                ' . $string . '
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            </div>';

        return $html;
    }

    static public function warning($string, $type) {
        $html = '
            <div class="alert alert-warning">
                <i class="fa fa-warning"></i>
                ' . $string . '
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            </div>';

        return $html;
    }
    
    static public function error($string, $type) {
        $html = '
            <div class="alert alert-danger">
                <i class="fa fa-times-circle"></i>
            ' . $string . '
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            </div>';

        return $html;
    }
}