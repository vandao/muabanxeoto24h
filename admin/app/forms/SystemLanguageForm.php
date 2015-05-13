<?php

use Phalcon\Forms\Element\Text;

class SystemLanguageForm extends FormBase
{
    public function initialize()
    {
        parent::initialize();
        
        $languages = SystemLanguage::getAllByLanguage();
        foreach ($languages as $language) {
            $languageName = new Text("language_name_" . $language->id);
            $languageName = $this->setLabels($languageName, 'Name', true, array('_Language' => $language->language_name));

            if ($language->is_default) {
                $this->setRequireField($languageName);
            }
            $this->add($languageName);
        }
        
        // Language code
        $languageCode = new Text('language_code');
        $languageCode = $this->setLabels($languageCode, 'Code');
        $languageCode = $this->setRequireField($languageCode);
        $this->add($languageCode);
    }

    public function changeModeNew() {
    }

    public function changeModeEdit() {

    }
}