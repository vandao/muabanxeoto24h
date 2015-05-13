<?php

use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\Between;

class FormBase extends Phalcon\Forms\Form
{
    public function initialize()
    {
        // CSRF
        $csrf = new Hidden('csrf');
        $this->add($csrf);


        $this->_formatNumber = new FormatNumber();
    }

    public function setRequireField($element) {
        $element->addValidators(array(
            new PresenceOf(array(
                'message' => $this->label->error('Field-Required_FieldName', false,
                    array(
                        '_FieldName' => $element->getLabel(),
                    )
                )
            )),
        ));

        return $element;
    }

    public function setValidateEmail($element) {
        $element->addValidators(array(
            new Email(array(
                'message' => $this->label->error('Field-Value-Invalid_FieldName', false,
                    array(
                        '_FieldName' => $element->getLabel(),
                    )
                )
            ))
        ));

        return $element;
    }

    public function setValidateNumber($element) {
        $element->addValidators(array(
            new Regex(array(
                'pattern' => '/^[0-9]+(?:\.[0-9]{0,2})?$/',
                'message' => $this->label->error('Field-Value-Must-Be-Number_FieldName', false,
                    array(
                        '_FieldName' => $element->getLabel(),
                    )
                )
            ))
        ));

        return $element;
    }

    public function setValidateFloat($element, $decimal = 2) {
        $element->addValidators(array(
            new Regex(array(
                'pattern' => '/^[0-9]+(?:\.[0-9]{0,' . $decimal . '})?$/',
                'message' => $this->label->error('Field-Value-Must-Be-Number_FieldName', false,
                    array(
                        '_FieldName' => $element->getLabel(),
                    )
                )
            ))
        ));

        return $element;
    }

    public function setValidateBetween($element, $min = 0, $max = 100) {
        $element->addValidators(array(
            new Between(array(
                'minimum' => $min,
                'maximum' => $max,
                'message' => $this->label->error('Field-Value-Must-Between_FieldName_Min_Max', false,
                    array(
                        '_FieldName' => $element->getLabel(),
                        '_Min'       => $min,
                        '_Max'       => $max,
                    )
                )
            ))
        ));

        return $element;
    }

    public function setValidateMinLength($element, $min) {
        $element->addValidators(array(
            new StringLength(array(
                'min'            => $min,
                'messageMinimum' => $this->label->error('Field-Value-Too-Short_FieldName_Min', false,
                    array(
                        '_FieldName' => $element->getLabel(),
                        '_Min'       => $min
                    )
                )
            )),
        ));

        return $element;
    }

    public function setValidateMaxLength($element, $max) {
        $element->addValidators(array(
            new StringLength(array(
                'max'            => $max,
                'messageMaximum' => $this->label->error('Field-Value-Too-Long_FieldName_Max', false,
                    array(
                        '_FieldName' => $element->getLabel(),
                        '_Max'       => $max
                    )
                )
            )),
        ));

        return $element;
    }

    public function setValidateConfirmation($element, $width) {
        $element->addValidators(array(
            new Confirmation(array(
                'message' => $this->label->error('Field-Value-Not-Match-Confirmation_FieldName', false,
                    array(
                        '_FieldName' => $element->getLabel(),
                    )
                ),
                'with'    => $width
            ))
        ));

        return $element;
    }

    public function setValidateFile($element, $require = false, $extension = '', $maxSize = 0) {
        $element->addValidators(array(
            new FileValidation(array(
                'require'          => $require,
                'messageRequire'   => $this->label->error('Field-Required_FieldName', false,
                    array(
                        '_FieldName' => $element->getLabel(),
                    )
                ),
                'extension'        => $extension,
                'messageExtension' => $this->label->error('Field-File-Extension-Invalid_FieldName_Extension', false,
                    array(
                        '_FieldName' => $element->getLabel(),
                        '_Extension' => $extension,
                    )
                ),
                'maxSize'          => $maxSize,
                'messageMaxSize'   => $this->label->error('Field-File-Max-Size-Invalid_FieldName_MaxSize', false,
                    array(
                        '_FieldName' => $element->getLabel(),
                        '_MaxSize'   => $this->_formatNumber->filesize($maxSize),
                    )
                ),
            )),
        ));

        return $element;
    }

    /**
     * Set validate date in future
     * @param $element
     * @param $elements (day, month, year)
     */
    public function setDateInFuture($element, $elements, $futureInDays = 0) {
        $day = '01';
        if (isset($elements['day']) && isset($_POST[$elements['day']])) {
            $value = $_POST[$elements['day']];
            if ($value) $day = $value;
        }
        $month = '01';
        if (isset($elements['month']) && isset($_POST[$elements['month']])) {
            $value = $_POST[$elements['month']];
            if ($value) $month = $value;
        }
        $year = date('Y');
        if (isset($elements['year']) && isset($_POST[$elements['year']])) {
            $value = $_POST[$elements['year']];
            if ($value) $year = $value;
        }

        $element->addValidators(array(
            new DateValidation(array(
                'value'       => $year . '-' . $month . '-' . $day,
                'mode'        => 'future',
                'dayAtLeast'  => $futureInDays,
                'message' => $this->label->error('Field-Date-Must-Be-In-The-Future_FieldName', false,
                    array(
                        '_FieldName' => $element->getLabel(),
                    )
                )
            )),
        ));

        return $element;
    }

