<?php

class WordDbTable extends \Phalcon\Mvc\Model
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
    public $date_updated;

    /**
     *
     * @var string
     */
    public $date_created;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'UserCourseWordTerm', 'word_id', NULL);
        $this->hasMany('id', 'UserWordTerm', 'word_id', NULL);
        $this->hasMany('id', 'WordTerm', 'word_id', NULL);
    }

}
