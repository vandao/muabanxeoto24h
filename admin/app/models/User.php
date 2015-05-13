<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class User extends UserDbTable
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
    }
    
    public function beforeValidationOnUpdate()
    {
    }

    public function validation()
    {
        $this->validate(new Uniqueness(array(
            'field'   => 'key',
            'message' => 'Sorry, your key was registered'
        )));

        if ($this->validationHasFailed() == true) {
            return false;
        }
    }
}