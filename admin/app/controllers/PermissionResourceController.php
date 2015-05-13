<?php
 
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class PermissionResourceController extends ControllerBase
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
        $this->setPageTitle('Permission-Resource-List');
        
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "PermissionResource", $this->session->get('postParam', array()));
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

        $data = PermissionResource::find($parameters);

        $paginator = new Paginator(array(
            "data"  => $data,
            "limit" => $itemPerPage,
            "page"  => $currentPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    public function regenerateAction() {
        $rermissionResource = new PermissionResource();

        $path      = __DIR__;
        $resources = array();
        foreach (scandir($path) as $file) {
            if (strstr($file, "Controller.php") !== false) {
                include_once $path . DIRECTORY_SEPARATOR . $file;
                foreach (get_declared_classes() as $class) {
                    if (is_subclass_of($class, 'ControllerBase') || $class == 'SessionController') {
                        $controller = $rermissionResource->filterResourceName($class, 'Controller');
                        $resources[$controller] = array();

                        $actions = array();
                        foreach (get_class_methods($class) as $action) {
                            if (strstr($action, "Action") !== false) {
                                $action = $rermissionResource->filterResourceName($action, 'Action');

                                if (! in_array($action, $resources[$controller])) {
                                    $resources[$controller][$action] = $action;
                                }
                            }
                        }
                    }
                }
            }
        }

        $rermissionResource->regenerateResources($resources);

        $this->response->redirect('permission-resource/index/clear');
    }

    public function ajaxEditNameAction() {
        $status   = 'error';
        $message  = '';
        $formData = array();

        if ($this->request->isPost()) {
            $formData = $this->request->getPost();

            if (isset($formData['pk']) && $formData['pk'] > 0 && isset($formData['value']) && $formData['value'] != '') {
                $permissionResource = PermissionResource::findFirstByid($formData['pk']);

                if ($permissionResource) {
                    $permissionResource->name = $formData['value'];

                    if ($permissionResource->save()) {
                        $status  = 'success';
                        $message = '';
                    }
                }
            }
        }

        echo AjaxResponse::toJson($status, $message, $data);
    }
}
