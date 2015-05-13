<?php

class TemplateLanguageDbTable extends \Phalcon\Mvc\Model
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
    public $template_id;

    /**
     *
     * @var string
     */
    public $template_subject;

    /**
     *
     * @var string
     */
    public $template_body;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('language_id', 'SystemLanguage', 'id', NULL);
        $this->belongsTo('template_id', 'Template', 'id', NULL);
    }

}
