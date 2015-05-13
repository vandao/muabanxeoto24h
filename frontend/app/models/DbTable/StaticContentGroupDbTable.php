<?php

class StaticContentGroupDbTable extends \Phalcon\Mvc\Model
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
    public $static_content_group_key;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'StaticContent', 'static_content_group_id', NULL);
        $this->hasMany('id', 'StaticContentGroupLanguage', 'static_content_group_id', NULL);
    }

}
