<?php

class LabelMessage extends \Phalcon\Mvc\User\Component {

  static public function addRowSuccess($type)
  {
    $label = new Label();

    return $label->success('Add-Row_Type', false,
      array(
        '_Type' => $label->label($type, false)
      )
    );
  }

  static public function editRowSuccess($type)
  {
    $label = new Label();

    return $label->success('Edit-Row_Type', false,
      array(
        '_Type' => $label->label($type, false)
      )
    );
  }

  static public function resetSuccess($type)
  {
    $label = new Label();

    return $label->success('Reset_Type', false,
      array(
        '_Type' => $label->label($type, false)
      )
    );
  }

  static public function invalidJson($name)
  {
    $label = new Label();

    return $label->error('Invalid-Json_Name', false,
      array(
        '_Name' => $label->label($name, false)
      )
    );
  }

  static public function editPermissionSuccess($resourceName, $isAllow)
  {
    $label            = new Label();

    return $label->success('Edit-Permission_ResourceName_IsAllow', false,
      array(
        '_ResourceName' => $label->label($resourceName, false),
        '_IsAllow'      => ($isAllow) ? $label->label('Allow', false) : $label->label('Deny', false)
      )
    );
  }

  static public function rowNotFound($type)
  {
    $label = new Label();

    return $label->error('Row-Not-Found_Type', false,
      array(
        '_Type' => $label->label($type, false)
      )
    );
  }

  static public function rowExisted($type)
  {
    $label = new Label();

    return $label->error('Row-Existed_Type', false,
      array(
        '_Type' => $label->label($type, false)
      )
    );
  }
}