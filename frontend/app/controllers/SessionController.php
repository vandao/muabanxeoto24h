<?php
class SessionController extends ControllerBase
{   
    public function loginAction($provider) {        
        $redirectUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/session/login/' . $provider;

        if (APPLICATION_ENV == "development") {
            switch ($provider) {
                case OPENID_PROVIDER_FACEBOOK:
                    $providerLibrary = new Facebook(
                        $this->systemConfig['Development_Facebook_App_Id'],
                        $this->systemConfig['Development_Facebook_Secret'],
                        $this->systemConfig['Development_Facebook_Scope'],
                        $redirectUrl
                    );
                    break;
                case OPENID_PROVIDER_GOOGLE:
                    $providerLibrary = new Google(
                        $this->systemConfig['Google_Client_Id'],
                        $this->systemConfig['Google_Client_Secret'],
                        array(),
                        $redirectUrl
                    );
                    break;
                
                default:
                    # code...
                    break;
            }
        } else {
            switch ($provider) {
                case OPENID_PROVIDER_FACEBOOK:
                    $providerLibrary = new Facebook(
                        $this->systemConfig['Production_Facebook_App_Id'],
                        $this->systemConfig['Production_Facebook_Secret'],
                        $this->systemConfig['Production_Facebook_Scope'],
                        $redirectUrl
                    );
                    break;
                case OPENID_PROVIDER_GOOGLE:
                    $providerLibrary = new Google(
                        $this->systemConfig['Google_Client_Id'],
                        $this->systemConfig['Google_Client_Secret'],
                        array(),
                        $redirectUrl
                    );
                    break;
                
                default:
                    # code...
                    break;
            }
        }

        $authenticate = $providerLibrary->authenticate();

        if ($authenticate['status'] == 'success') {
            $response = $providerLibrary->getUser();
            
            if ($response['status'] == 'success') {
                $user             = $response['data'];
                $user['provider'] = $provider;

                $user = User::saveUser($user);

                $this->auth->registerIdentity($user, $provider);
                $this->auth->createRememberEnviroment($user);
                

                if ($backUrl = $this->session->get('back')) {
                    return $this->redirect($backUrl);
                }

                return $this->redirect("/");
            } else {
                die($response['message']);
            }
        } else {
            if (isset($authenticate['data']['login_url'])) {
                return $this->redirect($authenticate['data']['login_url']);
            } else {
                die($authenticate['message']);
            }
        }
    }

    public function logoutAction(){
        $this->auth->remove();
        $this->session->remove('back');
        return $this->redirect('index/index');
    }

    public function signInAction(){
        $this->setPageTitle('Login');
        $this->view->pick("layouts/login");
    }

}

