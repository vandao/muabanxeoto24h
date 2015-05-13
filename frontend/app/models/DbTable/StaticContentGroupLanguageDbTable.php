<?php

class StaticContentGroupLanguageDbTable extends \Phalcon\Mvc\Model
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
    public $language_id;

    /**
     *
     * @var integer
     */
    public $static_content_group_id;

    /**
     *
     * @var string
     */
    public $static_content_group;

    /**
     *
     * @var string
     */
    public $static_content_group_page_title;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('language_id', 'SystemLanguage', 'id', NULL);
        $this->belongsTo('static_content_group_id', 'StaticContentGroup', 'id', NULL);
    }

}
