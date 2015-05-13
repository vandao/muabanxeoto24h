<?php
 
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

class TemplateGroupController extends ControllerBase
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
        $this->setPageTitle('Template-Group-List');
        
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
            "builder" => TemplateGroup::filter($parameters),
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
        $this->setPageTitle('Add-Template-Group');

        $form = new TemplateGroupForm();
        $form->changeModeNew();
        
        try {
            if ($this->request->isPost()) {
                $formData = $this->request->getPost();

                if ($form->isValid($formData)) {
                    $templateGroup = new TemplateGroup();

                    $this->db->begin();
                    if ($templateGroup->save($formData)) {
                        TemplateGroupLanguage::saveFormData($templateGroup->id, $formData);

                        $this->flash->success(LabelMessage::addRowSuccess('Group'));

                        $this->db->commit();
                        return $this->forward("template-group/index");
                    } else {
                        $this->_feedback = current($templateGroup->getMessages())->getMessage();
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
        $this->setPageTitle('Edit-Template-Group');

        $formData = TemplateGroup::getFormData($id);
        if (! $formData) {
            $this->flash->error(LabelMessage::rowNotFound('Group'));
            return $this->forward("template-group/index");
        }
        $templateGroup = TemplateGroup::findFirst($id);

        $form = new TemplateGroupForm($templateGroup);
        $form->bind($formData, $templateGroup);
        $form->changeModeEdit();

        try {
            if ($this->request->isPost()) {
                $formData = $this->request->getPost();

                if ($form->isValid($formData)) {

                    $this->db->begin();
                    if ($templateGroup->save($formData)) {
                        TemplateGroupLanguage::saveFormData($templateGroup->id, $formData);

                        $this->flash->success(LabelMessage::editRowSuccess('Group'));

                        $this->db->commit();
                        return $this->forward("template-group/index");
                    } else {
                        $this->_feedback = current($templateGroup->getMessages())->getMessage();
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
    public function ajaxEditStatusAction($id, $field){
        $templateGroup = TemplateGroup::findFirst($id);

        $status   = "error";
        $message  = "";
        $data     = "";

        if ($templateGroup) {
            $newStatus = ($templateGroup->$field) ? 0 : 1;
            $templateGroup->$field = $newStatus;
            if ($templateGroup->save()){
                $status   = "success";
            }else{
                $status   = "error";
                $message  = $templateGroup->getMessages();
            }
        } else {
            $status   = "error";
            $message  = LabelMessage::rowNotFound('Category');
        }

        echo AjaxResponse::toJson($status, $message, $data);
    }
}
