<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class PermissionStaff extends PermissionStaffDbTable
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
     * Get staff permission
     * @param int $roleId
     * @return array
     */
    static public function getStaffPermissions($staffId)
    {
        $permissions = array();
        foreach (PermissionStaff::find("staff_id = '$staffId'") as $permission) {
            $permissions[$permission->controller_name][$permission->action_name] = array(
                "is_custom" => $permission->is_custom,
                "is_allow"  => $permission->is_allow
            );
        }

        return $permissions;
    }

    /**
     * Update permission
     * @param  array $data(staff_id, controller_name, action_name, is_allow, is_custom)
     * @return string
     */
    static public function updatePermission($data)
    {
        $permissionStaff = PermissionStaff::findFirst(array(
            "conditions" => "staff_id = ?1 AND controller_name = ?2 AND action_name = ?3",
            "bind"       => array(
                1 => $data['staff_id'],
                2 => $data['controller_name'],
                3 => $data['action_name']
            )
        ));
        if (! $permissionStaff) $permissionStaff = new PermissionStaff();

        if (! $data['is_custom']) {
            if ($permissionStaff->is_custom) return 'ingore';
        } else {
            $data['is_custom'] = 1;
        }

        if ($permissionStaff->save($data)) {
            if ($data['is_custom']) {
                Staff::setCustomPermission($data['staff_id'], 1);
            }

            return 'success';
        } else {
            echo current($permissionStaff->getMessages())->getMessage();exit;
        }
    }

    /**
     * Update permission by staff group
     * @param  array $data(group_id, controller_name, action_name, is_allow)
     */
    static public function updatePermissionByStaffGroup($data)
    {
        foreach (Staff::find("staff_group_id = '" . $data['group_id'] . "'") as $staff) {
            $data['staff_id']  = $staff->id;
            $data['is_custom'] = 0;

            PermissionStaff::updatePermission($data);
        }
    }

    /**
     * Regenerate permission by staff group
     * @param  int $staffId
     */
    static public function regeneratePermissionByStaff($staffId) {
        $staff = Staff::findFirst($staffId);

        if ($staff) {
            PermissionStaff::resetPermisison($staffId);

            foreach (PermissionStaffGroup::find("group_id = '$staff->staff_group_id'") as $permission) {
                $data = array(
                    'staff_id'        => $staffId,
                    'controller_name' => $permission->controller_name,
                    'action_name'     => $permission->action_name,
                    'is_allow'        => $permission->is_allow,
                    'is_custom'       => 0
                );
                PermissionStaff::updatePermission($data);
            }
        }
    }

    /**
     * Reset permission
     * @param  int $staffId
     */
    static public function resetPermisison($staffId) {
        Staff::setCustomPermission($staffId, 0);

        foreach (PermissionStaff::find("staff_id = '$staffId'") as $permisison) {
            $permisison->delete();
        }
    }
}