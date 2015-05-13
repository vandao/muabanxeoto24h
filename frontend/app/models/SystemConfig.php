<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class SystemConfig extends SystemConfigDbTable
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
            'field'   => 'key',
            'message' => LabelMessage::rowExisted('Key')
        )));

        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    static public function getGroupConfig($group){
        $configs = array();
        foreach (SystemConfig::find("key like '$group%'") as $config) {
            $configs[$config->key] = $config->value;
        }
        return $configs;
    }

    static public function getMd5PackageConfig(){
        $configs = SystemConfig::getGroupConfig('Package');
        $md5String = '';
        foreach ($configs as $key => $value) {
            $md5String .= $value;
        }
        return $md5String;
    }
}