<?php
 
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class SystemConfigController extends ControllerBase
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
        $this->setPageTitle('System-Config-List');
        
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "SystemConfig", $this->session->get('postParam', array()));
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

        $data = SystemConfig::find($parameters);

        $paginator = new Paginator(array(
            "data"  => $data,
            "limit" => $itemPerPage,
            "page"  => $currentPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displayes the creation form
     */
    public function newAction()
    {
        Tag::setTitle(LabelPage::title('Add-System-Config'));
        $this->view->pageHeader = LabelPage::header('Add-System-Config');

        $form = new SystemConfigForm();
        $form->changeModeNew();
        
        try {
            if ($this->request->isPost()) {
                $formData = $this->request->getPost();

                if ($form->isValid($formData)) {
                    $systemConfig = new SystemConfig();

                    if ($systemConfig->save($formData)) {
                        $this->flash->success(LabelMessage::addRowSuccess('Config'));
                        return $this->forward("system-config/index");
                    } else {
                        $this->_feedback = current($systemConfig->getMessages())->getMessage();
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
        Tag::setTitle(LabelPage::title('Edit-System-Config'));
        $this->view->pageHeader = LabelPage::header('Edit-System-Config');

        $systemConfig = SystemConfig::findFirstByid($id);
        if (! $systemConfig) {
            $this->flash->error(LabelMessage::rowNotFound('Config'));
            return $this->forward("system-config/index");
        }

        $form = new SystemConfigForm($systemConfig);

        try {
            if ($this->request->isPost()) {
                $formData = $this->request->getPost();

                if ($form->isValid($formData)) {                    
                    if ($systemConfig->save($formData)) {
                        $this->flash->success(LabelMessage::editRowSuccess('Config'));
                        return $this->forward("system-config/index");
                    } else {
                        $this->_feedback = current($systemConfig->getMessages())->getMessage();
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
