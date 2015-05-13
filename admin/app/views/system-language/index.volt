{% set action = view.getControllerName() ~ '/' ~ view.getActionName() %}

{{ content() }}


<div class="row search-block">
    <div class="col-md-12">
        {{ form('class': 'form-inline well well-sm', 'action': action) }}
            {{ searchAndFilter.searchForm({
                'language_name': label.label('Name', false),
                'language_code': label.label('Code', false)})
            }}

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
                    <th>{{ label.label('Name') }}</th>
                    <th>{{ label.label('Code') }}</th>
                    <th>{{ label.label('Status') }}</th>
                    <th width="50px">{{ label.label('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                {% if page.items is defined and page.total_items > 0 %}
                    {% for item in page.items %}
                        {% include "system-language/_loops/system-language.volt" %}
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
