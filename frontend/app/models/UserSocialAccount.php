<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class UserSocialAccount extends UserSocialAccountDbTable
{
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
    }

    public function beforeValidationOnCreate()
    {
        $this->date_updated = new \Phalcon\Db\RawValue('NOW()');
        $this->date_created = new \Phalcon\Db\RawValue('NOW()');
    }
    
    public function beforeValidationOnUpdate()
    {
        $this->date_updated = new \Phalcon\Db\RawValue('NOW()');
    }

    public function validation()
    {
        $this->validate(new Uniqueness(array(
            'field'   => array('provider', 'identity'),
            'message' => LabelMessage::rowExisted('Identity')
        )));

        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    static public function getByIdentity($provider, $identity) {
        $row = UserSocialAccount::findFirst(array(
            "conditions" => "provider = ?1 AND identity = ?2",
            "bind"       => array(
                1 => $provider,
                2 => $identity
            )
        ));

        return $row;
    }
}