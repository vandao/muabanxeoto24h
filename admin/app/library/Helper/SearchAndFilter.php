<?php

class SearchAndFilter extends Phalcon\Mvc\User\Component
{   
    public function searchForm($options, $action = '') {
        // $postParam = $this->session->get('postParam');
        $postParam = $this->request->getPost();
        if (!$action){
            $action    = '/' . $this->view->getControllerName() . '/' . $this->view->getActionName();    
        }        

        $selectOption = array();
        foreach ($options as $key => $value) {
            if (isset($postParam['typeSearch']) && $postParam['typeSearch'] == $key) {
                $selectOption[] = '<option value="' . $key . '" selected="selected">' . $value . '</option>';
            } else {
                $selectOption[] = '<option value="' . $key . '">' . $value . '</option>';
            }
        }

        if (isset($postParam['keywordSearch'])) {
            $keywordSearch = $postParam['keywordSearch'];
        } else {
            $keywordSearch = '';
        }
        $sort = (isset($postParam['sort'])) ? $postParam['sort'] : '';
        $sortDir = (isset($postParam['sort_dir'])) ? $postParam['sort_dir'] : '';

        $html = '
        <div class="form-group">
            <input type="text" name="keywordSearch" class="form-control" value="'. $keywordSearch .'" placeholder="Keyword">
        </div>
        <div class="form-group">
            <select name="typeSearch" class="form-control">
                ' . implode($selectOption, "\n") . '
            </select>
        </div>
        <button class="btn btn-primary" class="btn btn-primary" title="Search" type="submit"><i class="fa fa-search"></i></button>
        <a class="btn btn-default" title="Clear" href="' . $action . '/clear">
            <i class="fa fa-refresh"></i>
        </a>
        <input type="hidden" id="sort" name="sort" value="' . $sort .'">
        <input type="hidden" id="sort_dir" name="sort_dir" value="'. $sortDir .'">';

        return $html;
    }

    public function filterOption($name, $fieldName, $options) {
        // $postParam = $this->session->get('postParam');
        $postParam = $this->request->getPost();
        $action    = '/' . $this->view->getControllerName() . '/' . $this->view->getActionName();

        $selectOption = array();
        foreach ($options as $key => $value) {
            if (isset($postParam[$fieldName]) && $postParam[$fieldName] == $key) {
                $selectOption[] = '<option value="' . $key . '" selected="selected">' . $value . '</option>';
            } else {
                $selectOption[] = '<option value="' . $key . '">' . $value . '</option>';
            }
        }

        $html = '
            <div class="form-group">
                <label>' . $name . '</label>
                <select name="' . $fieldName . '" class="form-control" onchange="$(\'#searchForm\').submit()" id="'. $fieldName.'">
                    ' . implode($selectOption, "\n") . '
                </select>
            </div>';

        return $html;
    }
}