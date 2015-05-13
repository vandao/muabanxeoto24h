<?php

class SystemLanguageDbTable extends \Phalcon\Mvc\Model
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
    public $language_code;

    /**
     *
     * @var integer
     */
    public $position;

    /**
     *
     * @var integer
     */
    public $is_default;

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
        $this->hasMany('id', 'MenuLanguage', 'language_id', NULL);
        $this->hasMany('id', 'StaffGroupLanguage', 'language_id', NULL);
        $this->hasMany('id', 'StaticContentGroupLanguage', 'language_id', NULL);
        $this->hasMany('id', 'StaticContentLanguage', 'language_id', NULL);
        $this->hasMany('id', 'SystemLabelLanguage', 'language_id', NULL);
        $this->hasMany('id', 'SystemLabelLanguage', 'language_id', NULL);
        $this->hasMany('id', 'SystemLabelLanguage', 'language_id', NULL);
        $this->hasMany('id', 'SystemLanguageLanguage', 'language_id', NULL);
        $this->hasMany('id', 'SystemLanguageLanguage', 'system_language_id', NULL);
        $this->hasMany('id', 'TemplateCategoryLanguage', 'language_id', NULL);
        $this->hasMany('id', 'TemplateGroupLanguage', 'language_id', NULL);
        $this->hasMany('id', 'TemplateLanguage', 'language_id', NULL);
        $this->hasMany('id', 'User', 'language_id', NULL);
        $this->hasMany('id', 'WordTerm', 'language_id', NULL);
    }

}
