<?php

use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;

class SystemLabelForm extends FormBase
{
    public function initialize()
    {
        parent::initialize();
        
        // System language
        $systemLanguageId = new Select("language_id", SystemLanguage::fetchFormPairs());
        $systemLanguageId = $this->setLabels($systemLanguageId, 'Language', false);
        $systemLanguageId = $this->setRequireField($systemLanguageId);
        $this->add($systemLanguageId);
        
        // Section
        $section = new Select("section", SystemLabel::getSections());
        $section = $this->setLabels($section, 'Section', false);
        $section = $this->setRequireField($section);
        $this->add($section);
        
        // Key
        $key = new Text('key');
        $key = $this->setLabels($key, 'Key');
        $key = $this->setRequireField($key);
        $this->add($key);
        
        // Value
        $value = new Text('value');
        $value = $this->setLabels($value, 'Value');
        $value = $this->setRequireField($value);
        $this->add($value);
    }

    public function changeModeNew() {
    }

    public function changeModeEdit() {

    }
}