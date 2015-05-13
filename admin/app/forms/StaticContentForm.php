<?php

use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\File;
use Phalcon\Forms\Element\Check;

class StaticContentForm extends FormBase
{
    public function initialize()
    {
        parent::initialize();
        
        // Static content key
        $staticContentKey = new Text('static_content_key');
        $staticContentKey = $this->setLabels($staticContentKey, 'Key');
        $staticContentKey = $this->setRequireField($staticContentKey);
        $staticContentKey = $this->setValidateMaxLength($staticContentKey, 30);
        $this->add($staticContentKey);

        $languages = SystemLanguage::getAllByLanguage();
        foreach ($languages as $language) {
            $staticContentTitle = new Text("static_content_title_" . $language->id);
            $staticContentTitle = $this->setLabels($staticContentTitle, 'Title', true, array('_Language' => $language->language_name));

            $staticContentContent = new TextArea("static_content_content_" . $language->id);
            $staticContentContent = $this->setLabels($staticContentContent, 'Content', true, array('_Language' => $language->language_name));

            $staticContentPageTitle = new Text("static_content_page_title_" . $language->id);
            $staticContentPageTitle = $this->setLabels($staticContentPageTitle, 'Page-Title', true, array('_Language' => $language->language_name));
            $staticContentPageTitle->setAttribute('is-disable-tinymce',1);

            if ($language->is_default) {
                $this->setRequireField($staticContentTitle);
                $this->setRequireField($staticContentContent);
                $this->setRequireField($staticContentPageTitle);
            }

            $this->add($staticContentTitle);
            $this->add($staticContentContent);
            $this->add($staticContentPageTitle);
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