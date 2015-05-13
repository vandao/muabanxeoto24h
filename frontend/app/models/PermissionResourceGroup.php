<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class PermissionResourceGroup extends PermissionResourceGroupDbTable
{
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
    }

    public function beforeValidationOnCreate()
    {
    }
    
    public function beforeValidationOnUpdate()
    {
    }

    public function validation()
    {
        $this->validate(new Uniqueness(array(
            'field'   => 'name',
            'message' => LabelMessage::rowExisted('Name')
        )));

        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    static public function fetchFormPairs($includeEmptyValue = false)
    {
        if ($includeEmptyValue) {
            $data = array("" => "");
        } else {
            $data = array();
        }
        foreach (PermissionResourceGroup::find() as $permissionResourceGroup) {
            $data[$permissionResourceGroup->id] = $permissionResourceGroup->name;
        }

        return $data;
    }

    /**
     * Get resource group name by action
     * @param string $actionName
     * @return string
     */
    public function getIdByActionName($actionName) {
        preg_match_all("/[A-Z]/", $actionName, $matches);

        foreach ($matches[0] as $letter) {
            $actionName = str_replace($letter, '-' . strtolower($letter), $actionName);
        }
        $prefix = current(explode("-", $actionName));

        $group = PermissionResourceGroup::findFirst("filter_prefix LIKE '%$prefix%'");

        if ($group) {
            return $group->id;
        }

        $group = PermissionResourceGroup::findFirst();
        return $group->id;
    }
}