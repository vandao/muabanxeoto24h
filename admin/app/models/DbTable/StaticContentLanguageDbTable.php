<?php

class StaticContentLanguageDbTable extends \Phalcon\Mvc\Model
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
    public $static_content_id;

    /**
     *
     * @var string
     */
    public $static_content_title;

    /**
     *
     * @var string
     */
    public $static_content_content;

    /**
     *
     * @var string
     */
    public $static_content_page_title;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('language_id', 'SystemLanguage', 'id', NULL);
        $this->belongsTo('static_content_id', 'StaticContent', 'id', NULL);
    }

}
