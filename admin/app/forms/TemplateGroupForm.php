<?php

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Check;

class TemplateGroupForm extends FormBase
{
    public function initialize()
    {
        parent::initialize();
        
        $languages = SystemLanguage::getAllByLanguage();
        foreach ($languages as $language) {
            $templateGroup = new Text("template_group_" . $language->id);
            $templateGroup = $this->setLabels($templateGroup, 'Group', true, array('_Language' => $language->language_name));

            if ($language->is_default) {
                $this->setRequireField($templateGroup);
            }
            $this->add($templateGroup);
        }
        
        // Template group key
        $templateGroupKey = new Text('template_group_key');
        $templateGroupKey = $this->setLabels($templateGroupKey, 'Key');
        $templateGroupKey = $this->setRequireField($templateGroupKey);
        $templateGroupKey = $this->setValidateMaxLength($templateGroupKey, 30);
        $this->add($templateGroupKey);

        // Is Disabled
        $isDisabled = new Check('is_disabled');
        $isDisabled = $this->setLabels($isDisabled, 'Is-Disabled', false);
        $this->add($isDisabled);
    }

    public function changeModeNew() {
    }

    public function changeModeEdit() {

    }
}