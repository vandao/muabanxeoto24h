<?php
 
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class PermissionResourceGroupController extends ControllerBase
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
        $this->setPageTitle('Resource-Group-List');
        
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "PermissionResourceGroup", $this->session->get('postParam', array()));
            $this->persistent->parameters = $query->getParams();
        }
        $currentPage = $this->request->getQuery("page", "int", 1);
        $itemPerPage = $this->request->getQuery("itemPerPage", "int", $this->systemConfig['Backend_Number_Of_Item_Per_Page']);
        Tag::setDefault('itemPerPage', $itemPerPage);

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id DESC";

        $data = PermissionResourceGroup::find($parameters);

        $paginator = new Paginator(array(
            "data"  => $data,
            "limit" => $itemPerPage,
            "page"  => $currentPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * New resource group
     */
    public function newAction()
    {
        $this->setPageTitle('Add-Resource-List');

        $form = new PermissionResourceGroupForm();
        $form->changeModeNew();
        
        try {
            if ($this->request->isPost()) {
                $formData = $this->request->getPost();

                if ($form->isValid($formData)) {
                    $permissionResourceGroup = new PermissionResourceGroup();

                    if ($permissionResourceGroup->save($formData)) {
                        $this->flash->success(LabelMessage::addRowSuccess('Group'));
                        return $this->forward("permission-resource-group/index");
                    } else {
                        $this->_feedback = current($permissionResourceGroup->getMessages())->getMessage();
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
     * Edit resource group
     */
    public function editAction($id)
    {
        $this->setPageTitle('Edit-Resource-Group');

        $permissionResourceGroup = PermissionResourceGroup::findFirstByid($id);
        if (! $permissionResourceGroup) {
            $this->flash->error(LabelMessage::rowNotFound('Group'));
            return $this->forward("permission-resource-group/index");
        }

        $form = new PermissionResourceGroupForm($permissionResourceGroup);

        try {
            if ($this->request->isPost()) {
                $formData = $this->request->getPost();

                if ($form->isValid($formData)) {
                    if ($permissionResourceGroup->save($formData)) {
                        $this->flash->success(LabelMessage::editRowSuccess('Group'));
                        return $this->forward("permission-resource-group/index");
                    } else {
                        $this->_feedback = current($permissionResourceGroup->getMessages())->getMessage();
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
