<?php
 
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

class IndexController extends ControllerBase
{
    public function indexAction() {
        $auth = $this->session->get(SESSION_LOGIN);

        $this->setPageTitle('Home-Page');
    }
}
