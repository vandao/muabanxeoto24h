<?php

class Pagination extends \Phalcon\Mvc\User\Component {

    /**
     * Generate html of paginator 
     * 
     * @param PaginatorObject $page
     */
    public function direct($page, $paramUrl = "") {
        $itemPerPage = $this->request->getQuery("itemPerPage", "int", $this->systemConfig['Backend_Number_Of_Item_Per_Page']);
        $baseUrl = $this->view->getControllerName() . '/' . $this->view->getActionName() . $paramUrl . '?itemPerPage='.$itemPerPage;
        

        /* calculate range of pages that will be displayed
         * each time will display a group with maximum 5 pages
         */
        $next = $page->current + 1;
        if ($next > $page->total_pages) $next = $page->total_pages;

        // generate html of paginator include Previous, range of pages, Next
        echo '<ul class="pagination pull-right">';
        
        // First page link
        if (isset($page->first) && $page->first != $page->current) {
            $url = Phalcon\Tag::linkTo($baseUrl . "&page=" . $page->first, '<i class="fa fa-angle-double-left"></i>');
            echo '<li>' . $url . '</li>';
        } else {
            echo '<li class="disabled"><a href="javascript:"><i class="fa fa-angle-double-left"></i></a></li>';
        }

        // Previous page link
        if (isset($page->before) && $page->before < $page->current) {
            $url = Phalcon\Tag::linkTo($baseUrl . "&page=" . $page->before, '<i class="fa fa-angle-left"></i>');
            echo '<li>' . $url . '</li>';
        } else {
            echo '<li class="disabled"><a href="javascript:"><i class="fa fa-angle-left"></i></a></li>';
        }

        /* calculate range of pages that will be displayed
         * each time will display a group with maximum 5 pages
         */
        $start = ($page->current < 5) ? 1 : $page->current - 2;
        $end = 4 + $start;
        $end = ($page->total_pages < $end) ? $page->total_pages : $end;
        $diff = $start - $end + 4;
        $start -= ($start - $diff > 0) ? $diff : 0;

        for ($i = $start; $i <= $end; $i++) {
            if ($i == $page->current) {
                echo '<li class="active"><a href="javascript:">' . $i . '</a></li>';
            } else {
                echo '<li>' . \Phalcon\Tag::linkTo($baseUrl . "&page=" . $i, $i) . '</li>';
            }
        }

        // Next page link
        if ($next > $page->current) {
            $url = Phalcon\Tag::linkTo($baseUrl . "&page=" . $next, '<i class="fa fa-angle-right"></i>');
            echo '<li>' . $url . '</li>';
        } else {
            echo '<li class="disabled"><a href="javascript:"><i class="fa fa-angle-right"></i> </a></li>';
        }

        // Last page link
        if (isset($page->last) && $page->last > $page->current) {
            $url = Phalcon\Tag::linkTo($baseUrl . "&page=" . $page->last, '<i class="fa fa-angle-double-right"></i>');
            echo '<li>' . $url . '</li>';
        } else {
            echo '<li class="disabled"><a href="javascript:"><i class="fa fa-angle-double-right"></i></a></li>';
        }

        echo '<li class="disabled"><a href="javascript:">' . $page->current . '/' . $page->total_pages . ' ('.$page->total_items .')</a></li>';        
        echo '</ul> ';
    }

    /**
     * Show list of number of items display on page
     * @param  $page
     */
    public function itemPerPage($page) {
        $numberItems = array();
        $start  = $this->systemConfig['Backend_Start_Number_Of_Item_Per_Page'];
        $end    = $this->systemConfig['Backend_End_Number_Of_Item_Per_Page'];
        $step   = $this->systemConfig['Backend_Step_Number_Of_Item_Per_Page'];

        for ($i = $start; $i <= $end ; $i+= $step) { 
            $numberItems[$i] = $i;
        }

        $request     = new Phalcon\Http\Request();
        $itemPerPage = $request->getQuery("itemPerPage", null, $this->systemConfig['Backend_Number_Of_Item_Per_Page']);

        echo '<form action="" method="get" class="form-inline pagination pull-right" id="show-items" >';
        echo '   <div class="form-group" style="margin-right: 10px">';        
        echo '       <label>' .$this->label->label('Show-Items', false) . '&nbsp; </label>';
        echo         $this->tag->selectStatic(array("itemPerPage", $numberItems, "onchange" => "$('#show-items').submit()", "class" => "form-control", "value" => $itemPerPage));
        echo '       <input type="hidden" id="page" name="page" value="' . $page->current . '" />';
        echo '   </div>';
        echo '</form>';
    }
}