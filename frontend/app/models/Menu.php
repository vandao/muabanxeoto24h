<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class Menu extends MenuDbTable
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
            'field'   => 'menu_key',
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
                "is_disabled"  => "Menu.is_disabled"
            ),
            'likeFirst'  => array(
                "menu_name" => "MenuLanguage.menu_name",
                "menu_name" => "MenuLanguage.menu_name"
            ),
            'likeAll'    => array(
            ),
        );
        $sorts   = array(
            'idSort'       => 'Menu.id',
            'positionSort' => 'Menu.position'
        );

        $model   = new Menu();
        $builder = $model->getModelsManager()->createBuilder()
                    ->from('Menu')
                    ->leftJoin('MenuLanguage', 'MenuLanguage.menu_id = Menu.id')
                    ->andWhere('MenuLanguage.language_id = :language_id:', array('language_id' => $languageId))
                    ->columns(array(
                        "Menu.id", "menu_key", "menu_url", "position", "Menu.is_disabled",
                        "MenuLanguage.language_id", "MenuLanguage.menu_name",
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
        
        foreach (Menu::getAllByLanguage() as $menuLanguage) {
            $data[$menuLanguage->id] = $menuLanguage->menu_name;
        }

        return $data;
    }

    static function getAllByLanguage($isDisabled = 0) {
        $params = array(
            'is_disabled'  => $isDisabled,
            'positionSort' => "ASC"
        );

        $builder = Menu::filter($params);

        return $builder->getQuery()->execute();
    }

    static function getFormData($id) {
        $main = Menu::findFirstByid($id);

        if ($main) {
            $main = $main->toArray();

            foreach (MenuLanguage::find("menu_id = '$id'") as $mainLanguage) {
                $nameKey = 'menu_name_' . $mainLanguage->language_id;

                $main[$nameKey] = $mainLanguage->menu_name;
            }
        }

        return $main;
    }

    private function _getLastOrder() {
        return Menu::maximum(array(
            "column"     => "position",
        ));
    }
}