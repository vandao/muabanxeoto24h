<?php

use Phalcon\Mvc\Controller;
use Phalcon\Tag;

class ControllerBase extends Controller
{
  protected $_feedback = '';

  protected $_ajaxStatus  = 'error';
  protected $_ajaxMessage = '';
  protected $_ajaxData    = array();

  protected function initialize()
  {
    $this->view->setLayout('default');
    $this->view->setTemplateAfter('header');
    $this->view->setTemplateBefore('footer');

    $this->setLang();
    $this->setSearchAndFilterParam();

    /**
     * Disable rendering view when request by ajax
     */
    if ($this->request->isAjax()) {
      $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);
      $this->view->disable();
    }

    if ($this->request->isPost()) {
      $_POST['is_disabled'] = (! isset($_POST['is_disabled'])) ? 0 : 1;      
    }
  }

  protected function forward($uri)
  {
    list($controller, $action) = explode('/', $uri);
    return $this->dispatcher->forward(
      array(
        'controller' => $controller,
        'action'     => $action,
      )
    );
  }
  
  protected function redirect($uri)
  {
    return $this->response->redirect($uri, true);
  }

  protected function setLang()
  {
    $stringParams   = explode("?lang=", $_SERVER['REQUEST_URI']);

    $languageCode   = '';
    if (sizeof($stringParams) > 1) {
        $languageCode          = end($stringParams);
        $this->session->set('lang', $languageCode);
    }

    $currentLang = $this->session->get('lang');
    
    if (! isset($currentLang)) {
        $this->session->set('lang', 'en');
    }

    $systemLanguage = SystemLanguage::findFirst(array(
        "conditions"  => "language_code = ?0",
        "bind"        => array(
            0 => $this->session->get('lang'),
        )
      )
    );

    $this->session->set('lang_id', $systemLanguage->id);
  }

  protected function setSearchAndFilterParam()
  {
    if ($this->request->isPost()) {
      $postParam = $this->request->getPost();
      $oldParam  = $this->session->get('postParam', array());

      if (isset($postParam['typeSearch'])) {
        if (isset($oldParam['typeSearch'])) unset($oldParam[$oldParam['typeSearch']]);
        $postParam[$postParam['typeSearch']] = $postParam['keywordSearch'];
      }

      if ($oldParam) {
        $postParam = array_merge($oldParam, $postParam);
      }

      $this->session->set('postParam', $postParam);
    }

    $router = new \Phalcon\Mvc\Router();
    $router->handle();

    //Reset search and filter param
    if (in_array('clear', $router->getParams())) {
      $this->session->remove('postParam');
      $this->persistent->parameters = array();
    }
  }

  protected function setPageTitle($pageName) {
    Tag::setTitle(LabelPage::title($pageName));
    $this->view->pageHeader = LabelPage::header($pageName);
  }

  protected function getUploadedFile(){
    $uploads = $this->request->getUploadedFiles();    
    $files = array();
    foreach ($uploads as $upload) {        
        $uploadPath = File::getTmpPath() . '/' . $upload->getName();
        $upload->moveTo($uploadPath);
        $files[$upload->getKey()] = $uploadPath;
        
    }
    return $files;
  }
}
