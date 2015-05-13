<?php

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Check;

class StaffGroupForm extends FormBase
{
    public function initialize()
    {
        parent::initialize();
        
        $languages = SystemLanguage::getAllByLanguage();
        foreach ($languages as $language) {
            $group = new Text("group_" . $language->id);
            $group = $this->setLabels($group, 'Group', true, array('_Language' => $language->language_name));

            if ($language->is_default) {
                $this->setRequireField($group);
            }
            $this->add($group);
        }

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