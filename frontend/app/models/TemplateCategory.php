<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class TemplateCategory extends TemplateCategoryDbTable
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
            'field'   => 'template_category_key',
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
            ),
            'likeFirst'  => array(
                "template_category" => "TemplateCategoryLanguage.template_category"
            ),
            'likeAll'    => array(
            ),
        );
        $sorts   = array(
            'idSort'       => 'TemplateCategory.id',
        );

        $model   = new TemplateCategory();
        $builder = $model->getModelsManager()->createBuilder()
                    ->from('TemplateCategory')
                    ->leftJoin('TemplateCategoryLanguage', 'TemplateCategoryLanguage.template_category_id = TemplateCategory.id')
                    ->where('TemplateCategoryLanguage.language_id = :language_id:', array('language_id' => $languageId))
                    ->columns(array(
                        "TemplateCategory.id", "TemplateCategory.template_category_key", "TemplateCategory.is_disabled",
                        "TemplateCategoryLanguage.language_id", "TemplateCategoryLanguage.template_category",
                    ));


        $builderFilter = new BuilderFilter();
        $builder       = $builderFilter->filter($builder, $params, $filters, $sorts);
        // echo $builder->getPhql();exit;
        
        return $builder;
    }

    static function fetchFormPairs($includeEmptyValue = false) {
        if ($includeEmptyValue) {
            $data = array("" => "");
        } else {
            $data = array();
        }
        
        foreach (TemplateCategory::getAllByLanguage() as $templateCategory) {
            $data[$templateCategory->id] = $templateCategory->template_category;
        }

        return $data;
    }

    static function getAllByLanguage($isDisabled = 0) {
        $params = array(
            'is_disabled' => $isDisabled
        );

        $builder = TemplateCategory::filter($params);

        return $builder->getQuery()->execute();
    }

    static function getFormData($id) {
        $main = TemplateCategory::findFirstByid($id);

        if ($main) {
            $main = $main->toArray();

            foreach (TemplateCategoryLanguage::find("template_category_id = '$id'") as $mainLanguage) {
                $nameKey = 'template_category_' . $mainLanguage->language_id;

                $main[$nameKey] = $mainLanguage->template_category;
            }
        }

        return $main;
    }
}