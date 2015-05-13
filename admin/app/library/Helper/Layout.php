<?php

/**
 * Layout
 *
 * Helps to build UI elements for the application
 */
class Layout extends Phalcon\Mvc\User\Component
{
    private $_headerMenu = array(
        'navbar-left' => array(
            'staff' => array(
                'caption'  => 'Staff',
                'action'   => '',
                'dropdown' => array(
                    'staff' => array(
                        'caption'    => 'Staff',
                        'controller' => 'staff',
                        'action'     => 'index/clear'
                    ),
                    'staff-group' => array(
                        'caption'    => 'Staff-Group',
                        'controller' => 'staff-group',
                        'action'     => 'index/clear'
                    ),
                )
            ),
            'system' => array(
                'caption'  => 'System',
                'action'   => '',
                'dropdown' => array(
                    'system-config' => array(
                        'caption'    => 'Config',
                        'controller' => 'system-config',
                        'action'     => 'index/clear'
                    ),
                    'system-label' => array(
                        'caption'    => 'Label',
                        'controller' => 'system-label',
                        'action'     => 'index/clear'
                    ),
                    'system-language' => array(
                        'caption'    => 'Language',
                        'controller' => 'system-language',
                        'action'     => 'index/clear'
                    ),
                    'static-content' => array(
                        'caption'    => 'Static-Content',
                        'controller' => 'static-content',
                        'action'     => 'index/clear'
                    ),
                    'email-queue' => array(
                        'caption'    => 'Email-Queue',
                        'controller' => 'email-queue',
                        'action'     => 'index/clear'
                    ),
                    'test-encrypt' => array(
                        'caption'    => 'Test Encrypt',
                        'controller' => 'session',
                        'action'     => 'testEncrypt'
                    ),
                    'divider',
                    'permission-resource' => array(
                        'caption'    => 'Permission-Resrouce',
                        'controller' => 'permission-resource',
                        'action'     => 'index/clear'
                    ),
                    'permission-resource-group' => array(
                        'caption'    => 'Permission-Resrouce-Group',
                        'controller' => 'permission-resource-group',
                        'action'     => 'index/clear'
                    ),
                )
            ),
            'template' => array(
                'caption'  => 'Template',
                'action'   => '',
                'dropdown' => array(
                    'tempalte' => array(
                        'caption'    => 'Template',
                        'controller' => 'template',
                        'action'     => 'index/clear'
                    ),
                    'template-category' => array(
                        'caption'    => 'Template-Category',
                        'controller' => 'template-category',
                        'action'     => 'index/clear'
                    ),
                    'tempalte-group' => array(
                        'caption'    => 'Template-Group',
                        'controller' => 'template-group',
                        'action'     => 'index/clear'
                    ),
                )
            )
        ),
        'navbar-right' => array(
        )
    );

