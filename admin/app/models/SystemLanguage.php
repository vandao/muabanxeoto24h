<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Session\Adapter\Files as SessionAdapter;

class SystemLanguage extends SystemLanguageDbTable
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
        $this->position = $this->_getLastOrder() + 1;
    }
    
    public function beforeValidationOnUpdate()
    {
    }

    public function validation()
    {
        $this->validate(new Uniqueness(array(
            'field'   => 'language_code',
            'message' => LabelMessage::rowExisted('Code')
        )));

        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    static public function filter($params, $isGetAllLanguage = false) {
        $filters = array(
            'equalTo'    => array(
                "language_code" => "language_code",
                "is_disabled"   => "SystemLanguage.is_disabled"
            ),
            'likeFirst'  => array(
                "language_name" => "SystemLanguageLanguage.language_name"
            ),
            'likeAll'    => array(
            ),
        );
        $sorts   = array(
            'idSort' => 'SystemLanguage.id'
        );

        $model   = new SystemLanguage();
        $builder = $model->getModelsManager()->createBuilder()
                    ->from('SystemLanguage')
                    ->leftJoin('SystemLanguageLanguage', 'SystemLanguageLanguage.system_language_id = SystemLanguage.id')
                    ->columns(array(
                        "SystemLanguage.id", "language_code", "position", "SystemLanguage.is_default", "SystemLanguage.is_disabled",
                        "SystemLanguageLanguage.language_id", "SystemLanguageLanguage.language_name",
                    ));

        if (! $isGetAllLanguage) {
            $languageId = SystemLanguage::getCurrentLanguageId();

            $builder->where('SystemLanguageLanguage.language_id = :language_id:', array('language_id' => $languageId));
        }


        $builderFilter = new BuilderFilter();
        $builder       = $builderFilter->filter($builder, $params, $filters, $sorts);
        // echo $builder->getPhql();exit;
        
        return $builder;
    }

    /**
     * get pair array of key and value depend on field
     * @param  string  $key        [description]
     * @param  string  $value      [description]
     * @param  boolean $includeAll [description]
     * @param  string  $where      [description]
     * @return array
     */
    static public function fetchPair($key, $value, $includeAll = true) {
        $label = new Label();

        $data  = array();        
        if ($includeAll) $data[''] = $label->label('All');
        foreach (SystemLanguage::getAllByLanguage() as $item) {
            $data[$item->$key] = $item->$value;
        }
        return $data;
    }

    static function getAllByLanguage($isDisabled = 0) {
        $params = array(
            'is_disabled' => $isDisabled
        );

        $builder = SystemLanguage::filter($params);

        return $builder->getQuery()->execute();
    }

    static function getFormData($id) {
        $main = SystemLanguage::findFirstByid($id);

        if ($main) {
            $main = $main->toArray();

            foreach (SystemLanguageLanguage::find("system_language_id = '$id'") as $mainLanguage) {
                $nameKey = 'language_name_' . $mainLanguage->language_id;

                $main[$nameKey] = $mainLanguage->language_name;
            }
        }

        return $main;
    }

    private function _getLastOrder() {
        return SystemLanguage::maximum(array(
            "column" => "position",
        ));
    }

    static public function getByCode($code) {
        return SystemLanguage::findFirst("language_code = '$code'");
    }

    static public function getCurrentLanguageId() {
        $session = new SessionAdapter();
        return $session->get('lang_id');
    }

    static function getAllForExport() {
        $params = array(
            'is_disabled'  => 0,
            'positionSort' => "ASC"
        );

        $builder      = SystemLanguage::filter($params, true);
        $systemLanguages = $builder->getQuery()->execute();

        $languages = SystemLanguage::fetchPair("id", "language_code", false);
        $data      = array();

        foreach ($languages as $languageId => $languageCode) {
            $data[$languageCode] = array();

            foreach ($systemLanguages as $systemLanguage) {
                if ($systemLanguage->language_id == $languageId) {
                    $language = array(
                        'id'            => $systemLanguage->id,
                        'language_code' => $systemLanguage->language_code,
                        'language_name' => $systemLanguage->language_name,
                    );

                    $data[$languageCode][$systemLanguage['language_code']] = $language;
                }
            }
        }

        return $data;
    }
}