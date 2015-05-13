<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class Staff extends StaffDbTable
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
        $this->email        = strtolower($this->email);
        $this->date_created = new \Phalcon\Db\RawValue('NOW()');
    }
    
    public function beforeValidationOnUpdate()
    {
        $this->email        = strtolower($this->email);
    }

    public function afterCreate() {
        PermissionStaff::regeneratePermissionByStaff($this->id);
    }

    public function afterUpdate() {
        //PermissionStaff::regeneratePermissionByStaff($this->id);
    }

    public function validation()
    {
        $this->validate(new Uniqueness(array(
            'field'   => 'email',
            'message' => LabelMessage::rowExisted('Email')
        )));

        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    static public function filter($params) {
        $languageId = SystemLanguage::getCurrentLanguageId();

        $filters = array(
            'equalTo'    => array(
                "staff_group_id" => "Staff.staff_group_id"
            ),
            'likeFirst'  => array(
                "full_name" => "Staff.full_name",
                "email"     => "Staff.email"
            ),
            'likeAll'    => array(
            ),
        );
        $sorts   = array(
            'idSort' => 'Staff.id'
        );

        $model   = new Staff();
        $builder = $model->getModelsManager()->createBuilder()
                    ->from('Staff')
                    ->leftJoin('StaffGroup', 'StaffGroup.id = Staff.staff_group_id')
                    ->leftJoin('StaffGroupLanguage', 'StaffGroupLanguage.staff_group_id = StaffGroup.id')
                    ->where('StaffGroupLanguage.language_id = :language_id:', array('language_id' => $languageId))
                    ->columns(array(
                        "Staff.id", "full_name", "email", "is_custom_permission",
                        "password", "date_updated", "date_created", "StaffGroupLanguage.staff_group"
                    ));


        $builderFilter = new BuilderFilter();
        $builder       = $builderFilter->filter($builder, $params, $filters, $sorts);
        // echo $builder->getPhql();exit;
        
        return $builder;
    }

    static public function setCustomPermission($staffId, $isCustomPermission = 0) {
        $staff = Staff::findFirstById($staffId);

        if ($staff) {
            $staff->is_custom_permission = $isCustomPermission;
            $staff->save();
        }
    }
}