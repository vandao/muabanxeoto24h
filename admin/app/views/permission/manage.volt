{% set action = view.getControllerName() ~ '/' ~ view.getActionName() %}

{{ content() }}

<div class="row">
    <div class="col-md-12">
        <h2>
            {{ pageHeader }}

            {% if mode is 'staff' %}
                {{ button.regenerate('regenerateStaff/' ~ id) }}
            {% endif %}
        </h2>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <tr class="success">
                    <th>{{ label.label('Controller', false) }}</th>
                    {% for resourceGroup in resourceGroups %}
                        <th>
                            {{ resourceGroup.name }}
                            <input type="checkbox" class="pull-right permission-group" value="{{ resourceGroup.id }}" />
                        </th>
                    {% endfor %}
                </tr>
            </thead>
            <tbody>
                {% if resources is defined and resources > 0 %}
                    {% for controller, groupResources in resources %}
                        <tr controller="{{ controller }}">
                            <th>{{ controller }}</th>

                            {% for resourceGroup in resourceGroups %}
                                <td>
                                {% if groupResources[resourceGroup.id] is defined and groupResources[resourceGroup.id] > 0 %}
                                    <table class="table table-bordered table-condensed" resource-group="{{ resourceGroup.id }}">
                                        <tr>
                                            <th>
                                                <input type="checkbox" class="pull-right permission-group-controller" />
                                            </th>
                                        </tr>

                                        {% for resource in groupResources[resourceGroup.id] %}
                                            <tr id="{{ resource.id }}" action="{{ resource.action_name }}">
                                                <td>
                                                    {% if permissions[controller][resource.action_name]['is_custom'] is defined and permissions[controller][resource.action_name]['is_custom'] %}
                                                        {% set color="text-danger" %}
                                                    {% else %}
                                                        {% set color="" %}
                                                    {% endif %}
                                                    <a href="#" class="resource-name {{ color }}">
                                                        {% if resource.name %}
                                                            {{ resource.name }}
                                                        {% else %}
                                                            {{ resource.action_name }}
                                                        {% endif %}
                                                    </a>
                                                    {% if permissions[controller][resource.action_name]['is_allow'] is defined and permissions[controller][resource.action_name]['is_allow'] %}
                                                        <input type="checkbox" class="pull-right permission-action" checked="checked" />
                                                    {% else %}
                                                        <input type="checkbox" class="pull-right permission-action" />
                                                    {% endif %}
                                                </td>
                                            </tr>
                                        {% endfor %}
                                    </table>
                                {% endif %}
                                </td>
                            {% endfor %}
                        </tr>
                    {% endfor %}
                {% else %}
                    <tr>
                        <td colspan="6">{{ label.label('NoResult') }}</td>
                    </tr>
                {% endif %}
            </tbody>
        </table>
    </div>
</div>


{{ hidden_field('staff_id') }}
{{ hidden_field('staff_group_id') }}

{{ hidden_field('user_id') }}
{{ hidden_field('user_group_id') }}

{% include "permission-resource/_blocks/script.volt" %}

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