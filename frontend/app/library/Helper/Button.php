<?php

class Button extends \Phalcon\Mvc\User\Component {
  
  public function newRow($params = array()) {
    $action = '/' . $this->view->getControllerName() . '/new';
    $name   = $this->label->direct('Button-New-Name', false);

    foreach ($params as $param) {
      $action .= '/' . $param;
    }

    $html   = '
		<div class="form-group pull-right">
		    <a class="btn btn-primary" href="' . $action . '" title="' . $name . '"><i class="fa fa-plus"></i></a>
		</div>';

    return $html;
  }

  public function editRow($id) {
    $action = '/' . $this->view->getControllerName() . '/edit/' . $id . '/clear';
    $name   = $this->label->direct('Button-Edit-Name', false);

    $html   = '<a class="btn btn-info btn-xs" href="' . $action . '" title="' . $name . '"><i class="fa fa-pencil"></i></a>';

    return $html;
  }

  public function deleteRow($id) {
    $action = '/' . $this->view->getControllerName() . '/ajaxDelete/' . $id;
    $name   = $this->label->direct('Button-Delete-Name', false);

    $html   = '<a class="btn btn-danger btn-xs delete" href="javascript:" title="' . $name . '"><i class="fa fa-times"></i></a>';

    return $html;
  }

  public function backToList($params = array()) {
    $action = '/' . $this->view->getControllerName() . '/index';
    $name   = $this->label->direct('Button-Back-To-List-Name', false);

    foreach ($params as $param) {
      $action .= '/' . $param;
    }
    $action .= '/clear';

    $html   = '
    <a class="btn btn-primary pull-right" href="' . $action . '" title="' . $name . '">
        <i class="fa fa-long-arrow-left"></i>
    </a>';

    return $html;
  }

  public function saveRow() {
    $action = '/' . $this->view->getControllerName() . '/' . $this->view->getActionName();
    $name   = $this->label->direct('Button-Save-Name', false);

    $html   = '
    <button type="submit" class="btn btn-primary" id="btnSave">
      <i class="fa fa-check"></i>
      ' . $name . '
    </button>';

    return $html;
  }

  public function submitForm() {
    $action = '/' . $this->view->getControllerName() . '/' . $this->view->getActionName();
    $name   = $this->label->direct('Button-Submit-Name', false);

    $html   = '
		<button type="submit" class="btn btn-primary">
			<i class="fa fa-check"></i>
			' . $name . '
		</button>';

    return $html;
  }

  public function resetForm() {
    $action = '/' . $this->view->getControllerName() . '/' . $this->view->getActionName();
    $name   = $this->label->direct('Button-Reset-Name', false);

    $html   = '
        <button type="reset" class="btn btn-default">
            <i class="fa fa-refresh"></i>
			' . $name . '
		</button>';

    return $html;
  }

  public function approved($id, $status){    
    $class  = ($status) ? "btn-info" : "btn-default";    
    $action = '/' . $this->view->getControllerName() . '/ajaxEditStatus/' . $id .'/is_approved';
    $status = ($status) ? $this->label->direct('Button-Approved', false) : $this->label->direct('Button-Unapproved', false);

    $html   = "<div class='btn-group'>
              <a class='btn $class update-status btn-xs' href='$action' title='$status'><i class='fa fa-check'></i></a>
            </div>";    
    return $html;
  }

  public function disabled($id, $value, $status = "is_disabled") {
    $class  = ($value) ? "btn-danger" : "btn-default";    
    $action = '/' . $this->view->getControllerName() . '/ajaxEditStatus/' . $id . '/' . $status;
    $icon   = ($value) ? "fa-times" : "fa-check";
    $title  = ($value) ? $this->label->direct('Button-Disabled', false) : $this->label->direct('Button-Active', false);

    $html   = "<div class='btn-group'>
                <a class='btn $class update-disable btn-xs' href='$action' title='$title'><i class='fa $icon' style='width: 12px'></i></a>
            </div>";    
    return $html;
  }

  public function google($id, $status){
    $class  = ($status) ? "btn-info" : "btn-default";    
    $action = '/' . $this->view->getControllerName() . '/ajaxEditStatus/' . $id .'/is_google';
    $status = ($status) ? $this->label->direct('Button-Google', false) : $this->label->direct('Button-Not-Google', false);

    $html   = "<div class='btn-group'>
              <a class='btn $class update-google btn-xs' href='$action' title='$status'><i class='fa fa-google'></i></a>
            </div>";    
    return $html;
  }

