<?php
use Phalcon\Validation\Validator;
use Phalcon\Validation\ValidatorInterface;
use Phalcon\Validation\Message;

class CreditCardValidation extends Validator implements ValidatorInterface
{
    private $_messages = array(
        'InvalidCardNumber' => 'Field :field invalid',
    );
    
    /**
    * Executes the validation
    *
    * @param Phalcon\Validation $validator
    * @param string $attribute
    * @return boolean
    */
    public function validate($validator, $attribute) {
        $value = $validator->getValue($attribute);

        $message = $this->getOption('message');

        if ($value != '') {
            $mode         = $this->getOption('mode');
            $functionName = "_" . $mode;

            $valid = $this->$functionName($value);

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
    private function _cardNumber($value) {
        $cardType = CreditCard::getCardType($value);
        
        if ($cardType == "Unknown") {
            return false;
        }

        return true;
    }
}