{% set action = view.getControllerName() ~ '/' ~ view.getActionName() %}

{{ content() }}


<div class="row search-block">
    <div class="col-md-12">
        {{ form('class': 'form-inline well well-sm', 'action': action) }}
            {{ searchAndFilter.searchForm({
                'template_subject': label.label('Subject', false),
                'template_body': label.label('Body', false),
                'template_key': label.label('Key', false)
            }) }}

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
                    <th>{{ label.label('Group') }}</th>
                    <th>{{ label.label('Category') }}</th>
                    <th>{{ label.label('Key') }}</th>
                    <th>{{ label.label('Subject') }}</th>
                    <th>{{ label.label('Status') }}</th>
                    <th width="65px">{{ label.label('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                {% if page.items is defined and page.total_items > 0 %}
                    {% for item in page.items %}
                        {% include "template/_loops/template.volt" %}
                    {% endfor %}
                {% else %}
                    <tr>
                        <td colspan="7">{{ label.label('NoResult') }}</td>
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

{% include "template/_blocks/script.volt" %}