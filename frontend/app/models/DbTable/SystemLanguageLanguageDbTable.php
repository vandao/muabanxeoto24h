<?php

class SystemLanguageLanguageDbTable extends \Phalcon\Mvc\Model
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
    public $system_language_id;

    /**
     *
     * @var string
     */
    public $language_name;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('language_id', 'SystemLanguage', 'id', NULL);
        $this->belongsTo('system_language_id', 'SystemLanguage', 'id', NULL);
    }

}
