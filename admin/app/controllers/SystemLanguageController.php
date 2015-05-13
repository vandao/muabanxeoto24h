<?php
 
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

class SystemLanguageController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * Index action
     */
    public function indexAction()
    {        
        $this->setPageTitle('System-Language-List');
        
        if ($this->request->isPost()) {
            $this->persistent->parameters = $this->session->get('postParam', array());
        }
        $currentPage = $this->request->getQuery("page", "int", 1);
        $itemPerPage = $this->request->getQuery("itemPerPage", "int", $this->systemConfig['Backend_Number_Of_Item_Per_Page']);
        Tag::setDefault('itemPerPage', $itemPerPage);

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["idSort"] = "DESC";

        $paginator = new Paginator(array(
            "builder" => SystemLanguage::filter($parameters),
            "limit"   => $itemPerPage,
            "page"    => $currentPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displayes the creation form
     */
    public function newAction()
    {
        $this->setPageTitle('Add-System-Language');

        $form = new SystemLanguageForm();
        $form->changeModeNew();
        
        try {
            if ($this->request->isPost()) {
                $formData = $this->request->getPost();

                if ($form->isValid($formData)) {
                    $systemLanguage = new SystemLanguage();

                    $this->db->begin();
                    if ($systemLanguage->save($formData)) {
                        SystemLanguageLanguage::saveFormData($systemLanguage->id, $formData);

                        $this->flash->success(LabelMessage::addRowSuccess('Language'));

                        $this->db->commit();
                        return $this->forward("system-language/index");
                    } else {
                        $this->_feedback = current($systemLanguage->getMessages())->getMessage();
                    }
                }
            }
        } catch (AuthException $e) {
            $this->flash->error($e->getMessage());
        }

        $this->view->form     = $form;
        $this->view->feedback = $this->_feedback;
    }

    /**
     * Displayes the creation form
     */
    public function editAction($id)
    {
        $this->setPageTitle('Edit-System-Language');

        $formData = SystemLanguage::getFormData($id);
        if (! $formData) {
            $this->flash->error(LabelMessage::rowNotFound('Language'));
            return $this->forward("system-language/index");
        }
        $systemLanguage = SystemLanguageLanguage::findFirst($id);

        $form = new SystemLanguageForm($systemLanguage);
        $form->bind($formData, $systemLanguage);
        $form->changeModeEdit();

        try {
            if ($this->request->isPost()) {
                $formData = $this->request->getPost();

                if ($form->isValid($formData)) {

                    $this->db->begin();                
                    if ($systemLanguage->save($formData)) {
                        SystemLanguageLanguage::saveFormData($systemLanguage->id, $formData);

                        $this->flash->success(LabelMessage::editRowSuccess('Language'));

                        $this->db->commit();
                        return $this->forward("system-language/index");
                    } else {
                        $this->_feedback = current($systemLanguage->getMessages())->getMessage();
                    }
                }
            }
        } catch (AuthException $e) {
            $this->flash->error($e->getMessage());
        }

        $this->view->form     = $form;
        $this->view->feedback = $this->_feedback;
    }
    
    /**
     * Update status
     * 
     * @param  int $id
     * @param  string $field
     * @return void
     */
    public function ajaxEditStatusAction($id, $field)
    {
        $systemLanguage = SystemLanguage::findFirst($id);

        $status   = "error";
        $message  = "";
        $data     = "";

        if ($systemLanguage) {
            $newStatus = ($systemLanguage->$field) ? 0 : 1;
            $systemLanguage->$field = $newStatus;
            if ($systemLanguage->save()){
                $status   = "success";
            }else{
                $status   = "error";
                $message  = $systemLanguage->getMessages();
            }
        } else {
            $status   = "error";
            $message  = LabelMessage::rowNotFound('Language');
        }

        echo AjaxResponse::toJson($status, $message, $data);
    }
}
