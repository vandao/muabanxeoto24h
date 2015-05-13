<?php
 
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

class TemplateController extends ControllerBase
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
        $this->setPageTitle('Template-List');
        
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
            "builder" => Template::filter($parameters),
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
        $this->setPageTitle('Add-Template');

        $form = new TemplateForm();
        $form->changeModeNew();
        
        try {
            if ($this->request->isPost()) {
                $formData = $this->request->getPost();
                // var_dump($formData);exit;

                if ($form->isValid($formData)) {
                    $template = new Template();

                    $this->db->begin();
                    if ($template->save($formData)) {
                        TemplateLanguage::saveFormData($template->id, $formData);

                        $this->flash->success(LabelMessage::addRowSuccess('Template'));

                        $this->db->commit();
                        return $this->forward("template/index");
                    } else {
                        $this->db->rollback();
                        $this->_feedback = current($template->getMessages())->getMessage();
                    }
                }
            }
        } catch (AuthException $e) {
            $this->db->rollback();
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
        $this->setPageTitle('Edit-Template');

        $formData = Template::getFormData($id);
        if (! $formData) {
            $this->flash->error(LabelMessage::rowNotFound('Template'));
            return $this->forward("template/index");
        }
        $template = Template::findFirst($id);

        $form = new TemplateForm();
        $form->bind($formData, $template);
        $form->changeModeEdit();

        try {
            if ($this->request->isPost()) {
                $formData = $this->request->getPost();

                if ($form->isValid($formData)) {

                    $this->db->begin();
                    if ($template->save($formData)) {
                        TemplateLanguage::saveFormData($template->id, $formData);

                        $this->flash->success(LabelMessage::editRowSuccess('Template'));

                        $this->db->commit();
                        return $this->forward("template/index");
                    } else {
                        $this->db->rollback();
                        $this->_feedback = current($template->getMessages())->getMessage();
                    }
                }
            }
        } catch (AuthException $e) {
            $this->db->rollback();
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
        $template = Template::findFirst($id);

        $status   = "error";
        $message  = "";
        $data     = "";

        if ($template) {
            $newStatus = ($template->$field) ? 0 : 1;
            $template->$field = $newStatus;
            if ($template->save()){
                $status   = "success";
            }else{
                $status   = "error";
                $message  = $template->getMessages();
            }
        } else {
            $status   = "error";
            $message  = LabelMessage::rowNotFound('Category');
        }

        echo AjaxResponse::toJson($status, $message, $data);
    }

    public function ajaxReviewAction($id) {
        $status   = 'error';
        $message  = '';
        $formData = array();

        if ($id > 0) {
            $template = Template::findFirst($id);

            if ($template) {
                $status       = 'success';
                $message      = '';
                $formData     = $template->toArray();
                $templateLanguage = TemplateLanguage::getByTemplate($id, $this->session->get('lang_id'));

                $formData['subject']  = $templateLanguage->template_subject;
                $formData['body']     = $templateLanguage->template_body;
            } else {
                $status   = 'error';
                $message  = LabelMessage::rowNotFound('Template');
            }
        }

        echo AjaxResponse::toJson($status, $message, $formData);
    }

    public function ajaxSendTestEmailAction() {
        $status  = 'error';
        $message = '';
        $data    = array();

        if ($this->request->isPost()) {
            $email = $this->request->getPost();
            
            if (SendEmail::send($email)) {
                $status   = 'success';
                $message  = '';
            } else {
                $status   = 'error';
                $message  = 'Can not send';
            }
        }

        echo AjaxResponse::toJson($status, $message, $data);
    }
}
