<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class EmailQueueAttachment extends EmailQueueAttachmentDbTable
{
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->setConnectionService('dbEmailQueue');
    }

    public function beforeValidationOnCreate()
    {
    }
    
    public function beforeValidationOnUpdate()
    {
    }

    public function validation()
    {
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }
}