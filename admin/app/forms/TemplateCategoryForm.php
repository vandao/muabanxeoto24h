<?php

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Check;

class TemplateCategoryForm extends FormBase
{
    public function initialize()
    {
        parent::initialize();
        
        $languages = SystemLanguage::getAllByLanguage();
        foreach ($languages as $language) {
            $templateCategory = new Text("template_category_" . $language->id);
            $templateCategory = $this->setLabels($templateCategory, 'Category', true, array('_Language' => $language->language_name));

            if ($language->is_default) {
                $this->setRequireField($templateCategory);
            }
            $this->add($templateCategory);
        }
        
        // Template category key
        $templateCategoryKey = new Text('template_category_key');
        $templateCategoryKey = $this->setLabels($templateCategoryKey, 'Key');
        $templateCategoryKey = $this->setRequireField($templateCategoryKey);
        $templateCategoryKey = $this->setValidateMaxLength($templateCategoryKey, 30);
        $this->add($templateCategoryKey);

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