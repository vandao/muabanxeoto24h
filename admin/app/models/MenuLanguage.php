<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class MenuLanguage extends MenuLanguageDbTable
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
            'field'   => array('menu_id', 'language_id', 'menu_name'),
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
            $language['menu_id']     = $mainId;
            $language['language_id'] = $languageId;

            $languageModel = MenuLanguage::getByMainId($mainId, $languageId);

            if (! $languageModel) $languageModel = new MenuLanguage();

            $languageModel->save($language);
        }
    }

    static public function getByMainId($mainId, $languageId) {
        return MenuLanguage::findFirst("menu_id = '$mainId' AND language_id = '$languageId'");
    }
}