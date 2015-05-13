<?php

use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Check;

class PermissionStaffGroupForm extends FormBase
{
    public function initialize()
    {
        parent::initialize();
        
        // Staff group
        $staffGroupId = new Select("staff_group_id", StaffGroup::find(), array(
            'using' => array('id', 'staff_group')
        ));

        $staffGroupId = $this->setLabels($staffGroupId, 'Staff-Group', false);
        $staffGroupId = $this->setRequireField($staffGroupId);
        $this->add($staffGroupId);
        
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