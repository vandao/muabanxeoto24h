<?php $action = $this->view->getControllerName() . '/' . $this->view->getActionName(); ?>

<?php echo $this->getContent(); ?>

<div class="row">
    <div class="col-md-12">
        <h2>
            <?php echo $pageHeader; ?>

            <?php if ($mode == 'staff') { ?>
                <?php echo $this->button->regenerate('regenerateStaff/' . $id); ?>
            <?php } ?>
        </h2>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <tr class="success">
                    <th><?php echo $this->label->label('Controller', false); ?></th>
                    <?php foreach ($resourceGroups as $resourceGroup) { ?>
                        <th>
                            <?php echo $resourceGroup->name; ?>
                            <input type="checkbox" class="pull-right permission-group" value="<?php echo $resourceGroup->id; ?>" />
                        </th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($resources) && $resources > 0) { ?>
                    <?php foreach ($resources as $controller => $groupResources) { ?>
                        <tr controller="<?php echo $controller; ?>">
                            <th><?php echo $controller; ?></th>

                            <?php foreach ($resourceGroups as $resourceGroup) { ?>
                                <td>
                                <?php if (isset($groupResources[$resourceGroup->id]) && $groupResources[$resourceGroup->id] > 0) { ?>
                                    <table class="table table-bordered table-condensed" resource-group="<?php echo $resourceGroup->id; ?>">
                                        <tr>
                                            <th>
                                                <input type="checkbox" class="pull-right permission-group-controller" />
                                            </th>
                                        </tr>

                                        <?php foreach ($groupResources[$resourceGroup->id] as $resource) { ?>
                                            <tr id="<?php echo $resource->id; ?>" action="<?php echo $resource->action_name; ?>">
                                                <td>
                                                    <?php if (isset($permissions[$controller][$resource->action_name]['is_custom']) && $permissions[$controller][$resource->action_name]['is_custom']) { ?>
                                                        <?php $color = 'text-danger'; ?>
                                                    <?php } else { ?>
                                                        <?php $color = ''; ?>
                                                    <?php } ?>
                                                    <a href="#" class="resource-name <?php echo $color; ?>">
                                                        <?php if ($resource->name) { ?>
                                                            <?php echo $resource->name; ?>
                                                        <?php } else { ?>
                                                            <?php echo $resource->action_name; ?>
                                                        <?php } ?>
                                                    </a>
                                                    <?php if (isset($permissions[$controller][$resource->action_name]['is_allow']) && $permissions[$controller][$resource->action_name]['is_allow']) { ?>
                                                        <input type="checkbox" class="pull-right permission-action" checked="checked" />
                                                    <?php } else { ?>
                                                        <input type="checkbox" class="pull-right permission-action" />
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                <?php } ?>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="6"><?php echo $this->label->label('NoResult'); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>


<?php echo $this->tag->hiddenField(array('staff_id')); ?>
<?php echo $this->tag->hiddenField(array('staff_group_id')); ?>

<?php echo $this->tag->hiddenField(array('user_id')); ?>
<?php echo $this->tag->hiddenField(array('user_group_id')); ?>


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

<script type="text/javascript">
    $(document).ready(function() {
        $('.permission-action').change(function() {
            var isAllow        = $(this).prop('checked'),
                controllerName = $(this).parents('tr[controller]').attr('controller'),
                actionName     = $(this).parents('tr[action]').attr('action');
            
            
            Permission.action(controllerName, actionName, isAllow);
        });

        $('.permission-group-controller').change(function() {
            var isAllow         = $(this).prop('checked'),
                controllerName  = $(this).parents('tr[controller]').attr('controller'),
                resourceGroupId = $(this).parents('table[resource-group]').attr('resource-group');
            
            Permission.groupByController(resourceGroupId, controllerName, isAllow);
        });

        $('.permission-group').change(function() {
            var isAllow         = $(this).prop('checked'),
                resourceGroupId = $(this).val();
            
            Permission.group(resourceGroupId, isAllow);
        });

        Permission.isCheckedAll();
    });

    var Permission = {
        action: function(controllerName, actionName, isAllow) {
            var permissionAction = $('tr[controller=' + controllerName + ']').find('tr[action=' + actionName + ']'),
                resourceGroupId  = permissionAction.parents('table[resource-group]').attr('resource-group')
                controllerName   = permissionAction.parents('tr[controller]').attr('controller');

            permissionAction.find('input').prop('checked', isAllow);

            Permission.isCheckedAllGroupByController(resourceGroupId, controllerName);
            Permission.isCheckedAllGroup(resourceGroupId, controllerName);

            Permission.updatePermission(controllerName, actionName, isAllow);
        },
        groupByController: function(resourceGroupId, controllerName, isAllow) {
            var controller    = $('tr[controller=' + controllerName + ']'),
                resourceGroup = controller.find('table[resource-group=' + resourceGroupId + ']');

            resourceGroup.find('tr[action]').each(function() {
                Permission.action(controllerName, $(this).attr('action'), isAllow);
            });

            resourceGroup.find('input').prop('checked', isAllow);
        },
        group: function(resourceGroupId, isAllow) {
            $('table[resource-group=' + resourceGroupId + ']').each(function() {
                var controllerName = $(this).parents('tr[controller]').attr('controller');

                Permission.groupByController(resourceGroupId, controllerName, isAllow);
            });
        },
        isCheckedAllGroupByController: function(resourceGroupId, controllerName) {
            var permissionAction = $('tr[controller=' + controllerName + ']').find('table[resource-group=' + resourceGroupId + ']').find('tr[action]');

            isCheckedAll = true;
            permissionAction.find('input').each(function() {
                if (! $(this).prop('checked')) {
                    isCheckedAll = false;
                }
            });

            $('tr[controller=' + controllerName + ']').find('table[resource-group=' + resourceGroupId + ']').find('.permission-group-controller').prop('checked', isCheckedAll);
        },
        isCheckedAllGroup: function(resourceGroupId) {
            var resourceGroup = $('table[resource-group=' + resourceGroupId + ']');

            isCheckedAll = true;
            resourceGroup.find('tr[action]').find('input').each(function() {
                if (! $(this).prop('checked')) {
                    isCheckedAll = false;
                }
            });

            $('.permission-group[value=' + resourceGroupId + ']').prop('checked', isCheckedAll);
        },
        isCheckedAll: function() {
            $('tr[action]').each(function() {
                var permissionAction = $(this),
                    resourceGroupId  = permissionAction.parents('table[resource-group]').attr('resource-group')
                    controllerName   = permissionAction.parents('tr[controller]').attr('controller');

                Permission.isCheckedAllGroupByController(resourceGroupId, controllerName);
                Permission.isCheckedAllGroup(resourceGroupId, controllerName);
            });
        },
        updatePermission: function(controllerName, actionName, isAllow) {
            var permission = {
                controller_name: controllerName,
                action_name: actionName,
                is_allow: isAllow
            };

            if ($('#staff_group_id').val() > 0) {
                permission['staff_group_id'] = $('#staff_group_id').val();
                $.post('/permission/ajaxEditPermissionStaffGroup', permission, function(json) {
                    alertMessage($.parseJSON(json));
                });
            } else {
                permission['staff_id'] = $('#staff_id').val();
                $.post('/permission/ajaxEditPermissionStaff', permission, function(json) {
                    alertMessage($.parseJSON(json));
                });
            }
        }
    }
</script>