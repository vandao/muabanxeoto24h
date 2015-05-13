<?php

class TemplateDbTable extends \Phalcon\Mvc\Model
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
    public $template_category_id;

    /**
     *
     * @var integer
     */
    public $template_group_id;

    /**
     *
     * @var string
     */
    public $template_key;

    /**
     *
     * @var string
     */
    public $template_variable;

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
        $this->hasMany('id', 'TemplateLanguage', 'template_id', NULL);
        $this->belongsTo('template_category_id', 'TemplateCategory', 'id', NULL);
        $this->belongsTo('template_group_id', 'TemplateGroup', 'id', NULL);
    }

}
