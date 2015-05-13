<?php

use Phalcon\Mvc\User\Plugin;

/**
 * Vokuro\Auth\Auth
 * Manages Authentication/Identity Management in Vokuro
 */
class Auth extends Plugin
{
    /**
     * Checks the user credentials
     *
     * @param array $credentials
     * @return boolan
     */
    public function check($credentials)
    {
        // Check if the user exist
        $user = User::findFirstByEmail($credentials['email']);
        if ($user == false) return false;

        // Check the password
        if (! $this->security->checkHash($credentials['password'], $user->password)) return false;

        // Check if the remember me was selected
        if (isset($credentials['remember_me'])) {
            $this->createRememberEnviroment($user);
        }

        // Register identity
        $this->registerIdentity($user);
        return true;
    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param Vokuro\Models\Users $user
     */
    public function createRememberEnviroment(User $user)
    {
        $token = $this->_getToken($user);

        $expire = time() + 86400 * 30;
        $this->cookies->set('RMU', $user->id, $expire);
        $this->cookies->set('RMT', $token, $expire);
    }

    /**
     * Check if the session has a remember me cookie
     *
     * @return boolean
     */
    public function hasRememberMe()
    {
        return $this->cookies->has('RMU');
    }

    /**
     * Logs on using the information in the coookies
     *
     * @return Phalcon\Http\Response
     */
    public function loginWithRememberMe()
    {
        $userId      = $this->cookies->get('RMU')->getValue();
        $cookieToken = $this->cookies->get('RMT')->getValue();

        $user = User::findFirstById($userId);
        if ($user) {
            $token = $this->_getToken($user);

            if ($cookieToken == $token) {
                $this->createRememberEnviroment($user);
                
                // Register identity
                $this->registerIdentity($user);

                return $this->response->redirect('/');
            } else {
                $this->remove();
            }
        }

        //$this->cookies->get('RMU')->delete();
        //$this->cookies->get('RMT')->delete();

        return $this->response->redirect('session/login');
    }

    /**
     * Returns the current identity
     *
     * @return array
     */
    public function getIdentity()
    {
        return $this->session->get(SESSION_LOGIN);
    }

    /**
     * Returns the current identity
     *
     * @return string
     */
    public function getName()
    {
        $identity = $this->session->get(SESSION_LOGIN);
        return $identity['name'];
    }

    /**
     * Removes the user identity information from session
     */
    public function remove()
    {
        if ($this->cookies->has('RMU')) {
            $this->cookies->get('RMU')->delete();
        }
        if ($this->cookies->has('RMT')) {
            $this->cookies->get('RMT')->delete();
        }

        $this->session->remove(SESSION_LOGIN);
    }

    // Register identity
    public function registerIdentity($user)
    {
        $this->session->set(SESSION_LOGIN, array(
            'user_id'    => $user->id,
            'full_name'  => $user->getFullname()
        ));
    }

    private function _getToken($user)
    {
        $userAgent = $this->request->getUserAgent();

        return md5($user->email . $user->password . $userAgent);
    }
}
