<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class User extends UserDbTable
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
        $this->language_id  = SystemLanguage::findFirst()->id;
        $this->email        = strtolower($this->email);
        $this->date_created = new \Phalcon\Db\RawValue('NOW()');

        $ipAddress       = $_SERVER['REMOTE_ADDR'];
        $this->ip_number = new \Phalcon\Db\RawValue("INET_ATON('$ipAddress')");
    }
    
    public function beforeValidationOnUpdate()
    {
    }

    public function validation()
    {
        if ($this->email) {
            $this->validate(new Uniqueness(array(
                'field'   => 'email',
                'message' => LabelMessage::rowExisted('Email')
            )));

            if ($this->validationHasFailed() == true) {
                return false;
            }    
        }
    }

    public function getFullname(){
        return $this->first_name . ' ' . $this->last_name;
    }

    static public function getByEmail($email) {
        $row = User::findFirst(array(
            "conditions" => "email = ?1",
            "bind"       => array(
                1 => $email
            )
        ));

        return $row;
    }

    static public function saveUser($data) {
        $user              = null;
        $userSocialAccount = null;

        if ($data['email'] && $data['email'] != "") {
            $user = User::getByEmail($data['email']);
        } else {
            unset($data['email']);

            $userSocialAccount = UserSocialAccount::getByIdentity($data['provider'], $data['identity']);

            if ($userSocialAccount) {
                $user = User::findFirstById($userSocialAccount->user_id);
            }
        }

        if (! $user) $user = new User();

        $user->last_login  = new \Phalcon\Db\RawValue('NOW()');
        
        if (! $user->save($data)) {
            foreach ($user->getMessages() as $messages) {
                echo (string) $messages;die;
            }
            return false;
        }

        if (! $userSocialAccount) $userSocialAccount = new UserSocialAccount();

        $data['user_id'] = $user->id;
        $userSocialAccount->save($data);
        
        return $user;
    }
}