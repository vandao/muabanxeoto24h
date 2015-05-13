<?php
 
use Phalcon\Tag;
use Phalcon\Paginator\Adapter\QueryBuilder as Paginator;

class UserController extends ControllerBase
{
    public function profileAction() {
        $auth = $this->session->get(SESSION_LOGIN);

        if (! $auth) {
            return $this->redirect("/session/login");
        }

        $this->setPageTitle('Profile');
        
        $this->view->user = $auth['profile'];
    }

    public function applicationAction($applicationCode, $selectedMethod = "referrer", $selectedShowType = "graph", $selectedMonth = "") {
        $auth = $this->session->get(SESSION_LOGIN);

        if (! $auth) {
            return $this->redirect("/session/login");
        }

        if ($applicationCode != "") {
            $application = ClientApplication::findFirstByApplicationCode($applicationCode);

            Tag::setTitle($application->application_name);
            $this->view->pageHeader = $application->application_name;

            if ($application) {
                $publisher = Publisher::getPublisherByApplicationId($auth['id'], $application->id);
                
                if (! $publisher) {
                    $data = array(
                        'client_id'             => $application->client_id,
                        'client_application_id' => $application->id,
                        'user_id'               => $auth['id'],
                        'publisher_name'        => $auth['full_name'],
                    );
                    
                    $publisher = new Publisher();

                    if (! $publisher->save($data)) {
                        $this->_feedback = $this->label->error('Join-Application-Failed', false);
                    }
                }

                if ($publisher) {
                    $this->view->selectedMethod = $selectedMethod;
                    $this->view->methods        = array(
                        'referrer' => $this->label->label('Referrer', false)
                    );

                    $this->view->selectedShowType = $selectedShowType;
                    $this->view->showTypes        = array(
                        'graph' => $this->label->label('Graph', false),
                        'list'  => $this->label->label('List', false)
                    );

                    $dateRange = new DateRange();
                    if ($selectedMonth == "") $selectedMonth = date('d-m-Y');
                    $monthRange = $dateRange->direct(array('Previous Month', 'This Month', 'Current Month', 'Next Month'), $selectedMonth);
                    
                    $params    = array(
                        'publisher_id' => $publisher->id,
                        'date_from'    => $monthRange['Current Month']['From'],
                        'date_to'      => $monthRange['Current Month']['To'],
                        'idSort'       => 'DESC',
                    );
                    switch ($selectedMethod) {
                        case 'referrer':
                            $modelName = "Referrer";
                            break;
                        default:
                            $modelName = "";
                            break;
                    }

                    $model = new $modelName();
                    switch ($selectedShowType) {
                        case 'graph':
                            $this->view->pageGraph = $model->showGraph($params);

                            $this->view->dates = $dateRange->getAllDayFromDayToDay($params['date_from'], $params['date_to']);
                            break;
                        default:
                            $currentPage = $this->request->getQuery("page", "int", 1);
                            $itemPerPage = $this->request->getQuery("itemPerPage", "int", $this->systemConfig['Frontend_Number_Of_Item_Per_Page']);
                            
                            $paginator = new Paginator(array(
                                "builder" => $model->showList($params),
                                "limit"   => $itemPerPage,
                                "page"    => $currentPage
                            ));

                            $this->view->pageList = $paginator->getPaginate();
                            break;
                    }


                    $this->view->monthRange  = $monthRange;
                    $this->view->feedback    = $this->_feedback;
                    $this->view->application = $application;
                    $this->view->publisher   = $publisher;
                }
            }
        } else {
            return $this->redirect("/");
        }
    }

    public function signUpAction() {
        $this->setPageTitle('Sign-Up');
        
        $auth = $this->session->get(SESSION_LOGIN);

        if (! $auth) {
            $form = new UserForm();
            $form->changeModeNew();

            try {
                if ($this->request->isPost()) {
                    $formData = $this->request->getPost();

                    if ($form->isValid($formData)) {
                        $password             = $formData['password'];
                        $formData['password'] = $this->security->hash($formData['password']);

                        $user = new User();

                        $this->db->begin();
                        if ($user->save($formData)) {
                            $this->db->commit();

                            $formData['password']    = $password;
                            $formData['remember_me'] = true;
                            if ($this->auth->check($formData)) {
                                return $this->redirect("/");
                            } else {
                                $this->_feedback = $this->label->error('Login-Failed', false);
                            }
                        } else {
                            $this->_feedback = current($user->getMessages())->getMessage();
                        }
                    }
                }
            } catch (AuthException $e) {
                $this->_feedback = $e->getMessage();
            }

            $this->view->form            = $form;
            $this->view->feedback        = $this->_feedback;
        } else {
            return $this->redirect("/");
        }
    }
}