  public function missing($flashcardId, $type = ""){
    $name   = $this->label->direct('Label-Missing', false);
    $class  = "flashcard-missing-".$type;
    switch ($type) {
        case 'term':
        case 'meaning':
        case 'sentence': 
            $html = "<div class='alert alert-danger flashcard-missing edit-text'>
              <div><i class='fa fa-times-circle'></i> $name </div>
            </div>";    
            break;
        case 'image': 
            $html = '<div class="flashcard-missing upload-ajax upload-ajax-border img-thumbnail fileinput-button ">
                        <div class="file-wrapper">
                            <div class = "file-content">
                                <i class="fa fa-plus"></i>
                            </div>
                            <div class="file-progress-holder">
                                <div class="file-progress">
                                    <div class="file-progres-bar"></div>
                                </div>
                            </div>
                        </div>
                        <input type="file" name="files[]" class="fileupload " accept="image/*">    
                    </div>';
        case 'termsound':
        case 'meaningsound':
        case 'sentencesound':                  
            $html = '<div class="flashcard-missing upload-ajax upload-ajax-border img-thumbnail fileinput-button ">
                        <div class="file-wrapper">
                            <div class = "file-content">
                                <i class="fa fa-plus"></i>
                            </div>
                            <div class="file-progress-holder">
                                <div class="file-progress">
                                    <div class="file-progres-bar"></div>
                                </div>
                            </div>
                        </div>
                        <input type="file" name="files[]" class="fileupload " accept="audio/*">    
                    </div>';
        break;
    }
    
    return $html;
  }  

  public function download($id, $isApproved){
    $disable = (!$isApproved) ? "disabled='disabled'" : "";
    $class   = ($isApproved) ? "btn-info" : "btn-default";
    $action  = '/' . $this->view->getControllerName() . '/generate/' . $id;
    $title   = $this->label->direct('Label-Generate-Package', false);
    $html    = "<div class='btn-group'>
              <a class='btn $class btn-xs btn-generate' href='$action' title='$title' $disable><i class='fa fa-flash' style='min-width: 10px'></i></a>
            </div>";
    return $html;
  }

  public function regenerate($action = 'regenerate') {
    $name    = $this->label->direct('Button-Regenerate-Name', false);

    $html    = '<a class="btn btn-primary pull-right" href="/' . $this->view->getControllerName() . '/' . $action . '" title="' . $name . '">
                    <i class="fa fa-refresh"></i>
                </a>';

    return $html;
  }

  public function playAudio($audioId, $url){
    $html = "<span class='btn btn-xs player-audio btn-default' onclick='playSound(this);'>
              <i class='fa fa-play-circle' style='font-size: 30px;''></i>
              <audio src='$url' preload='auto' controls='' style='margin-top:5px; display:none' id='$audioId'></audio>
            </span>";
    return $html;
  }

  public function checkbox($id, $status){
    $disabled = ($status) ? "" : "disabled = disabled";
    $html = "<input type='checkbox' id='$id' value='$id' class='check-item' $disabled />";
    return $html;
  }

  public function updated($id, $status){
    $class = ($status) ? "btn-success" : "btn-warning";
    $icon =  ($status) ? "fa-check" : "fa-warning";
    $title = ($status) ? $this->label->direct('Label-Zip-Package-Latest-Updated', false) : $this->label->direct('Label-Zip-Package-Need-Generate', false);
    $html = "<div class='btn-group'>
              <a class='btn $class btn-xs' href='javascript:;'' title='$title'><i class='fa $icon'></i></a>
            </div>";
    return $html;
  }

  public function publicWordlist($id, $status){
    $class  = ($status) ? "btn-info" : "btn-default";
    $icon   = ($status) ? "fa-eye" : "fa-eye-slash";
    $action = '/' . $this->view->getControllerName() . '/ajaxEditStatus/' . $id .'/is_public';
    $status = ($status) ? $this->label->direct('Button-Public', false) : $this->label->direct('Button-Unpublic', false);
    
    $html   = "<div class='btn-group'>
              <a class='btn $class update-status btn-xs' href='$action' title='$status'><i class='fa $icon'></i></a>
            </div>";
    return $html;
  }

  public function featured($id, $status){
    $class  = ($status) ? "btn-info" : "btn-default";    
    $action = '/' . $this->view->getControllerName() . '/ajaxEditStatus/' . $id .'/is_featured';
    $status = ($status) ? $this->label->direct('Button-Featured', false) : $this->label->direct('Button-Unfeatured', false);

    $html   = "<div class='btn-group'>
              <a class='btn $class update-status btn-xs' href='$action' title='$status'><i class='fa fa-star'></i></a>
            </div>";    
    return $html;
  }

  public function linkUser($userId, $label){
    $html = "<a href='/user/index/$userId' target='_blank' >$userId. $label</a>";
    return $html;
  }

  public function sort($sortField, $currentField, $currentSortDir){
    $sortClass = 'fa-sort';
    $newSortDir = 'asc';
    if ($sortField == $currentField){
        $sortClass .= '-' . $currentSortDir;
        if ($currentSortDir == $newSortDir){
            $newSortDir = 'desc';
        }
    }    

    $html = "<span data-sort='$sortField' data-sort-dir = '$newSortDir' class='sort text-info'>
                <i class='fa $sortClass' style='font-size: 18px'></i>
            </span>";
    return $html;
  }
}