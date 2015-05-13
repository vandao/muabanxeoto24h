<?php

class WordTermDbTable extends \Phalcon\Mvc\Model
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
    public $word_id;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var string
     */
    public $term;

    /**
     *
     * @var integer
     */
    public $insert_count;

    /**
     *
     * @var integer
     */
    public $used_count;

    /**
     *
     * @var integer
     */
    public $google_search_result;

    /**
     *
     * @var integer
     */
    public $position;

    /**
     *
     * @var integer
     */
    public $is_google;

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
        $this->hasMany('id', 'UserCourseWordTerm', 'word_term_id', NULL);
        $this->hasMany('id', 'UserWordTerm', 'word_term_id', NULL);
        $this->belongsTo('word_id', 'Word', 'id', NULL);
        $this->belongsTo('user_id', 'User', 'id', NULL);
        $this->belongsTo('language_id', 'SystemLanguage', 'id', NULL);
    }

}
