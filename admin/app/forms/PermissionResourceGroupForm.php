<?php

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Check;

class PermissionResourceGroupForm extends FormBase
{
    public function initialize()
    {
        parent::initialize();
        
        // Name
        $name = new Text('name');
        $name = $this->setLabels($name, 'Name');
        $name = $this->setRequireField($name);
        $this->add($name);
        
        // Filter prefix
        $filterPrefix = new Text('filter_prefix');
        $filterPrefix = $this->setLabels($filterPrefix, 'Filter-Prefix');
        $filterPrefix = $this->setRequireField($filterPrefix);
        $this->add($filterPrefix);

        // Is Disabled
        $isDisabled = new Check('is_disabled');
        $isDisabled = $this->setLabels($isDisabled, 'Is-Disabled', false);
        $this->add($isDisabled);
    }

    public function changeModeNew() {
    }

    public function changeModeEdit() {

    }
}