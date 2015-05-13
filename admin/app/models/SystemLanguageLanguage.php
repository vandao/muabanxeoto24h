<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class SystemLanguageLanguage extends SystemLanguageLanguageDbTable
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
            'field'   => array('system_language_id', 'language_id', 'language_name'),
            'message' => LabelMessage::rowExisted('Name')
        )));

        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    static public function saveFormData($mainId, $formData)
    {
        $data = FormData::parseFormDataByKey($formData);

        foreach ($data as $languageId => $language) {
            $language['system_language_id'] = $mainId;
            $language['language_id']        = $languageId;

            $languageModel = SystemLanguageLanguage::getByMainId($mainId, $languageId);

            if (! $languageModel) $languageModel = new SystemLanguageLanguage();

            $languageModel->save($language);
        }
    }

    static public function getByMainId($mainId, $languageId) {
        return SystemLanguageLanguage::findFirst("system_language_id = '$mainId' AND language_id = '$languageId'");
    }
}