<?php

use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Check;

class TemplateForm extends FormBase
{
    public function initialize()
    {
        parent::initialize();
        
        // Template category
        $templateCategoryId = new Select("template_category_id", TemplateCategory::fetchFormPairs());
        $templateCategoryId = $this->setLabels($templateCategoryId, 'Category', false);
        $templateCategoryId = $this->setRequireField($templateCategoryId);
        $this->add($templateCategoryId);
        
        // Template group
        $templateGroupId = new Select("template_group_id", TemplateGroup::fetchFormPairs());
        $templateGroupId = $this->setLabels($templateGroupId, 'Group', false);
        $templateGroupId = $this->setRequireField($templateGroupId);
        $this->add($templateGroupId);

        // Template key
        $templateKey = new Text('template_key');
        $templateKey = $this->setLabels($templateKey, 'Key');
        $templateKey = $this->setRequireField($templateKey);
        $this->add($templateKey);

        // Template variable
        $templateVariable = new Text('template_variable');
        $templateVariable = $this->setLabels($templateVariable, 'Variable');
        $templateVariable = $this->setRequireField($templateVariable);
        $this->add($templateVariable);

        $languages = SystemLanguage::getAllByLanguage();
        foreach ($languages as $language) {
            $subject = new Text("template_subject_" . $language->id);
            $subject = $this->setLabels($subject, 'Subject', true, array('_Language' => $language->language_name));

            $body = new TextArea("template_body_" . $language->id);
            $body = $this->setLabels($body, 'Body', true, array('_Language' => $language->language_name));

            if ($language->is_default) {
                $this->setRequireField($subject);
                $this->setRequireField($body);
            }

            $this->add($subject);
            $this->add($body);
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