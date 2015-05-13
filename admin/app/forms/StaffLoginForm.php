<?php

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;

class StaffLoginForm extends FormBase
{
    public function initialize()
    {
        parent::initialize();
        
        // Email
        $email = new Text('email');
        $email = $this->setLabels($email, 'Email');
        $email = $this->setRequireField($email);
        $email = $this->setValidateEmail($email);
        $this->add($email);

        // Password
        $password = new Password('password');
        $password = $this->setLabels($password, 'Password');
        $password = $this->setRequireField($password);
        $this->add($password);

        // Remember
        $remember = new Check('remember_me', array('value' => 'yes'));
        $remember = $this->setLabels($remember, 'Remember-Me', false);
        $this->add($remember);
    }
}