<?php

use Phalcon\Validation\Validator;
use Phalcon\Validation\ValidatorInterface;
use Phalcon\Validation\Message;
use Phalcon\Http\Request\File;

class FileValidation extends Validator implements ValidatorInterface
{
    private $_file;
    private $_messages = array(
        'Require'       => 'Field :field must not be empty',
        'IniSize'       => 'File :field exceeds the maximum file size',
        'MaxResolution' => 'File :field must not exceed :max resolution',
        'MinResolution' => 'File :field must be at least :min resolution',
        'MaxSize'       => 'File :field exceeds the size of :max',
        'Extension'     => 'File :field must be of extension: :extensions',
        'Valid'         => 'Field :field is not valid',
    );

    /**
     * Executes the validation
     *
     * @param Phalcon\Validation $validator
     * @param string $attribute
     * @return boolean
     */
    public function validate($validator, $attribute)
    {
        //$filename = $validator->getValue($attribute);        
        $this->_file = $_FILES[$attribute];
        //var_dump($this->_file);exit;

        $isValidRequire   = $this->_require();
        $isValidExtension = $this->_extension();
        $isValidMaxSize   = $this->_maxSize();

        if (! $isValidRequire) {
            $message = $this->_getMessage('Require', $attribute);
            $validator->appendMessage(new Message($message, $attribute, 'Require'));
        }
        if (! $isValidExtension) {
            $message = $this->_getMessage('Extension', $attribute, array(':extensions' => $this->getOption('extension')));
            $validator->appendMessage(new Message($message, $attribute, 'Extension'));
        }
        if (! $isValidMaxSize) {
            $message = $this->_getMessage('MaxSize', $attribute, array(':max' => FormatNumber::filesize($this->getOption('maxSize'))));
            $validator->appendMessage(new Message($message, $attribute, 'MaxSize'));
        }

        if ($isValidRequire && $isValidExtension && $isValidMaxSize) {
            return true;
        }

        return false;
    }

    /**
     * Validation is require
     * @param bool require
     * @param string messageFileRequire
     * @return bool
     */
    private function _require() {
        if ($this->getOption('require')) {
            if ($this->_file['name'] != '') return true;

            return false;
        }

        return true;
    }

    /**
     * Validation extension
     * @param bool extension
     * @param string FileExtension
     * @return bool
     */
    private function _extension() {
        $extensionString = $this->getOption('extension');

        if ($this->_file['name'] != '' && $extensionString != '') {
            $extensions = explode(',',$extensionString);            
            $extension  = pathinfo($this->_file['name'], PATHINFO_EXTENSION);            
            if (in_array($extension, $extensions)) return true;

            return false;
        }

        return true;
    }

    /**
     * Validation filesize
     * @param bool maxSize
     * @param string FileSize
     * @return bool
     */
    private function _maxSize() {
        $filePath = $this->_file['tmp_name'];

        $maxSize = $this->getOption('maxSize');
        if ($this->_file['name'] != '' && $maxSize > 0) {
            if (is_file($filePath) && $maxSize > filesize($filePath)) return true;

            return false;
        }

        return true;
    }

    private function _getMessage($type, $attribute, $variables = array()) {
        $message = $this->getOption('message' . $type);
        
        if (! $message) { 
            $message = str_replace(":field", $attribute, $this->_messages[$type]);

            if (count($variables) > 0) {
                foreach ($variables as $key => $value) {
                    $message = str_replace($key, $value, $message);
                }
            }
        }

        return $message;
    }
}