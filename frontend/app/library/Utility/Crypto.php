<?php

class Crypto extends \Phalcon\Mvc\User\Component
{
    /**
     * We can generate from command like this
     * openssl genrsa -aes128 -passout pass:your-password -out privkey.pem 2048
     * openssl rsa -in privkey.pem -passin pass:your-password -pubout -out privkey.pub
     */
    static public function generateRSA($bit = 2048) {
        // generate bit RSA key
        $pkGenerate = openssl_pkey_new(array(
            'private_key_bits' => $bit,
            'private_key_type' => OPENSSL_KEYTYPE_RSA
        ));
         
        // get the private key
        openssl_pkey_export($pkGenerate, $pkGeneratePrivate); // NOTE: second argument is passed by reference
         
        // get the public key
        $pkGenerateDetails = openssl_pkey_get_details($pkGenerate);
        $pkGeneratePublic  = $pkGenerateDetails['key'];
         
        // free resources
        openssl_pkey_free($pkGenerate);
        
        return array('public' => $pkGeneratePublic, 'private' => $pkGeneratePrivate);
    }

    static public function generateRSAPublicKey($privateKey) {
        // fetch/import public key from PEM formatted string
        // remember $privateKey now is PEM formatted...
        // this is an alternative method from the public retrieval in previous
        $pkImport        = openssl_pkey_get_private($privateKey); // import
        $pkImportDetails = openssl_pkey_get_details($pkImport); // same as getting the public key in previous
        $pkImportPublic  = $pkImportDetails['key'];
        openssl_pkey_free($pkImport); // clean up

        return $pkImportPublic;
    }

    static public function encryptRSA($data, $key, $fromKey = "public") {
        if (is_array($data)) $data = json_encode($data);
        $data = base64_encode($data);

        if ($fromKey == "public") {
            if (openssl_public_encrypt($data, $encrypted, $key))
                $data = base64_encode($encrypted);
        } else {
            if (openssl_private_encrypt($data, $encrypted, $key))
                $data = base64_encode($encrypted);
        }
        
        return urlencode($data);
    }

    static public function decryptRSA($data, $key, $fromKey = "public") {
        $data = base64_decode(urldecode($data));

        if ($fromKey == "public") {
            if (openssl_private_decrypt($data, $decrypted, $key)) {
                $data = $decrypted;
            } else {
                $data = "";
            }
        } else {
            if (openssl_public_decrypt($data, $decrypted, $key)) {
                $data = $decrypted;
            } else {
                $data = "";
            }
        }

        $data = base64_decode($data);
        if (Crypto::isJson($data)) $data = json_decode($data, true);

        return $data;
    }

    static public function isJson($string) {
        json_decode($string);

        return (json_last_error() == JSON_ERROR_NONE);
    }
}
