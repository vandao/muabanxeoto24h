<?php

use Phalcon\Mvc\User\Component;
use Phalcon\Acl\Adapter\Memory as AclMemory;
use Phalcon\Acl\Role as AclRole;
use Phalcon\Acl\Resource as AclResource;


class Acl extends Component
{

    /**
     * The ACL Object
     *
     * @var \Phalcon\Acl\Adapter\Memory
     */
    private $_acl;

    /**
     * Default role
     *
     * @var string
     */
    public $defaultRole = 'user';

    /**
     * Permission to edit label
     *
     * @var bool
     */
    public $isAllowEditLabel = false;

    /**
     * Checks if the current profile is allowed to access a resource
     *
     * @param string $controllerName
     * @param string $actionName
     * @return boolean
     */
    public function isAllowed($controllerName, $actionName)
    {
        try {
            return $this->rebuild()->isAllowed($this->defaultRole, $controllerName, $actionName);
        } catch (Exception $e) {
            echo $e->getMessage();exit;
        }
    }

    /**
     * Get permission by staff id
     * @param int $staffId
     * @return array
     */
    public function getPermissions()
    {
        $identity = $this->session->get(SESSION_ADMIN);
        $staffId  = $identity['id'];

        $permissions = array();
        if ($staffId == $this->config->permission->superAdminId) {
            foreach (PermissionResource::find() as $resource) {
                $permissions[$resource->controller_name][$resource->action_name] = $resource->action_name;
            }
        } else {
            foreach (PermissionStaff::find("staff_id = '$staffId'") as $permission) {
                if ($permission->is_allow) {
                    $permissions[$permission->controller_name][$permission->action_name] = $permission->action_name;
                }
            }
        }

        return $permissions;
    }

    /**
     * Set permisisons
     * @param  array $permissions
     */
    public function setPermissions()
    {
        try {
            foreach ($this->getDefaultPermissions() as $controllerName => $actions) {
                if ($controllerName == "system-label" && in_array("rename", $actions)) {
                    $this->isAllowEditLabel = true;
                }

                $this->_acl->allow($this->defaultRole, $controllerName, $actions);
            }

            foreach ($this->getPermissions() as $controllerName => $actions) {
                if ($controllerName == "system-label" && in_array("rename", $actions)) {
                    $this->isAllowEditLabel = true;
                }

                $this->_acl->allow($this->defaultRole, $controllerName, $actions);
            }

        } catch (Exception $e) {
            echo $e->getMessage();exit;
        }
    }

    /**
     * Set resources
     *
     * @return array
     */
    public function setResources()
    {   
        foreach (PermissionResource::find() as $resource) {
            $this->_acl->addResource(new AclResource($resource->controller_name), $resource->action_name);
        }

        foreach ($this->getDefaultPermissions() as $controllerName => $actions) {
            $this->_acl->addResource(new AclResource($controllerName), $actions);
        }
    }

    public function getDefaultPermissions() {
        $permissions = array();

        foreach ($this->config->permission->defaultAllow as $controllerName => $actions) {
            foreach ($actions as $actionName) {
                $permissions[$controllerName][$actionName] = $actionName;
            }
        }

        return $permissions;
    }

    /**
     * Rebuilds the access list into a file
     *
     * @return \Phalcon\Acl\Adapter\Memory
     */
    public function rebuild()
    {
        $this->_acl = new AclMemory();

        $this->_acl->setDefaultAction(\Phalcon\Acl::DENY);

        $this->_acl->addRole(new AclRole($this->defaultRole));

        $this->setResources();

        $this->setPermissions();

        return $this->_acl;
    }
}
