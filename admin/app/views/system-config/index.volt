<?php $action = $this->view->getControllerName() . '/' . $this->view->getActionName(); ?>

{{ content() }}


<div class="row search-block">
    <div class="col-md-12">
        {{ form('class': 'form-inline well well-sm', 'action': action) }}
            {{ searchAndFilter.searchForm({'value': label.label('Value', false), 'key': label.label('Key', false)}) }}

            {{ button.newRow() }}
        </form>
    </div>
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
                    <th>{{ label.label('Key') }}</th>
                    <th>{{ label.label('Value') }}</th>
                    <th width="50px">Action</th>
                </tr>
            </thead>
            <tbody>
                {% if page.items is defined and page.total_items > 0 %}
                    {% for item in page.items %}
                        {% include "system-config/_loops/system-config.volt" %}
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
