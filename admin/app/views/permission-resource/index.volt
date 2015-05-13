{% set action = view.getControllerName() ~ '/' ~ view.getActionName() %}

{{ content() }}

<div class="row search-block">
    {{ form('class': 'form-inline', 'action': action, "id": "searchForm") }}
    <div class="col-md-6">
        <div class="well well-sm">
            {{ searchAndFilter.searchForm({
                'name': label.label('Name', false),
                'controller_name': label.label('Controller-Name', false),
                'action_name': label.label('Action-Name', false)})
            }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="well well-sm">
            <?php $permissionResourceGroup = PermissionResourceGroup::fetchFormPairs(true); ?>
            {{ searchAndFilter.filterOption(label.label('Permission-Resource-Group', false), 'group_id', permissionResourceGroup) }}

            {{ button.regenerate() }}
        </div>
    </div>
    </form>
</div>


<div class="row">
    <div class="col-md-6">
        <h2>{{ pageHeader }}</h2>
    </div>
    <div class="col-md-6">
        {{ pagination.direct(page) }}
        {{ pagination.itemPerPage(page) }}
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ label.label('Name') }}</th>
                    <th>{{ label.label('Group') }}</th>
                    <th>{{ label.label('Controller-Name') }}</th>
                    <th>{{ label.label('Action-Name') }}</th>
                </tr>
            </thead>
            <tbody>
                {% if page.items is defined and page.total_items > 0 %}
                    {% for item in page.items %}
                        {% include "permission-resource/_loops/permission-resource.volt" %}
                    {% endfor %}
                {% else %}
                    <tr>
                        <td colspan="6">{{ label.label('NoResult') }}</td>
                    </tr>
                {% endif %}
            </tbody>
        </table>

        <div class="pagination-footer">
            {{ pagination.direct(page) }}
            {{ pagination.itemPerPage(page) }}
        </div>
    </div>
</div>

{% include "permission-resource/_blocks/script.volt" %}