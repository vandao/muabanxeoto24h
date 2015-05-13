<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class StaticContent extends StaticContentDbTable
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
            'field'   => 'static_content_key',
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
                "is_disabled"            => "StaticContent.is_disabled",
            ),
            'likeFirst'  => array(
                "static_content_key"     => "static_content_key",
                "static_content_title"   => "StaticContentLanguage.static_content_title",
                "static_content_content" => "StaticContentLanguage.static_content_content"
            ),
            'likeAll'    => array(
            ),
        );
        $sorts   = array(
            'idSort'       => 'StaticContent.id'
        );

        $model   = new StaticContent();
        $builder = $model->getModelsManager()->createBuilder()
                    ->from('StaticContent')
                    ->leftJoin('StaticContentLanguage', 'StaticContentLanguage.static_content_id = StaticContent.id')
                    ->where('StaticContentLanguage.language_id = :language_id:', array('language_id' => $languageId))
                    ->columns(array(
                        "StaticContent.id", "static_content_key", "StaticContent.is_disabled",
                        "StaticContentLanguage.language_id", "static_content_title", "static_content_content", "static_content_page_title"
                    ));


        $builderFilter = new BuilderFilter();
        $builder       = $builderFilter->filter($builder, $params, $filters, $sorts);
        // echo $builder->getPhql();exit;
        
        return $builder;
    }

    static function getFormData($id) {
        $staticContent = StaticContent::findFirstByid($id);

        if ($staticContent) {
            $staticContent = $staticContent->toArray();

            foreach (StaticContentLanguage::find("static_content_id = '$id'") as $staticContentLanguage) {
                $titleKey     = 'static_content_title_' . $staticContentLanguage->language_id;
                $contentKey   = 'static_content_content_' . $staticContentLanguage->language_id;
                $pageTitleKey = 'static_content_page_title_' . $staticContentLanguage->language_id;

                $staticContent[$titleKey]     = $staticContentLanguage->static_content_title;
                $staticContent[$contentKey]   = $staticContentLanguage->static_content_content;
                $staticContent[$pageTitleKey] = $staticContentLanguage->static_content_page_title;
            }
        }

        return $staticContent;
    }
}