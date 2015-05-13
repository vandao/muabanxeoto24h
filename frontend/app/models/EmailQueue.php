<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class EmailQueue extends EmailQueueDbTable
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
        $this->status       = STATUS_PENDING;
        $this->date_created = new \Phalcon\Db\RawValue('NOW()');
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

    static public function filter($params) {
        $languageId = SystemLanguage::getCurrentLanguageId();

        $filters = array(
            'equalTo'    => array(
            ),
            'likeFirst'     => array(
                "from_name" => "from_name",
                "from"      => "from",
                "to"        => "to",
                "cc"        => "cc",
                "bcc"       => "bcc",
                "reply_to"  => "reply_to",
                "subject"   => "subject",
                "reference" => "reference",
                "body"      => "body",
                "sent_result" => "sent_result",
                "status"    => "status"
            ),
            'likeAll'    => array(
            ),
        );
        $sorts   = array(
            'idSort' => 'EmailQueue.id'
        );

        $model   = new EmailQueue();
        $builder = $model->getModelsManager()->createBuilder()
                    ->from('EmailQueue');


        $builderFilter = new BuilderFilter();
        $builder       = $builderFilter->filter($builder, $params, $filters, $sorts);
        // echo $builder->getPhql();exit;
        
        return $builder;
    }

    static public function resetEmail($id)
    {
        $emailQueue = EmailQueue::findFirst($id);

        $emailQueue->sent_result = NULL;
        $emailQueue->status      = STATUS_PENDING;
        $emailQueue->date_sent   = NULL;

        return $emailQueue->save();
    }
}