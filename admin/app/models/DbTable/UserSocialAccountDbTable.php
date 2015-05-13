<?php

class UserSocialAccountDbTable extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $provider;

    /**
     *
     * @var string
     */
    public $identity;

    /**
     *
     * @var string
     */
    public $token;

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
    }

}
