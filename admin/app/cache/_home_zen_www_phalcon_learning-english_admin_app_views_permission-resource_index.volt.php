<?php $action = $this->view->getControllerName() . '/' . $this->view->getActionName(); ?>

<?php echo $this->getContent(); ?>

<div class="row search-block">
    <?php echo $this->tag->form(array('class' => 'form-inline', 'action' => $action, 'id' => 'searchForm')); ?>
    <div class="col-md-6">
        <div class="well well-sm">
            <?php echo $this->searchAndFilter->searchForm(array('name' => $this->label->label('Name', false), 'controller_name' => $this->label->label('Controller-Name', false), 'action_name' => $this->label->label('Action-Name', false))); ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="well well-sm">
            <?php $permissionResourceGroup = PermissionResourceGroup::fetchFormPairs(true); ?>
            <?php echo $this->searchAndFilter->filterOption($this->label->label('Permission-Resource-Group', false), 'group_id', $permissionResourceGroup); ?>

            <?php echo $this->button->regenerate(); ?>
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
                    <th><?php echo $this->label->label('Name'); ?></th>
                    <th><?php echo $this->label->label('Group'); ?></th>
                    <th><?php echo $this->label->label('Controller-Name'); ?></th>
                    <th><?php echo $this->label->label('Action-Name'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($page->items) && $page->total_items > 0) { ?>
                    <?php foreach ($page->items as $item) { ?>
                        
                        <tr id="<?php echo $item->id; ?>">
                            <td><?php echo $item->id; ?></td>
                            <td>
                            	<a href="#" class="resource-name"><?php echo $item->name; ?></a>
                            </td>
                            <td><?php echo $item->PermissionResourceGroup->name; ?></td>
                            <td><?php echo $item->controller_name; ?></td>
                            <td><?php echo $item->action_name; ?></td>
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


<script>
    $(document).ready(function() {
        $('.resource-name').editable({
            url: "/permission-resource/ajaxEditName",
            type: 'text',
            pk: function(){
                var id = $(this).parent().parent().attr('id');

                return id;
            },
            success: function(response, newValue) {
            },
        });
    });
</script>