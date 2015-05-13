<?php
 
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

class StaffGroupController extends ControllerBase
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
        $this->setPageTitle('Staff-Group-List');
        
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
            "builder" => StaffGroup::filter($parameters),
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
        $this->setPageTitle('Add-Staff-Group');

        $form = new StaffGroupForm();
        $form->changeModeNew();
        
        try {
            if ($this->request->isPost()) {
                $formData = $this->request->getPost();

                if ($form->isValid($formData)) {
                    $staffGroup = new StaffGroup();

                    $this->db->begin();
                    if ($staffGroup->save($formData)) {
                        StaffGroupLanguage::saveFormData($staffGroup->id, $formData);

                        $this->flash->success(LabelMessage::addRowSuccess('Group'));

                        $this->db->commit();
                        return $this->forward("staff-group/index");
                    } else {
                        $this->_feedback = current($staffGroup->getMessages())->getMessage();
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
        $this->setPageTitle('Edit-Staff-Group');

        $formData = StaffGroup::getFormData($id);
        if (! $formData) {
            $this->flash->error(LabelMessage::rowNotFound('StaffGroup'));
            return $this->forward("staff-group/index");
        }
        $staffGroup = StaffGroup::findFirst($id);

        $form = new StaffGroupForm($staffGroup);
        $form->bind($formData, $staffGroup);
        $form->changeModeEdit();

        try {
            if ($this->request->isPost()) {
                $formData = $this->request->getPost();

                if ($form->isValid($formData)) {
                    
                    $this->db->begin();
                    if ($staffGroup->save($formData)) {
                        StaffGroupLanguage::saveFormData($staffGroup->id, $formData);

                        $this->flash->success(LabelMessage::editRowSuccess('Group'));

                        $this->db->commit();
                        return $this->forward("staff-group/index");
                    } else {
                        $this->_feedback = current($staffGroup->getMessages())->getMessage();
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
