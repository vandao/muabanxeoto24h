<?php
 
use Phalcon\Tag;

class IndexController extends ControllerBase
{

    public function indexAction($time = '')
    {
        Tag::setTitle(LabelPage::title('Dashboard'));
        $this->view->pageHeader = LabelPage::header('Dashboard');

    }

}
