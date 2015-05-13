<?php

class TemplateCategoryDbTable extends \Phalcon\Mvc\Model
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
    public $template_category_key;

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
        $this->hasMany('id', 'Template', 'template_category_id', NULL);
        $this->hasMany('id', 'TemplateCategoryLanguage', 'template_category_id', NULL);
    }

}
