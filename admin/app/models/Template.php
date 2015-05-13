<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class Template extends TemplateDbTable
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
            'field'   => array('template_key', 'template_category_id'),
            'message' => LabelMessage::rowExisted('Key')
        )));

        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    static public function filter($params) {
        $languageId = SystemLanguage::getCurrentLanguageId();

        $filters = array(
            'equalTo'    => array(
                "template_category_id" => "Template.template_category_id",
                "template_group_id"    => "Template.template_group_id",
            ),
            'likeFirst'  => array(
                "template_key"     => "Template.template_key",
                "template_subject" => "TemplateLanguage.template_subject",
                "template_body"    => "TemplateLanguage.template_body"
            ),
            'likeAll'    => array(
            ),
        );
        $sorts   = array(
            'idSort'       => 'Template.id',
        );

        $model   = new Template();
        $builder = $model->getModelsManager()->createBuilder()
                    ->from('Template')
                    ->leftJoin('TemplateLanguage', 'TemplateLanguage.template_id = Template.id')
                    ->leftJoin('TemplateGroup', 'TemplateGroup.id = Template.template_group_id')
                    ->leftJoin('TemplateGroupLanguage', 'TemplateGroupLanguage.template_group_id = Template.template_group_id')
                    ->leftJoin('TemplateCategory', 'TemplateCategory.id = Template.template_group_id')
                    ->leftJoin('TemplateCategoryLanguage', 'TemplateCategoryLanguage.template_category_id = Template.template_category_id')
                    ->where('TemplateLanguage.language_id = :language_id:', array('language_id' => $languageId))
                    ->where('TemplateGroupLanguage.language_id = :language_id:', array('language_id' => $languageId))
                    ->where('TemplateCategoryLanguage.language_id = :language_id:', array('language_id' => $languageId))
                    ->columns(array(
                        "Template.id", "Template.template_category_id", "Template.template_group_id", "template_variable", "template_key", "Template.is_disabled",
                        "TemplateLanguage.language_id", "TemplateLanguage.template_subject", "TemplateLanguage.template_body",
                        "TemplateGroupLanguage.template_group", "TemplateGroup.template_group_key",
                        "TemplateCategoryLanguage.template_category", "TemplateCategory.template_category_key"
                    ));


        $builderFilter = new BuilderFilter();
        $builder       = $builderFilter->filter($builder, $params, $filters, $sorts);
        // echo $builder->getPhql();exit;
        
        return $builder;
    }

    static function getFormData($id) {
        $template = Template::findFirstByid($id);

        if ($template) {
            $template = $template->toArray();

            foreach (TemplateLanguage::find("template_id = '$id'") as $templateLanguage) {
                $subjectKey = 'template_subject_' . $templateLanguage->language_id;
                $bodyKey    = 'template_body_' . $templateLanguage->language_id;

                $template[$subjectKey] = $templateLanguage->template_subject;
                $template[$bodyKey]    = $templateLanguage->template_body;
            }
        }

        return $template;
    }

    static function getEmailTemplate($key, $languageId, $categoryKey = "email") {
        $templateCategory = TemplateCategory::findFirst("template_category_key = '$categoryKey'");

        if ($templateCategory) {
            $template = Template::findFirst("template_key = '$key' AND template_category_id = '$templateCategory->id'");

            if ($template) {
                $templateLanguage = TemplateLanguage::findFirst("template_id = '$template->id' AND language_id = '$languageId'");

                return $templateLanguage->toArray();
            }
        }

        return array();
    }
    
    static public function getTemplateKeyByCategory($templateCategoryKey){        
        $templateCategory = TemplateCategory::findFirst("template_category_key = '$templateCategoryKey'");

        if ($templateCategory) {
            $templates = Template::find("template_category_id = ". $templateCategory->id);
            $data      = array();

            foreach ($templates as $template) {
                $data[$template->key] = $template->key;
            }

            return $data;
        }

    }
}