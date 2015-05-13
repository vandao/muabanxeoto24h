<?php

class StaticContentDbTable extends \Phalcon\Mvc\Model
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
    public $static_content_group_id;

    /**
     *
     * @var string
     */
    public $static_content_key;

    /**
     *
     * @var string
     */
    public $image_extension;

    /**
     *
     * @var string
     */
    public $image_position;

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
        $this->hasMany('id', 'StaticContentLanguage', 'static_content_id', NULL);
        $this->belongsTo('static_content_group_id', 'StaticContentGroup', 'id', NULL);
    }

}
