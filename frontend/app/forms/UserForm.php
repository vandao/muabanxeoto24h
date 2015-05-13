<?php

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Password;

class UserForm extends FormBase
{
    public function initialize()
    {
        parent::initialize();
                
        // Full name
        $fullName = new Text('full_name');
        $fullName = $this->setLabels($fullName, 'Full-Name');
        $fullName = $this->setRequireField($fullName);
        $fullName = $this->setValidateMaxLength($fullName, 20);
        $this->add($fullName);
        
        // Email
        $email = new Email('email');
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
        
        // Phone number
        $phoneNumber = new Text('phone_number');
        $phoneNumber = $this->setLabels($phoneNumber, 'Phone-Number');
        $phoneNumber = $this->setRequireField($phoneNumber);
        $phoneNumber = $this->setValidateMaxLength($phoneNumber, 20);
        $this->add($phoneNumber);
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