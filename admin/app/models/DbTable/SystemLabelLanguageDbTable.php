<?php

class SystemLabelLanguageDbTable extends \Phalcon\Mvc\Model
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
    public $system_label_id;

    /**
     *
     * @var integer
     */
    public $language_id;

    /**
     *
     * @var string
     */
    public $label_value;

    /**
     *
     * @var string
     */
    public $label_hint;

    /**
     *
     * @var integer
     */
    public $is_approved;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('system_label_id', 'SystemLabel', 'id', NULL);
        $this->belongsTo('language_id', 'SystemLanguage', 'id', NULL);
        $this->belongsTo('language_id', 'SystemLanguage', 'id', NULL);
        $this->belongsTo('language_id', 'SystemLanguage', 'id', NULL);
    }

}
