<?php
 
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

class StaticContentController extends ControllerBase
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
        $this->setPageTitle('Static-Content-List');
        
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
        $parameters["positionSort"] = "ASC";

        $paginator = new Paginator(array(
            "builder" => StaticContent::filter($parameters),
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
        $this->setPageTitle('Add-Static-Content');

        $form = new StaticContentForm();
        $form->changeModeNew();
        
        try {
            if ($this->request->isPost()) {
                $formData = $this->request->getPost();

                if ($form->isValid($formData)) {
                    $this->db->begin();

                    $staticContent = new StaticContent();

                    if ($staticContent->save($formData)) {
                        // Upload image
                        if ($this->request->hasFiles() == true) {
                            $fileManager = new File("staticContent", "image");

                            foreach ($this->request->getUploadedFiles() as $file) {
                                $fileExtention = $fileManager->getExtension($file->getName());
                                $uploadPath    = $fileManager->getUploadPath($staticContent->id, $fileExtention);
                                $file->moveTo($uploadPath);

                                $formData['image_extension'] = $fileExtention;
                            }
                            
                            $staticContent->save($formData);
                        }

                        StaticContentLanguage::saveFormData($staticContent->id, $formData);

                        $this->flash->success(LabelMessage::addRowSuccess('Static-Content'));

                        $this->db->commit();
                        return $this->forward("static-content/index");
                    } else {
                        $this->db->rollback();
                        $this->_feedback = current($staticContent->getMessages())->getMessage();
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
        $this->setPageTitle('Edit-Static-Content');

        $formData = StaticContent::getFormData($id);
        if (! $formData) {
            $this->flash->error(LabelMessage::rowNotFound('Static-Content'));
            return $this->forward("static-content/index");
        }
        $staticContent = StaticContent::findFirst($id);

        $form = new StaticContentForm();
        $form->bind($formData, $staticContent);
        $form->changeModeEdit();

        try {
            if ($this->request->isPost()) {
                $formData = $this->request->getPost();

                if ($form->isValid($formData)) {
                    $this->db->begin();

                    if ($staticContent->save($formData)) {
                        // Upload image
                        if ($this->request->hasFiles() == true) {
                            $fileManager = new File("staticContent", "image");
                            $fileManager->deleteFile($staticContent->id, $staticContent->image_extension, $version = 'original');

                            foreach ($this->request->getUploadedFiles() as $file) {
                                $fileExtention = $fileManager->getExtension($file->getName());
                                $uploadPath    = $fileManager->getUploadPath($staticContent->id, $fileExtention);
                                $file->moveTo($uploadPath);

                                $formData['image_extension'] = $fileExtention;
                            }

                            $staticContent->save($formData);
                        }

                        StaticContentLanguage::saveFormData($staticContent->id, $formData);

                        $this->flash->success(LabelMessage::editRowSuccess('Static-Content'));

                        $this->db->commit();
                        return $this->forward("static-content/index");
                    } else {
                        $this->db->rollback();
                        $this->_feedback = current($staticContent->getMessages())->getMessage();
                    }
                }
            }
        } catch (AuthException $e) {
            $this->db->rollback();
            $this->flash->error($e->getMessage());
        }

        $this->view->imageUrl = File::getUploadUrl("staticContent", "image", $staticContent->id, $staticContent->image_extension);
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
        $staticContent = StaticContent::findFirst($id);

        $status   = "error";
        $message  = "";
        $data     = "";

        if ($staticContent) {
            $newStatus = ($staticContent->$field) ? 0 : 1;
            $staticContent->$field = $newStatus;
            if ($staticContent->save()){
                $status   = "success";
            }else{
                $status   = "error";
                $message  = $staticContent->getMessages();
            }
        } else {
            $status   = "error";
            $message  = LabelMessage::rowNotFound('Static-Content');
        }

        echo AjaxResponse::toJson($status, $message, $data);
    }
}
