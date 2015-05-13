<?php

class SystemLabelDbTable extends \Phalcon\Mvc\Model
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
    public $section;

    /**
     *
     * @var string
     */
    public $label_key;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'SystemLabelLanguage', 'system_label_id', NULL);
    }

}
