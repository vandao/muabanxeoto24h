<?php
 
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

class SystemLabelController extends ControllerBase
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
        $this->setPageTitle('System-Label-List');
        
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
            "builder" => SystemLabel::filter($parameters),
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
        $this->setPageTitle('Add-System-Label');

        $form = new SystemLabelForm();
        $form->changeModeNew();
        
        try {
            if ($this->request->isPost()) {
                $formData = $this->request->getPost();

                if ($form->isValid($formData)) {
                    $systemLabel = new SystemLabel();

                    if ($systemLabel->save($formData)) {
                        $this->flash->success(LabelMessage::addRowSuccess('Label'));
                        return $this->forward("system-label/index/clear");
                    } else {
                        $this->_feedback = current($systemLabel->getMessages())->getMessage();
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
     * Displayes the edit form
     */
    public function editAction($id)
    {
        $this->setPageTitle('Edit-System-Label');

        $systemLabel = SystemLabel::findFirstByid($id);
        if (! $systemLabel) {
            $this->flash->error(LabelMessage::rowNotFound('Label'));
            return $this->forward("system-label/index/clear");
        }

        $form = new SystemLabelForm($systemLabel);

        try {
            if ($this->request->isPost()) {
                $formData = $this->request->getPost();

                if ($form->isValid($formData)) {
                    if ($systemLabel->save($formData)) {
                        $this->flash->success(LabelMessage::editRowSuccess('Label'));
                        return $this->forward("system-label/index/clear");
                    } else {
                        $this->_feedback = current($systemLabel->getMessages())->getMessage();
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
     * Ajax rename
     */
    public function renameAction()
    {
        $data = array('message' => STATUS_ERROR, 'data' => array());
        if (! $this->request->isPost()) {
            $this->flash->error(LabelMessage::rowNotFound('Label'));
            return $this->forward("system-label/index");
        }

        $formData = $this->request->getPost();
        if (! isset($formData['value']) || ! isset($formData['pk']) || ! isset($formData['value']['value']) || ! isset($formData['value']['hint'])) {
            $this->flash->error(LabelMessage::rowNotFound('Label'));
            return $this->forward("system-label/index");
        }

        $formData = array(
            'id'          => $formData['pk'],
            'value'       => $formData['value']['value'],
            'hint'        => $formData['value']['hint'],
        );

        $systemLabel = SystemLabel::findFirstById($formData['id']);
        if (! $systemLabel) {
            $this->flash->error(LabelMessage::rowNotFound('Label'));
            return $this->forward("system-label/index");
        }

        try {
            if (! $systemLabel->save($formData)) {
                $data['message'] = current($systemLabel->getMessages())->getMessage();
            } else {
                $data['message'] = STATUS_COMPLETED;
            }
        } catch (AuthException $e) {
            $this->flash->error($e->getMessage());
        }

        echo json_encode($data);
        $this->view->disable();
    }

    public function ajaxDeleteAction($id) {
        if ($id > 0) {
            $systemLabel = SystemLabel::findFirstById($id);

            if ($systemLabel) {
                if ($systemLabel->delete()) {
                    $this->_ajaxStatus  = 'success';
                }
            } else {
                $this->_ajaxMessage = LabelMessage::rowNotFound('Label');
            }
        } else {
            $this->_ajaxMessage = LabelMessage::rowNotFound('Label');
        }

        echo AjaxResponse::toJson($this->_ajaxStatus, $this->_ajaxMessage, $this->_ajaxData);
    }
}
