<?php

class PermissionResourceDbTable extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $section;

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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('group_id', 'PermissionResourceGroup', 'id', NULL);
    }

}