    /**
     * Builds header menu with left and right items
     *
     * @return string
     */
    public function getMenu()
    {
        $auth = $this->session->get(SESSION_ADMIN);
        if ($auth) {
            $this->_headerMenu['navbar-right']['session'] = array(
                'caption'  => $this->label->label('Hi', false) . " " . $auth['full_name'],
                'action'   => 'index',
                'dropdown' => array(
                    'change-profile' => array(
                        'caption'    => 'Profile',
                        'controller' => 'staff',
                        'action'     => 'editProfile/' . $auth['id']
                    ),
                    // 'change-password' => array(
                    //     'caption'    => 'Change-Password',
                    //     'controller' => 'staff',
                    //     'action'     => 'change-password'
                    // ),
                    'divider'        => 'divider',
                    'logout' => array(
                        'caption'    => 'Logout',
                        'controller' => 'session',
                        'action'     => 'logout'
                    )
                )
            );
        } else {
            $this->_headerMenu['navbar-right']['session'] = array(
                'caption'  => 'Log-In',
                'action'   => 'login',
                'dropdown' => array(
                )
            );
        }
        
        $this->_setPermissions();
        $this->_convertCaption();
        
        

        $controllerName = $this->view->getControllerName();
        $actionName     = $this->view->getActionName();
        // echo "<pre>";
        // print_r($this->_headerMenu); die();
        foreach ($this->_headerMenu as $position => $menu) {
            echo '<ul class="nav navbar-nav ' . $position . '">';            
            
            foreach ($menu as $controller => $option) {
                $controllerActive = ($controllerName == $controller) ? 'active' : 'pass-to-action';

                if (count($option['dropdown']) > 0) {
                    $controllerClass = 'dropdown ' . $controllerActive;

                    $menu  = '<li class="' . $controllerClass . '">';
                    $menu .= Phalcon\Tag::linkTo(array('#', $option['caption'] . ' <b class="caret"></b>', 'class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'));
                    $menu .= '<ul class="dropdown-menu">';
                    foreach ($option['dropdown'] as $dropdown) {
                        if ($dropdown == 'divider') {
                            $menu .= '<li class="divider"></li>';
                        } else {
                            $menuAction   = str_replace('/clear', '', $dropdown['action']);
                            $actionActive = '';
                            if ($controllerName == $dropdown['controller'] && $actionName == $menuAction) {
                                $actionActive     = 'active';
                                $controllerActive = 'active';
                            }

                            $menu .= '<li class="'. $actionActive .'">';
                            if ($dropdown['controller']){
                                $menu .= Phalcon\Tag::linkTo($dropdown['controller'].'/'.$dropdown['action'], $dropdown['caption']);    
                            }else{
                                $menu .= '<a href="' . $dropdown['action'] .'" target="_blank">' . $dropdown['caption'] . '</a>';
                            }
                            
                            $menu .= '</li>';
                        }
                    }
                    $menu .= '</ul>';
                    $menu .= '</li>';
                } else {
                    if ($option['action'] != '') {
                        $menu  = '<li class="' . $controllerClass . '">';
                        $menu .= Phalcon\Tag::linkTo($controller.'/'.$option['action'], $option['caption']);
                        $menu .= '</li>';
                    }
                }
                
                if ($controllerActive == 'pass-to-action') $controllerActive = '';
                $menu = str_replace('pass-to-action', $controllerActive, $menu);
                echo $menu;
            }
            echo '</ul>';
        }
    }

    private function _setPermissions() {
        $permissions = $this->acl->getPermissions();
        // unset($permissions['system-label']['index']);
        // var_dump($permissions);exit;

        foreach ($this->_headerMenu as $position => $menu) {
            foreach ($menu as $controller => $option) {
                if (count($option['dropdown']) > 0 ) {
                    foreach ($option['dropdown'] as $dropdownKey => $dropdown) {
                        if ($dropdown != 'divider') {
                            $action     = explode("/", $dropdown['action']);
                            $action     = current($action);

                            if (! isset($permissions[$dropdown['controller']][$action])) {
                                // echo $controller . $action;exit;
                                unset($this->_headerMenu[$position][$controller]['dropdown'][$dropdownKey]);
                                unset($option['dropdown'][$dropdownKey]);
                            }
                        } else {
                            unset($option['dropdown'][$dropdownKey]);
                        }
                    }

                    if (count($option['dropdown']) == 0) {
                        if ($option['action'] == "") {
                            unset($this->_headerMenu[$position][$controller]);
                        }
                    }
                } else {                    
                    $action = current(explode("/", $option['action']));

                    if (! isset($permissions[$controller][$action]) && $controller != 'website') {
                        unset($this->_headerMenu[$position][$controller]);
                    }
                }
            }
        }

        // var_dump($this->_headerMenu);exit;
    }

    private function _convertCaption() {
        foreach ($this->_headerMenu as $positionKey => $positions) {
            foreach ($positions as $mainKey => $mainMenu) {
                if ($mainKey != 'session') {
                    $this->_headerMenu[$positionKey][$mainKey]['caption'] = $this->label->menu($mainMenu['caption'], false);
                }

                foreach ($mainMenu['dropdown'] as $childKey => $child) {
                    if (isset($child['caption']) > 0) {
                        $this->_headerMenu[$positionKey][$mainKey]['dropdown'][$childKey]['caption'] = $this->label->menu($child['caption'], false);
                    }
                }
            }
        }
    }
}