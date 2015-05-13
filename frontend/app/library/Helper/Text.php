<?php

abstract class Text extends \Phalcon\Mvc\User\Component {
  
  static public function hiddenEmail($email) {
    $emails   = explode('@', $email);
    $username = current($emails);
    $domain   = end($emails);

    return substr($username, 0, rand(5, 8)) . '...@' . $domain;
  }

  static public function shorten($text, $length = 100) {
    if (strlen($text) <= 100)
        return $text;
    $text = $text . ' '; 
    $text = substr($text, 0, $length); 
    $text = substr($text, 0, strrpos($text, ' '));
    $text = $text . '...'; 

    return $text; 
  }

  static public function randomString($length=3, $letters = "", $includeSpecialChars = false) {
    $specialChars                = "|@#~$%()=^*+[]{}-_";
    if ($letters == "") $letters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    if ($includeSpecialChars) $letters .= $specialChars;
    
    $string   = "";

    for($i = 0; $i < $length; $i++) {
        $char    = $letters[mt_rand(0, strlen($letters)-1)];
        $string .= $char;
    }

    return $string;
  }

  static public function getDomainNameByUrl($url) {
    $parse = parse_url($url);

    return str_replace("www.", "", $parse['host']);
  }
}