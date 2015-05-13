<?php

use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Check;

class PermissionStaffForm extends FormBase
{
    public function initialize()
    {
        parent::initialize();
        
        // Staff group
        $staffId = new Select("staff_id", Staff::find(), array(
            'using' => array('id', 'full_name')
        ));

        $staffId = $this->setLabels($staffId, 'Staff', false);
        $staffId = $this->setRequireField($staffId);
        $this->add($staffId);
        
        // Controller name
        $controllerName = new Text('controller_name');
        $controllerName = $this->setLabels($controllerName, 'Controller-Name');
        $controllerName = $this->setRequireField($controllerName);
        $this->add($controllerName);
        
        // Action name
        $actionName = new Text('action_name');
        $actionName = $this->setLabels($actionName, 'Action-Name');
        $actionName = $this->setRequireField($actionName);
        $this->add($actionName);

        // Is allow
        $isAllow = new Check('is_allow');
        $isAllow = $this->setLabels($isAllow, 'Is-Allow', false);
        $isAllow = $this->setRequireField($isAllow);
        $this->add($isAllow);
    }
}