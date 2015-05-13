<?php

class UserWordTermDbTable extends \Phalcon\Mvc\Model
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
    public $user_id;

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
     *
     * @var integer
     */
    public $insert_count;

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
        $this->belongsTo('user_id', 'User', 'id', NULL);
        $this->belongsTo('word_id', 'Word', 'id', NULL);
        $this->belongsTo('word_term_id', 'WordTerm', 'id', NULL);
    }

}
