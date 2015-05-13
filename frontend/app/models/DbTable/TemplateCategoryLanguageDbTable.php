<?php

class TemplateCategoryLanguageDbTable extends \Phalcon\Mvc\Model
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
    public $template_category_id;

    /**
     *
     * @var string
     */
    public $template_category;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('language_id', 'SystemLanguage', 'id', NULL);
        $this->belongsTo('template_category_id', 'TemplateCategory', 'id', NULL);
    }

}
