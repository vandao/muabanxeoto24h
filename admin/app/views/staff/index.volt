{% set action = view.getControllerName() ~ '/' ~ view.getActionName() %}

{{ content() }}


<div class="row search-block">
    {{ form('class': 'form-inline', 'action': action, "id": "searchForm") }}
        <div class="col-md-6">
            <div class="well well-sm">
                {{ searchAndFilter.searchForm({
                    'email': label.label('Email', false)
                }) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="well well-sm">
                <?php $staffGroups = StaffGroup::fetchFormPairs(true); ?>
                {{ searchAndFilter.filterOption(label.label('Staff-Group', false), 'staff_group_id', staffGroups) }}

                {{ button.newRow() }}
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
                    <th>{{ label.label('Staff-Group') }}</th>
                    <th>{{ label.label('Full-Name') }}</th>
                    <th>{{ label.label('Email') }}</th>
                    <th width="150px">{{ label.label('Date') }}</th>
                    <th width="50px">{{ label.label('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                {% if page.items is defined and page.total_items > 0 %}
                    {% for item in page.items %}
                        {% include "staff/_loops/staff.volt" %}
                    {% endfor %}
                {% else %}
                    <tr>
                        <td colspan="6">{{ label.label('No-Result') }}</td>
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
