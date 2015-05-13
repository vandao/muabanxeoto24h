<?php

class PermissionStaffGroupDbTable extends \Phalcon\Mvc\Model
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
    public $group_id;

    /**
     *
     * @var string
     */
    public $controller_name;

    /**
     *
     * @var string
     */
    public $action_name;

    /**
     *
     * @var integer
     */
    public $is_allow;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('group_id', 'StaffGroup', 'id', NULL);
    }

}
