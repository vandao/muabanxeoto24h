<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class TemplateGroup extends TemplateGroupDbTable
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
            'field'   => 'template_group_key',
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
                "template_group" => "TemplateGroupLanguage.template_group"
            ),
            'likeAll'    => array(
            ),
        );
        $sorts   = array(
            'idSort'       => 'TemplateGroup.id',
        );

        $model   = new TemplateGroup();
        $builder = $model->getModelsManager()->createBuilder()
                    ->from('TemplateGroup')
                    ->leftJoin('TemplateGroupLanguage', 'TemplateGroupLanguage.template_group_id = TemplateGroup.id')
                    ->where('TemplateGroupLanguage.language_id = :language_id:', array('language_id' => $languageId))
                    ->columns(array(
                        "TemplateGroup.id", "TemplateGroup.template_group_key", "TemplateGroup.is_disabled",
                        "TemplateGroupLanguage.language_id", "TemplateGroupLanguage.template_group",
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
        
        foreach (TemplateGroup::getAllByLanguage() as $templateGroup) {
            $data[$templateGroup->id] = $templateGroup->template_group;
        }

        return $data;
    }

    static function getAllByLanguage($isDisabled = 0) {
        $params = array(
            'is_disabled' => $isDisabled
        );

        $builder = TemplateGroup::filter($params);

        return $builder->getQuery()->execute();
    }

    static function getFormData($id) {
        $main = TemplateGroup::findFirstByid($id);

        if ($main) {
            $main = $main->toArray();

            foreach (TemplateGroupLanguage::find("template_group_id = '$id'") as $mainLanguage) {
                $nameKey = 'template_group_' . $mainLanguage->language_id;

                $main[$nameKey] = $mainLanguage->template_group;
            }
        }

        return $main;
    }
}