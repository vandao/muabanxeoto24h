<?php

use Phalcon\Mvc\Model\Validator\Email as Email;

class UserDbTable extends \Phalcon\Mvc\Model
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
     * @var string
     */
    public $first_name;

    /**
     *
     * @var string
     */
    public $last_name;

    /**
     *
     * @var string
     */
    public $provider;

    /**
     *
     * @var string
     */
    public $avatar;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $gender;

    /**
     *
     * @var string
     */
    public $date_of_birth;

    /**
     *
     * @var integer
     */
    public $ip_number;

    /**
     *
     * @var string
     */
    public $last_login;

    /**
     *
     * @var string
     */
    public $date_created;

    /**
     * Validations and business logic
     */
    public function validation()
    {

        $this->validate(
            new Email(
                array(
                    'field'    => 'email',
                    'required' => true,
                )
            )
        );
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'UserSocialAccount', 'user_id', NULL);
        $this->hasMany('id', 'UserWordTerm', 'user_id', NULL);
        $this->hasMany('id', 'WordTerm', 'user_id', NULL);
        $this->belongsTo('language_id', 'SystemLanguage', 'id', NULL);
    }

}
