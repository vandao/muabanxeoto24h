<?php

use Phalcon\Mvc\Model\Validator\Uniqueness;

class PermissionResource extends PermissionResourceDbTable
{
    const SECTION = 'Backend';

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
    }

    public function beforeValidationOnCreate()
    {
        $this->section = PermissionResource::SECTION;
    }
    
    public function beforeValidationOnUpdate()
    {
    }

    public function validation()
    {
        // $this->validate(new Uniqueness(array(
        //     'field'   => 'key',
        //     'message' => LabelMessage::rowExisted('Email')
        // )));

        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    /**
     * Get resources
     * @return array
     */
    static public function getResources() {
        $resources = array();

        foreach (PermissionResource::find(array("section = '" . PermissionResource::SECTION . "'", "order" => "controller_name DESC")) as $resource) {
            $resources[$resource->controller_name][$resource->group_id][] = $resource;
        }

        return $resources;
    }

    /**
     * Get resource
     * @param  string $controllerName
     * @param  string $actionName
     * @return object
     */
    static public function getResource($controllerName, $actionName) {
        return PermissionResource::findFirst("controller_name = '$controllerName' AND action_name = '$actionName'");
    }

    /**
     * Regenerate resource
     * @param  array $resources
     */
    public function regenerateResources($resources)
    {
        $permissionResourceGroup = new PermissionResourceGroup();

        /**
         * Delete not existed resource
         */
        foreach (PermissionResource::find() as $resource) {
            if (! isset($resources[$resource->controller_name][$resource->action_name])) {
                $resource->delete();
            }
        }

        /**
         * Add new resource
         */
        foreach ($resources as $controllerName => $actions) {
            foreach ($actions as $actionName) {
                $newResource = array(
                    'controller_name' => $controllerName,
                    'action_name'     => $actionName,
                    'group_id'        => $permissionResourceGroup->getIdByActionName($actionName)
                );

                $permissionResource = PermissionResource::findFirst(array(
                    "conditions" => "controller_name = ?1 AND action_name = ?2",
                    "bind"       => array(
                        1 => $newResource['controller_name'],
                        2 => $newResource['action_name']
                    )
                ));
                if (! $permissionResource) $permissionResource = new PermissionResource();
                
                $permissionResource->save($newResource);
            }
        }
    }

    /**
     * Filter and convert resource name
     * @param string $string
     * @return string
     */
    public function filterResourceName($string, $removeString)
    {
        if ($removeString == 'Controller') $string = strtolower(substr($string, 0, 1)) . substr($string, 1);

        $string = str_replace($removeString, '', $string);

        if ($removeString == 'Controller') {
            preg_match_all("/[A-Z]/", $string, $matches);

            foreach ($matches[0] as $letter) {
                $string = str_replace($letter, '-' . strtolower($letter), $string);
            }
        }

        return $string;
    }
}