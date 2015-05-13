<?php

use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\PresenceOf;

class SystemConfigForm extends FormBase
{
    public function initialize()
    {
        parent::initialize();
        
        // Key
        $key = new Text('key');
        $key = $this->setLabels($key, 'Key');
        $key = $this->setRequireField($key);
        $this->add($key);
        
        // Value
        $value = new Text('value');
        $value = $this->setLabels($value, 'Value');
        // $value = $this->setRequireField($value);
        $this->add($value);
    }

    public function changeModeNew() {
    }

    public function changeModeEdit() {

    }
}