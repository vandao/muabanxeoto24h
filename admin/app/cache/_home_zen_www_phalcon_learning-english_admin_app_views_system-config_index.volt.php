<?php $action = $this->view->getControllerName() . '/' . $this->view->getActionName(); ?>

<?php echo $this->getContent(); ?>


<div class="row search-block">
    <div class="col-md-12">
        <?php echo $this->tag->form(array('class' => 'form-inline well well-sm', 'action' => $action)); ?>
            <?php echo $this->searchAndFilter->searchForm(array('value' => $this->label->label('Value', false), 'key' => $this->label->label('Key', false))); ?>

            <?php echo $this->button->newRow(); ?>
        </form>
    </div>
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
                    <th><?php echo $this->label->label('Key'); ?></th>
                    <th><?php echo $this->label->label('Value'); ?></th>
                    <th width="50px">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($page->items) && $page->total_items > 0) { ?>
                    <?php foreach ($page->items as $item) { ?>
                        
                        <tr>
                            <td><?php echo $item->id; ?></td>
                            <td><?php echo $item->key; ?></td>
                            <td><?php echo $item->value; ?></td>
                            <td>
                                <div class="btn-group">
                                    <?php echo $this->button->editRow($item->id); ?>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="6"><?php echo $this->label->label('NoResult'); ?></td>
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
