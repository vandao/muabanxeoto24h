{% set action = view.getControllerName() ~ '/' ~ view.getActionName() %}

{{ content() }}


<div class="row search-block">
    {{ form('class': 'form-inline', 'action': action, "id": "searchForm") }}
    <div class="col-md-12">
        <div class="well well-sm">
            {{ searchAndFilter.searchForm({
                'static_content_title': label.label('Title', false),
                'static_content_key': label.label('Key', false)
            }) }}

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
                    <th>{{ label.label('Key') }}</th>
                    <th>{{ label.label('Title') }}</th>
                    <th>{{ label.label('Status') }}</th>
                    <th width="50px">{{ label.label('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                {% if page.items is defined and page.total_items > 0 %}
                    {% for item in page.items %}
                        {% include "static-content/_loops/static-content.volt" %}
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
