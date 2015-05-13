<?php
 
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

class EmailQueueController extends ControllerBase
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
        $this->setPageTitle('Email-Queue-List');
        
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
            "builder" => EmailQueue::filter($parameters),
            "limit"   => $itemPerPage,
            "page"    => $currentPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    public function ajaxReviewAction($id) {
        $status   = 'error';
        $message  = '';
        $data     = array();

        if ($id > 0) {
            $emailQueue = EmailQueue::findFirst($id);

            if ($emailQueue) {
                $status   = 'success';
                $message  = '';
                $data     = $emailQueue->toArray();
            } else {
                $status   = 'error';
                $message  = LabelMessage::rowNotFound('Email-Queue');
            }
        }

        echo AjaxResponse::toJson($status, $message, $data);
    }

    public function ajaxResetAction($id) {
        $status   = 'error';
        $message  = '';
        $data     = array();

        if ($id > 0) {
            $emailQueue = EmailQueue::resetEmail($id);

            if ($emailQueue) {
                $status  = 'success';
                $message = LabelMessage::resetSuccess('Email-Queue');
            } else {
                $status  = 'error';
                $message = current($emailQueue->getMessages())->getMessage();
            }
        }

        echo AjaxResponse::toJson($status, $message, $data);
    }
}
