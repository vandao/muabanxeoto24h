<?php

class TemplateGroupDbTable extends \Phalcon\Mvc\Model
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
    public $template_group_key;

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
        $this->hasMany('id', 'Template', 'template_group_id', NULL);
        $this->hasMany('id', 'TemplateGroupLanguage', 'template_group_id', NULL);
    }

}
