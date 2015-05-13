<?php

class PermissionStaffDbTable extends \Phalcon\Mvc\Model
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
    public $staff_id;

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
     *
     * @var integer
     */
    public $is_custom;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('staff_id', 'Staff', 'id', NULL);
    }

}
