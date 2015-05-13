<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class StaffGroup extends StaffGroupDbTable
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
        $this->staff_count = 0;
    }
    
    public function beforeValidationOnUpdate()
    {
    }

    public function validation()
    {
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    static public function filter($params) {
        $languageId = SystemLanguage::getCurrentLanguageId();

        $filters = array(
            'equalTo'    => array(
                "id" => "StaffGroup.id"
            ),
            'likeFirst'  => array(
                "staff_group" => "StaffGroupLanguage.staff_group"
            ),
            'likeAll'    => array(
            ),
        );
        $sorts   = array(
            'idSort' => 'StaffGroup.id'
        );

        $model   = new StaffGroup();
        $builder = $model->getModelsManager()->createBuilder()
                    ->from('StaffGroup')
                    ->leftJoin('StaffGroupLanguage', 'StaffGroupLanguage.staff_group_id = StaffGroup.id')
                    ->where('StaffGroupLanguage.language_id = :language_id:', array('language_id' => $languageId))
                    ->columns(array(
                        "StaffGroup.id", "is_disabled", "StaffGroupLanguage.staff_group"
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
        
        foreach (StaffGroup::getAllByLanguage() as $staffGroup) {
            $data[$staffGroup->id] = $staffGroup->staff_group;
        }

        return $data;
    }

    static function getAllByLanguage($isDisabled = 0) {
        $params = array(
            'is_disabled' => $isDisabled
        );

        $builder = StaffGroup::filter($params);

        return $builder->getQuery()->execute();
    }

    static function getFormData($id) {
        $main = StaffGroup::findFirstByid($id);

        if ($main) {
            $main = $main->toArray();

            foreach (StaffGroupLanguage::find("staff_group_id = '$id'") as $mainLanguage) {
                $nameKey = 'group_' . $mainLanguage->language_id;

                $main[$nameKey] = $mainLanguage->staff_group;
            }
        }

        return $main;
    }

    static function getById($staffGroupId) {
        $params = array(
            'id' => $staffGroupId
        );

        $builder     = StaffGroup::filter($params);
        $staffGroups = $builder->getQuery()->execute();
        
        if ($staffGroups->count() == 1) {
            foreach ($staffGroups as $staffGroup) {
                return $staffGroup;
            }
        }

        return false;
    }
}