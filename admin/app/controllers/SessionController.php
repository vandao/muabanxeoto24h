<?php


use Phalcon\Mvc\Controller;
use Phalcon\Tag;

class SessionController extends Controller
{
    private $_feedback = '';

    public function initialize()
    {
    $this->view->setLayout('login');
    }

    public function indexAction()
    {

    }

    public function loginAction() {
        Tag::setTitle(LabelPage::title('Login'));

        $form = new StaffLoginForm();
        
        try {
            if (! $this->request->isPost()) {
                if ($this->auth->hasRememberMe()) {
                    return $this->auth->loginWithRememberMe();
                }
            } else {
                $formData = $this->request->getPost();

              if ($form->isValid($formData)) {
                    if ($this->auth->check($formData)) {
                        return $this->response->redirect('');
                    } else {
                        $this->_feedback = $this->label->error('Login-Failed', false);
                    }
                }
            }
        } catch (AuthException $e) {
            $this->flash->error($e->getMessage());
        }

        $this->view->form     = $form;
        $this->view->feedback = $this->_feedback;
    }

    public function logoutAction() {
        $this->auth->remove();

        return $this->response->redirect('session/login'); 
    }

    public function testEncryptAction() {
        $this->view->setLayout('default');
        $this->view->setTemplateAfter('header');
        $this->view->setTemplateBefore('footer');

        $text = 'test password';

        $encrypted = $this->crypt->encrypt($text);
        echo $encrypted;
        echo "<br />";
        echo $this->crypt->decrypt($encrypted);
    }
}

