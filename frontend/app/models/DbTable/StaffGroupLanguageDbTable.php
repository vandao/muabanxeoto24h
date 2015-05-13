<?php

class StaffGroupLanguageDbTable extends \Phalcon\Mvc\Model
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
    public $staff_group_id;

    /**
     *
     * @var string
     */
    public $staff_group;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('language_id', 'SystemLanguage', 'id', NULL);
        $this->belongsTo('staff_group_id', 'StaffGroup', 'id', NULL);
    }

}
