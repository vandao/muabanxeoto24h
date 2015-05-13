<?php

use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Check;

class StaffForm extends FormBase
{
    public function initialize()
    {
        parent::initialize();
        
        // Staff group
        $staffGroupId = new Select("staff_group_id", StaffGroup::fetchFormPairs());
        $staffGroupId = $this->setLabels($staffGroupId, 'Staff-Group', false);
        $staffGroupId = $this->setRequireField($staffGroupId);
        $this->add($staffGroupId);

        // Full name
        $fullName = new Text('full_name');
        $fullName = $this->setLabels($fullName, 'Full-Name');
        $fullName = $this->setRequireField($fullName);
        $this->add($fullName);
        
        // Email
        $email = new Text('email');
        $email = $this->setLabels($email, 'Email');
        $email = $this->setRequireField($email);
        $email = $this->setValidateEmail($email);
        $this->add($email);

        // Password
        $password = new Password('password');
        $password = $this->setLabels($password, 'Password');
        $password = $this->setValidateConfirmation($password, 'confirm_password');
        $this->add($password);

        // Confirm password
        $confirmPassword = new Password('confirm_password');
        $confirmPassword = $this->setLabels($confirmPassword, 'Confirm-Password');
        $this->add($confirmPassword);

        // Is Disabled
        $isDisabled = new Check('is_disabled');
        $isDisabled = $this->setLabels($isDisabled, 'Is-Disabled', false);
        $this->add($isDisabled);
    }

    public function changeModeNew() {
        $elements = $this->getElements();
        
        $password = $elements['password'];
        $password = $this->setRequireField($password);
        $password = $this->setValidateMinLength($password, 6);

        $confirmPassword = $elements['confirm_password'];
        $confirmPassword = $this->setRequireField($confirmPassword);
        $confirmPassword = $this->setValidateMinLength($confirmPassword, 6);
    }

    public function changeModeEdit() {

    }
}