    /**
     * Set validate date in future
     * @param $element
     * @param $elements (day, month, year)
     */
    public function setValidateCardNumber($element) {
        $element->addValidators(array(
            new CreditCardValidation(array(
                'mode'    => "cardNumber",
                'message' => $this->label->error('Field-Card-Number-Invalid_FieldName', false,
                    array(
                        '_FieldName' => $element->getLabel(),
                    )
                )
            )),
        ));

        return $element;
    }

    public function setLabels($element, $fileName, $isIncludeDescription = true, $variables = array()) {
        $variableKey = '';
        foreach ($variables as $key => $value) {
            $variableKey .= $key;
        }
        $element->setLabel($this->label->label($fileName . $variableKey, false, $variables));

        if ($isIncludeDescription) {
            $element->setAttribute('placeholder', $this->label->form($fileName . '-Description', false));
        }

        return $element;
    }

    /**
     * Prints messages for horizontal form
     */
    public function messageHorizontal($customMessage = '')
    {
        $messages = $this->getMessages();

        $html = '';
        if (count($messages) > 0 || $customMessage != '') {
            $html = '<div class="form-group">' .
                        '<div class="col-md-offset-2 col-md-10">' .
                            '<div class="alert alert-danger" style="margin-bottom: 0px;">';

            foreach ($messages as $keyElement => $value) {
                $html .= '<div><i class="fa fa-times-circle"></i> ' . $value->getMessage() . '</div>';
            }

            if ($customMessage != '') $html .= '<div><i class="fa fa-times-circle"></i> ' . $customMessage . '</div>';

            $html .= '</div>' .
                '</div>' .
            '</div>';
        }

        return $html;
        /**
        if ($this->hasMessagesFor($name)) {
            foreach ($this->getMessagesFor($name) as $message) {
                $this->flash->error($message);
            }
        }
        */
    }

    /**
     * Prints messages for vertical form
     */
    public function messageVertical($customMessages = '')
    {
        $messages = $this->getMessages();
        if ($customMessages == ''){
            $customMessages = array();
        }
        if (!is_array($customMessages)){
            $customMessages = array($customMessages);
        }

        $html = '';
        if (count($messages) > 0 || count($customMessages) > 0) {
            $html = '<div class="alert alert-danger">';

            foreach ($messages as $keyElement => $value) {
                $html .= '<div><i class="fa fa-times-circle"></i> ' . $value->getMessage() . '</div>';
            }
            foreach ($customMessages as $customMessage) {
                if ($customMessages != ''){
                    $html .= '<div><i class="fa fa-times-circle"></i> ' . $customMessage . '</div>'; 
                }  
            }
            

            $html .= '</div>' ;
        }


        return $html;
    }

    public function renderHorizontal($name, $attributes = array())
    {
        if ($this->has($name)) {
            $element     = $this->get($name);
            $elementType = $this->_getType($element);

            if ($elementType != 'checkbox') {
                if (isset($attributes['class'])) {
                    $attributes['class'] .= ' form-control';
                } else {
                    $attributes['class'] = 'form-control';
                }
            }
            foreach ($attributes as $attribute => $value) {
                $element->setAttribute($attribute, $value);
            }        

            switch ($elementType) {
                case 'text':
                    $html = $this->_formTextHorizontal($element);
                    break;
                case 'checkbox':
                    $html = $this->_formCheckboxHorizontal($element);
                    break;
                case 'hidden':
                    $html = $element->__toString();
                    break;            
                default:
                    $html = $this->_formTextHorizontal($element);
                    break;
            }

            echo $html;
        }
    }

    public function renderVertical($name, $attributes = array())
    {
        if ($this->has($name)) {
            $element     = $this->get($name);
            $elementType = $this->_getType($element);

            if ($elementType != 'checkbox') {
                if (isset($attributes['class'])) {
                    $attributes['class'] .= ' form-control';
                } else {
                    $attributes['class'] = 'form-control';
                }
            }
            foreach ($attributes as $attribute => $value) {
                $element->setAttribute($attribute, $value);
            }

            switch ($elementType) {
                case 'text':
                    $html = $this->_formTextVertical($element);
                    break;
                case 'checkbox':
                    $html = $this->_formCheckboxVertical($element);
                    break;
                case 'hidden':
                    $html = $element->__toString();
                    break;
                default:
                    $html = $this->_formTextVertical($element);
                    break;
            }

            echo $html;
        }
    }

