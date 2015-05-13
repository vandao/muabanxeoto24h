<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class StaticContentLanguage extends StaticContentLanguageDbTable
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
            'field'   => array('static_content_id', 'language_id', 'static_content_title'),
            'message' => LabelMessage::rowExisted('Title')
        )));

        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    static public function saveFormData($staticContentId, $formData)
    {
        $data = FormData::parseFormDataByKey($formData);

        foreach ($data as $languageId => $staticContent) {
            $staticContent['static_content_id'] = $staticContentId;
            $staticContent['language_id']       = $languageId;

            $staticContentLanguage = StaticContentLanguage::getByStaticContent($staticContentId, $languageId);

            if (! $staticContentLanguage) $staticContentLanguage = new StaticContentLanguage();

            $staticContentLanguage->save($staticContent);
        }
    }

    static public function getByStaticContent($staticContentId, $languageId) {
        return StaticContentLanguage::findFirst("static_content_id = '$staticContentId' AND language_id = '$languageId'");
    }
}