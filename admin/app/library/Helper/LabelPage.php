<?php

class LabelPage extends \Phalcon\Mvc\User\Component {

    static public function header($pageName, $variables = array())
    {
        $label = new Label();

        $variableKey = '';
        foreach ($variables as $key => $value) {
            $variableKey .= $key;
        }

        return $label->page($pageName . '-Header' . $variableKey, true, $variables);
    }

    static public function title($pageName, $variables = array())
    {
        $label = new Label();

        $variableKey = '';
        foreach ($variables as $key => $value) {
            $variableKey .= $key;
        }

        return $label->page($pageName . '-Title' . $variableKey, false, $variables);
    }
}