<?php

use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl;
use Phalcon\Acl\Adapter\Memory as AclMemory;
use Phalcon\Acl\Role as AclRole;
use Phalcon\Acl\Resource as AclResource;

/**
 * Bootstrap
 */
class Bootstrap extends Plugin
{
    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        $controllerName = $dispatcher->getControllerName();
        $actionName     = $dispatcher->getActionName();
        if ($controllerName == 'api'){            
            return true;
        }

        if ($controllerName != 'session' && $actionName != 'login') {
            $auth = $this->session->get(SESSION_ADMIN);

            if (! $auth) {
                $dispatcher->forward(
                    array(
                        'controller' => 'session',
                        'action'     => 'login'
                    )
                );

                //Returning "false" we tell to the dispatcher to stop the current operation
                return false;
            } else {
                //Check if the Role have access to the controller (resource)
                $allowed = $this->acl->isAllowed($controllerName, $actionName);
                // $allowed = true;
                if ($allowed != Acl::ALLOW) {
                    header('Location: /permission/index');
                    exit;
                }
            }
        }
    }
}
