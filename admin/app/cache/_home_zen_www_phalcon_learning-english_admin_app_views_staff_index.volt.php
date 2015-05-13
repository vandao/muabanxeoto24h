<?php $action = $this->view->getControllerName() . '/' . $this->view->getActionName(); ?>

<?php echo $this->getContent(); ?>


<div class="row search-block">
    <?php echo $this->tag->form(array('class' => 'form-inline', 'action' => $action, 'id' => 'searchForm')); ?>
        <div class="col-md-6">
            <div class="well well-sm">
                <?php echo $this->searchAndFilter->searchForm(array('email' => $this->label->label('Email', false))); ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="well well-sm">
                <?php $staffGroups = StaffGroup::fetchFormPairs(true); ?>
                <?php echo $this->searchAndFilter->filterOption($this->label->label('Staff-Group', false), 'staff_group_id', $staffGroups); ?>

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
                    <th><?php echo $this->label->label('Staff-Group'); ?></th>
                    <th><?php echo $this->label->label('Full-Name'); ?></th>
                    <th><?php echo $this->label->label('Email'); ?></th>
                    <th width="150px"><?php echo $this->label->label('Date'); ?></th>
                    <th width="50px"><?php echo $this->label->label('Action'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($page->items) && $page->total_items > 0) { ?>
                    <?php foreach ($page->items as $item) { ?>
                        
<tr>
    <td><?php echo $item->id; ?></td>
    <td><?php echo $item->staff_group; ?></td>
    <td><?php echo $item->full_name; ?></td>
    <td><?php echo $item->email; ?></td>
    <td><?php echo $item->date_created; ?></td>
    <td>
        <div class="btn-group">
            <?php echo $this->button->editRow($item->id); ?>
            <?php if ($item->is_custom_permission) { ?>
                <?php echo $this->tag->linkTo(array('permission/manage/staff/' . $item->id, '<i class="fa fa-cog"></i>', 'class' => 'btn btn-danger btn-xs', 'title' => $this->label->button('Custom-Permission', false))); ?>
            <?php } else { ?>
                <?php echo $this->tag->linkTo(array('permission/manage/staff/' . $item->id, '<i class="fa fa-cog"></i>', 'class' => 'btn btn-default btn-xs')); ?>
            <?php } ?>
        </div>
    </td>
</tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="6"><?php echo $this->label->label('No-Result'); ?></td>
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
