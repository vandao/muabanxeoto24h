<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class TemplateLanguage extends TemplateLanguageDbTable
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
            'field'   => array('template_id', 'language_id', 'template_subject'),
            'message' => LabelMessage::rowExisted('Subject')
        )));

        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    static public function saveFormData($templateId, $formData)
    {
        $data = FormData::parseFormDataByKey($formData);

        foreach ($data as $languageId => $template) {
            $template['template_id'] = $templateId;
            $template['language_id'] = $languageId;

            $templateLanguage = TemplateLanguage::getByTemplate($templateId, $languageId);

            if (! $templateLanguage) $templateLanguage = new TemplateLanguage();

            $templateLanguage->save($template);
        }
    }

    static public function getByTemplate($templateId, $languageId) {
        return TemplateLanguage::findFirst("template_id = '$templateId' AND language_id = '$languageId'");
    }
}