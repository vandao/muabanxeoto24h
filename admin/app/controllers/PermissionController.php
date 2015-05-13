<?php
 
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class PermissionController extends ControllerBase
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
        $this->setPageTitle('Permission-Denied');
        
        //If he doesn't have access forward him to the index controller
        $this->flash->error("You don't have access to this page");
    }

    /**
     * Manage
     */
    public function manageAction($mode, $id)
    {
        $this->view->resources      = PermissionResource::getResources();
        $this->view->resourceGroups = PermissionResourceGroup::find();
        // var_dump($this->view->resources);exit;

        if ($mode == 'staff-group') {
            $staffGroup = StaffGroup::getById($id);

            $permissions = PermissionStaffGroup::getStaffGroupPermissions($id);
            Tag::displayTo("staff_group_id", $id);

            Tag::setTitle(LabelPage::title('Permission', array(
                '_Name' => $staffGroup->staff_group
            )));
            $this->view->pageHeader = LabelPage::header('Permission', array(
                '_Name' => $staffGroup->staff_group
            ));
        } else {
            $staff      = Staff::findFirst($id);
            $staffGroup = StaffGroup::getById($staff->staff_group_id);

            $permissions = PermissionStaff::getStaffPermissions($id);
            Tag::displayTo("staff_id", $id);

            Tag::setTitle(LabelPage::title('Permission', array(
                '_Name'  => $staff->full_name,
                '_Group' => $staffGroup->staff_group
            )));
            $this->view->pageHeader = LabelPage::header('Permission', array(
                '_Name'  => $staff->full_name,
                '_Group' => $staffGroup->staff_group
            ));
        }

        $this->view->permissions = $permissions;
        $this->view->mode        = $mode;
        $this->view->id          = $id;
        // var_dump($this->view->permissions);exit;
    }

    public function ajaxEditPermissionStaffGroupAction() {
        $status   = 'error';
        $message  = '';
        $formData = array();

        if ($this->request->isPost()) {
            $form     = new PermissionStaffGroupForm();
            $formData = $this->request->getPost();
            $formData['is_allow'] = ($formData['is_allow'] == 'true') ? 1 : 0;

            if ($form->isValid($formData)) {
                $message = PermissionStaffGroup::updatePermission($formData);

                if ($message == 'success') {
                    $resource = PermissionResource::getResource($formData['controller_name'], $formData['action_name']);
                    
                    $status  = $message;
                    $message = LabelMessage::editPermissionSuccess($resource->name, $formData['is_allow']);
                } else {
                    $message = current($permissionRole->getMessages())->getMessage();
                }
            } else {
                foreach ($form->getMessages() as $message) {
                    $message = $message->getMessage();break;
                }
            }
        }

        echo AjaxResponse::toJson($status, $message, $data);
    }

    public function ajaxEditPermissionStaffAction() {
        $status   = 'error';
        $message  = '';
        $formData = array();

        if ($this->request->isPost()) {
            $form     = new PermissionStaffForm();
            $formData = $this->request->getPost();
            $formData['is_allow']  = ($formData['is_allow'] == 'true') ? 1 : 0;
            $formData['is_custom'] = 1;

            if ($form->isValid($formData)) {
                $message = PermissionStaff::updatePermission($formData);

                if ($message == 'success') {
                    $resource = PermissionResource::getResource($formData['controller_name'], $formData['action_name']);

                    $status  = $message;
                    $message = LabelMessage::editPermissionSuccess($resource->name, $formData['is_allow']);
                }
            } else {
                foreach ($form->getMessages() as $message) {
                    $message = $message->getMessage();break;
                }
            }
        }

        echo AjaxResponse::toJson($status, $message, $data);
    }

    public function regenerateStaffAction($id) {
        PermissionStaff::regeneratePermissionByStaff($id);

        $this->redirect("/permission/manage/staff/$id");
    }
}
