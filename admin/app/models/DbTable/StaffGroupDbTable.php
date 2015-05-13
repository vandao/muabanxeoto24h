<?php

class StaffGroupDbTable extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $is_disabled;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'PermissionStaffGroup', 'group_id', NULL);
        $this->hasMany('id', 'Staff', 'staff_group_id', NULL);
        $this->hasMany('id', 'StaffGroupLanguage', 'staff_group_id', NULL);
    }

}
