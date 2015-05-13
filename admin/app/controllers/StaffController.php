<?php
 
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

class StaffController extends ControllerBase
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
        $this->setPageTitle('Staff-List');
        
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
            "builder" => Staff::filter($parameters),
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
        $this->setPageTitle('Add-Staff');

        $form = new StaffForm();
        $form->changeModeNew();
        
        try {
            if ($this->request->isPost()) {
                $formData = $this->request->getPost();

                if ($form->isValid($formData)) {
                    $staff                = new Staff();
                    $formData['password'] = $this->security->hash($formData['password']);

                    if ($staff->save($formData)) {
                        $this->flash->success(LabelMessage::addRowSuccess('Staff'));
                        return $this->forward("staff/index");
                    } else {
                        $this->_feedback = current($staff->getMessages())->getMessage();
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
        $this->setPageTitle('Edit-Staff');

        $staff = Staff::findFirstById($id);
        if (! $staff) {
            $this->flash->error(LabelMessage::rowNotFound('Staff'));
            return $this->forward("staff/index");
        }

        $form = new StaffForm($staff);
        $form->changeModeEdit();
        Tag::displayTo("password", '');
        Tag::displayTo("confirm_password", '');

        try {
            if ($this->request->isPost()) {
                $formData = $this->request->getPost();

                if ($form->isValid($formData)) {
                    if ($formData['password'] != '') {
                        $formData['password'] = $this->security->hash($formData['password']);
                    } else {
                        unset($formData['password']);
                    }

                    if ($staff->save($formData)) {
                        $this->flash->success(LabelMessage::editRowSuccess('Staff'));
                        return $this->forward("staff/index");
                    } else {
                        $this->_feedback = current($staff->getMessages())->getMessage();
                    }
                }
            }
        } catch (AuthException $e) {
            $this->flash->error($e->getMessage());
        }

        $this->view->form     = $form;
        $this->view->feedback = $this->_feedback;
    }
}
