<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class TemplateCategoryLanguage extends TemplateCategoryLanguageDbTable
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
            'field'   => array('template_category_id', 'language_id', 'template_category'),
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
            $language['template_category_id'] = $mainId;
            $language['language_id']          = $languageId;

            $languageModel = TemplateCategoryLanguage::getByMainId($mainId, $languageId);

            if (! $languageModel) $languageModel = new TemplateCategoryLanguage();

            $languageModel->save($language);
        }
    }

    static public function getByMainId($mainId, $languageId) {
        return TemplateCategoryLanguage::findFirst("template_category_id = '$mainId' AND language_id = '$languageId'");
    }
}