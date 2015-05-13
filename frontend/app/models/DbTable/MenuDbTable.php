<?php

class MenuDbTable extends \Phalcon\Mvc\Model
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
    public $menu_key;

    /**
     *
     * @var string
     */
    public $menu_url;

    /**
     *
     * @var integer
     */
    public $position;

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
        $this->hasMany('id', 'MenuLanguage', 'menu_id', NULL);
    }

}
