<?php
 
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

class TemplateCategoryController extends ControllerBase
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
        $this->setPageTitle('Template-Category-List');
        
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
            "builder" => TemplateCategory::filter($parameters),
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
        $this->setPageTitle('Add-Template-Category');

        $form = new TemplateCategoryForm();
        $form->changeModeNew();
        
        try {
            if ($this->request->isPost()) {
                $formData = $this->request->getPost();

                if ($form->isValid($formData)) {
                    $templateCategory = new TemplateCategory();

                    $this->db->begin();
                    if ($templateCategory->save($formData)) {
                        TemplateCategoryLanguage::saveFormData($templateCategory->id, $formData);

                        $this->flash->success(LabelMessage::addRowSuccess('Category'));

                        $this->db->commit();
                        return $this->forward("template-category/index");
                    } else {
                        $this->_feedback = current($templateCategory->getMessages())->getMessage();
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
        $this->setPageTitle('Edit-Template-Category');

        $formData = TemplateCategory::getFormData($id);
        if (! $formData) {
            $this->flash->error(LabelMessage::rowNotFound('Category'));
            return $this->forward("template-category/index");
        }
        $templateCategory = TemplateCategory::findFirst($id);

        $form = new TemplateCategoryForm($templateCategory);
        $form->bind($formData, $templateCategory);
        $form->changeModeEdit();

        try {
            if ($this->request->isPost()) {
                $formData = $this->request->getPost();

                if ($form->isValid($formData)) {

                    $this->db->begin();
                    if ($templateCategory->save($formData)) {
                        TemplateCategoryLanguage::saveFormData($templateCategory->id, $formData);

                        $this->flash->success(LabelMessage::editRowSuccess('Category'));

                        $this->db->commit();
                        return $this->forward("template-category/index");
                    } else {
                        $this->_feedback = current($templateCategory->getMessages())->getMessage();
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
        $templateCategory = TemplateCategory::findFirst($id);

        $status   = "error";
        $message  = "";
        $data     = "";

        if ($templateCategory) {
            $newStatus = ($templateCategory->$field) ? 0 : 1;
            $templateCategory->$field = $newStatus;
            if ($templateCategory->save()){
                $status   = "success";
            }else{
                $status   = "error";
                $message  = $templateCategory->getMessages();
            }
        } else {
            $status   = "error";
            $message  = LabelMessage::rowNotFound('Category');
        }

        echo AjaxResponse::toJson($status, $message, $data);
    }
}