    public function renderInline($name, $attributes = array(), $fileErrors = array())
    {
        if ($this->has($name)) {
            $element     = $this->get($name);
            $elementType = $this->_getType($element);

            if ($elementType != 'checkbox') {
                if (isset($attributes['class'])) {
                    $attributes['class'] .= ' form-control';
                } else {
                    $attributes['class'] = 'form-control';
                }
            }
            foreach ($attributes as $attribute => $value) {
                $element->setAttribute($attribute, $value);
            }        

            switch ($elementType) {
                case 'text':
                    $html = $this->_formTextInline($element);
                    break;
                case 'checkbox':
                    $html = $this->_formCheckboxHorizontal($element);
                    break;
                case 'hidden':
                    $html = $element->__toString();
                    break;
                case 'file':
                    $html = $this->_formFileHorizontal($element, $fileErrors);
                    break;
                default:
                    $html = $this->_formTextInline($element);
                    break;
            }

            echo $html;
        }
    }

    private function _getType($element) {
        $elementHtml = $element->__toString();

        if (is_numeric(stripos($elementHtml, "<input"))) {
            $firstPos = stripos($elementHtml, 'type="') + strlen('type="');
            $lastPos  = stripos($elementHtml, '"', $firstPos);

            return substr($elementHtml, $firstPos, $lastPos - $firstPos);
        } elseif (is_numeric(stripos($elementHtml, "<textarea"))) {
            return 'textarea';
        } else {
            return 'select';
        }
    }

    private function _formTextHorizontal($element) {
        //Get any generated messages for the current element
        $messages = $this->getMessagesFor($element->getName());

        if (count($messages)) {
            $html = '<div class="form-group has-error has-feedback">'.
                '<label for="' . $element->getName() . '" class="col-md-2 control-label">' . $element->getLabel() . '</label>' .
                '<div class="col-md-10">' .
                    $element .
                    '<span class="fa fa-times form-control-feedback"></span>' .
                '</div>' .
            '</div>';
        } else {
            $html = '<div class="form-group">' .
                '<label for="'  . $element->getName() . '" class="col-md-2 control-label">' . $element->getLabel() . '</label>' .
                '<div class="col-md-10">' .
                    $element .
                '</div>' .
            '</div>';
        }

        return $html;
    }

    private function _formCheckboxHorizontal($element) {
        //Get any generated messages for the current element
        $messages = $this->getMessagesFor($element->getName());

        if (count($messages)) {
            $html = '<div class="form-group has-error">' .
                '<div class="col-md-offset-2 col-md-10">' .
                    '<div class="checkbox">' .
                        '<label>' .
                            $element . $element->getLabel() .
                        '</label>' .
                    '</div>' .
                '</div>' .
            '</div>';
        } else {
            $html = '<div class="form-group">' .
                '<div class="col-md-offset-2 col-md-10">' .
                    '<div class="checkbox">' .
                        '<label>' .
                            $element . $element->getLabel() .
                        '</label>' .
                    '</div>' .
                '</div>' .
            '</div>';
        }

        return $html;
    }

    private function _formTextVertical($element) {
        //Get any generated messages for the current element
        $messages = $this->getMessagesFor($element->getName());

        if (count($messages)) {
            foreach ($messages as $message) {
                break;
            }

            $html = '<div class="form-group has-error has-feedback">' .
                '<label for="' . $element->getName() . '" class="control-label">' . $element->getLabel() . '</label>' .
                '<span id="' . $element->getName() . '-error" class="checkbox-inline">' . $message . '</span>' .
                $element .
                '<span class="glyphicon glyphicon-remove form-control-feedback"></span>' .
            '</div>';
        } else {
            $html = '<div class="form-group">' .
                '<label for="' . $element->getName() . '" class="control-label">' . $element->getLabel() . '</label>' .
                $element .
            '</div>';
        }

        return $html;
    }

    private function _formCheckboxVertical($element) {
        //Get any generated messages for the current element
        $messages = $this->getMessagesFor($element->getName());

        if (count($messages)) {
            $html = '<div class="checkbox has-error">' .
                        '<label>' .
                            $element . $element->getLabel() .
                        '</label>' .
                    '</div>';
        } else {
            $html = '<div class="checkbox">' .
                        '<label>' .
                            $element . $element->getLabel() .
                        '</label>' .
                    '</div>';
        }

        return $html;
    }

    private function _formTextInline($element) {
        //Get any generated messages for the current element
        $messages = $this->getMessagesFor($element->getName());

        if (count($messages)) {
            $html = '<div class="form-group has-error has-feedback">'.
                        '<label for="' . $element->getName() . '" class="">' . $element->getLabel() . '</label> ' .
                        $element .
                        '<span class="glyphicon glyphicon-remove form-control-feedback"></span>' .
                    '</div>';
        } else {
            $html = '<div class="form-group">' .
                        '<label for="'  . $element->getName() . '">' . $element->getLabel() . '</label> ' .
                        $element .
                    '</div>';
        }

        return $html;
    }

}
