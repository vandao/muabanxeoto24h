<?php

class UserCourseWordTermDbTable extends \Phalcon\Mvc\Model
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
    public $user_course_id;

    /**
     *
     * @var integer
     */
    public $word_id;

    /**
     *
     * @var integer
     */
    public $word_term_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('user_course_id', 'UserCourse', 'id', NULL);
        $this->belongsTo('word_id', 'Word', 'id', NULL);
        $this->belongsTo('word_term_id', 'WordTerm', 'id', NULL);
    }

}
