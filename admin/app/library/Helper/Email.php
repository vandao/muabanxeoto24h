<?php

class Email extends \Phalcon\Mvc\User\Component {
    public function newRegisterPackage($userId, $servicePackageId, $expiryDate, $isDebug = false) {
        $user           = User::findFirst($userId);
        $servicePackage = ServicePackage::getById($servicePackageId);

        if ($user && $servicePackage) {
            $template = Template::getEmailTemplate('new-register-package', $user->language_id);

            if ($template) {
                $variables = array(
                    '{{FirstName}}'    => $user->first_name,
                    '{{LastName}}'     => $user->last_name,
                    '{{Email}}'        => $user->email,
                    '{{PackageName}}'  => $servicePackage->service_package_name,
                    '{{PackagePrice}}' => $servicePackage->service_package_price,
                    '{{CurrencyCode}}' => $servicePackage->currency_code,
                    '{{ExpiryDate}}'   => $expiryDate
                );

                $template = $this->_insertVariables($template, $variables);
                $email    = array(
                    'to'      => $this->systemConfig['Sendmail-Receive-Contact-Us'],
                    'subject' => $template['subject'],
                    'body'    => $template['body'],
                );
                $this->_debug($email, $isDebug);

                SendEmail::send($email);

                return true;
            }
        }

        return false;
    }

    public function autoRegister($userId, $password, $isDebug = false) {
        $user = User::findFirst($userId);

        if ($user) {
            $template = Template::getEmailTemplate('auto-register', $user->language_id);

            if ($template) {
                $variables = array(
                    '{{FirstName}}' => $user->first_name,
                    '{{LastName}}'  => $user->last_name,
                    '{{Email}}'     => $user->email,
                    '{{Password}}'  => $password,
                );

                $template = $this->_insertVariables($template, $variables);
                $email    = array(
                    'to'      => $user->email,
                    'subject' => $template['subject'],
                    'body'    => $template['body'],
                );
                $this->_debug($email, $isDebug);

                SendEmail::send($email);

                return true;
            }
        }

        return false;
    }

    public function contactUs($data, $isDebug = false) {
        $languageId = SystemLanguage::getCurrentLanguageId();

        $template   = Template::getEmailTemplate('contact-us', $languageId);
        if ($template) {
            $variables = array(
                '{{Subject}}'     => $data['subject'],
                '{{FirstName}}'   => $data['first_name'],
                '{{LastName}}'    => $data['last_name'],
                '{{Email}}'       => $data['email'],
                '{{CompanyName}}' => $data['company_name'],
                '{{PhoneNumber}}' => $data['phone_number'],
                '{{Detail}}'      => $data['detail'],
            );

            $template = $this->_insertVariables($template, $variables);
            $email    = array(
                'to'      => $this->systemConfig['Sendmail-Receive-Contact-Us'],
                'subject' => $template['subject'],
                'body'    => $template['body'],
            );
            $this->_debug($email, $isDebug);

            SendEmail::send($email);

            return true;
        }

        return false;
    }













    private function _debug($email, $isDebug) {
        if ($isDebug) {
            echo '<h1>To: ' . $email['to'] . '</h1>';
            echo '<h1>' . $email['subject'] . '</h1>';
            echo $email['body'];

            die();
        }
    }

    private function _insertVariables($template, $variables) {
        $subject = $template['template_subject'];
        $body    = $template['template_body'];

        foreach ($variables as $key => $value) {
            $subject = str_replace($key, $value, $subject);
            $body    = str_replace($key, $value, $body);
        }

        // Replace full path of image
        $body = str_replace("../../..", $this->config->url->frontend, $body);

        return array('subject' => $subject, 'body' => $body);
    }
}