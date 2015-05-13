<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class PermissionStaffGroup extends PermissionStaffGroupDbTable
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
        // $this->validate(new Uniqueness(array(
        //     'field'   => 'key',
        //     'message' => 'Sorry, your key was registered'
        // )));

        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    /**
     * Get staff group permission
     * @param int $staffGroupId
     * @return array
     */
    static public function getStaffGroupPermissions($staffGroupId)
    {
        $permissions = array();
        foreach (PermissionStaffGroup::find("group_id = '$staffGroupId'") as $permission) {
            $permissions[$permission->controller_name][$permission->action_name] = array(
                "is_allow"  => $permission->is_allow
            );
        }

        return $permissions;
    }

    /**
     * Update permission
     * @param  array $data(staff_group_id, controller_name, action_name, is_allow)
     * @return string
     */
    static public function updatePermission($data)
    {
        $permissionStaffGroup = PermissionStaffGroup::findFirst(array(
            "conditions" => "controller_name = ?1 AND action_name = ?2",
            "bind"       => array(
                1 => $data['controller_name'],
                2 => $data['action_name']
            )
        ));
        if (! $permissionStaffGroup) $permissionStaffGroup = new PermissionStaffGroup();

        $data['group_id'] = $data['staff_group_id'];

        if ($permissionStaffGroup->save($data)) {
            PermissionStaff::updatePermissionByStaffGroup($data);

            return 'success';
        } else {
            return current($permissionStaffGroup->getMessages())->getMessage();
        }
    }
}