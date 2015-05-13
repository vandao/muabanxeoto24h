<?php
use Phalcon\Validation\Validator;
use Phalcon\Validation\ValidatorInterface;
use Phalcon\Validation\Message;

class DateValidation extends Validator implements ValidatorInterface
{
    private $_messages = array(
        'MustBeInTheFuture' => 'Field :field must be in the future',
    );
    
    /**
    * Executes the validation
    *
    * @param Phalcon\Validation $validator
    * @param string $attribute
    * @return boolean
    */
    public function validate($validator, $attribute) {
        // $value = $validator->getValue($attribute);
        $value   = $this->getOption('value');

        $message = $this->getOption('message');

        if ($value != '') {
            $mode         = $this->getOption('mode');
            $dayAtLeast   = $this->getOption('dayAtLeast');
            $functionName = "_" . $mode;

            $valid = $this->$functionName($value, $dayAtLeast);

            if ($valid) return true;
        }


        if (! $message) {
            $message = str_replace(":field", $attribute, $this->_message);
        }
        
        $validator->appendMessage(new Message($message, $attribute));

        return false;
    }

    /**
     * Validation in the future
     * @param date $value (YYYY-mm-dd)
     * @param int $nextDayAtLeast
     * @return bool
     */
    private function _future($value, $nextDayAtLeast = 0) {
        $dateFuture = strtotime("+$nextDayAtLeast day", time());

        if ($value <= date('Y-m-d', $dateFuture)) {
            return false;
        }

        return true;
    }

    /**
     * Validation in the past
     * @param date $value (YYYY-mm-dd)
     * @param int $passDayAtLeast
     * @return bool
     */
    private function _past($value, $passDayAtLeast = 0) {
        $dateFuture = strtotime("-$passDayAtLeast day", time());

        if ($value < date('Y-m-d', $dateFuture)) {
            return false;
        }

        return true;
    }
}