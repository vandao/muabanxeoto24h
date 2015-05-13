<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class StaffGroupLanguage extends StaffGroupLanguageDbTable
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
            'field'   => array('staff_group_id', 'language_id', 'staff_group'),
            'message' => LabelMessage::rowExisted('Group')
        )));

        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    static public function saveFormData($mainId, $formData)
    {
        $data = FormData::parseFormDataByKey($formData);

        foreach ($data as $languageId => $language) {
            $language['staff_group_id'] = $mainId;
            $language['language_id']    = $languageId;

            $languageModel = StaffGroupLanguage::getByMainId($mainId, $languageId);

            if (! $languageModel) $languageModel = new StaffGroupLanguage();

            $languageModel->save($language);
        }
    }

    static public function getByMainId($mainId, $languageId) {
        return StaffGroupLanguage::findFirst("staff_group_id = '$mainId' AND language_id = '$languageId'");
    }
}