<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class TemplateGroupLanguage extends TemplateGroupLanguageDbTable
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
            'field'   => array('template_group_id', 'language_id', 'template_group'),
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
            $language['template_group_id'] = $mainId;
            $language['language_id']       = $languageId;

            $languageModel = TemplateGroupLanguage::getByMainId($mainId, $languageId);

            if (! $languageModel) $languageModel = new TemplateGroupLanguage();

            $languageModel->save($language);
        }
    }

    static public function getByMainId($mainId, $languageId) {
        return TemplateGroupLanguage::findFirst("template_group_id = '$mainId' AND language_id = '$languageId'");
    }
}