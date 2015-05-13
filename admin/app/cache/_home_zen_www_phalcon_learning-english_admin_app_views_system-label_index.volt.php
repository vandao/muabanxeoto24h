<?php
    $action = $this->view->getControllerName() . '/' . $this->view->getActionName();
    $languages = SystemLanguage::fetchPair("id", "language_name", false);
?>

<?php echo $this->getContent(); ?>

<div class="row search-block">
    <?php echo $this->tag->form(array('class' => 'form-inline ', 'action' => $action, 'id' => 'searchForm')); ?>
    <div class="col-md-6 ">
        <div class="well well-sm">
            <?php echo $this->searchAndFilter->searchForm(array('label_value' => $this->label->label('Value', false), 'label_key' => $this->label->label('Key', false))); ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="well well-sm">        
            <?php $sections = array_merge(array("" => "All"), SystemLabel::getSections()); ?>
            <?php echo $this->searchAndFilter->filterOption($this->label->label('Section', false), 'section', $sections); ?>            
            <?php echo $this->searchAndFilter->filterOption($this->label->normal('Status', false), 'is_approved', array('' => 'All', '2' => $this->label->label('Approved', false), '1' => $this->label->label('Inapproved', false))); ?>
            <?php echo $this->button->newRow(); ?>
        </div>
    </div>
</form>
</div>


<div class="row">
    <div class="col-md-6">
        <h2><?php echo $pageHeader; ?></h2>
    </div>
    <div class="col-md-6">
        <?php echo $this->pagination->direct($page); ?>
        <?php echo $this->pagination->itemPerPage($page); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?php echo $this->label->label('Section'); ?></th>                    
                    <th><?php echo $this->label->label('Key'); ?></th>
                    <th><?php echo $this->label->label('Value'); ?></th>
                    <th><?php echo $this->label->label('Status'); ?></th>
                    <th width="70px"><?php echo $this->label->label('Action'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($page->items) && $page->total_items > 0) { ?>
                    <?php foreach ($page->items as $item) { ?>
                        <tr id="<?php echo $item->id; ?>">
    <td><?php echo $item->id; ?></td>
    <td><?php echo $item->section; ?></td>    
    <td><?php echo $item->label_key; ?></td>
    <td><?php echo $this->label->normal($item->label_key, true, array(), $item->language_id); ?></td>
    <td>
        <?php echo $this->button->approved($item->system_label_language_id, $item->is_approved); ?>
    </td>
    <td>
        <div class="btn-group">
            <?php echo $this->button->editRow($item->id); ?>
            <?php echo $this->button->deleteRow($item->id, 0); ?>
        </div>
    </td>
</tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="7"><?php echo $this->label->label('NoResult'); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="pagination-footer">
            <?php echo $this->pagination->direct($page); ?>
            <?php echo $this->pagination->itemPerPage($page); ?>
        </div>
    </div>
</div>