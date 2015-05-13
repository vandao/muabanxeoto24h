{% set action = view.getControllerName() ~ '/' ~ view.getActionName() %}

{{ content() }}


<div class="row search-block">
    <div class="col-md-12">
        {{ form('class': 'form-inline well well-sm', 'action': action) }}
            {{ searchAndFilter.searchForm({
                'to': label.label('To', false),
                'from': label.label('From', false),
                'subject': label.label('Subject', false) }) }}
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
                    <th>{{ label.label('From') }}</th>
                    <th>{{ label.label('To') }}</th>
                    <th>{{ label.label('Subject') }}</th>
                    <th>{{ label.label('Sent-Result') }}</th>
                    <th>{{ label.label('Status') }}</th>
                    <th width="150px">{{ label.label('Date') }}</th>
                    <th width="65px">{{ label.label('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                {% if page.items is defined and page.total_items > 0 %}
                    {% for item in page.items %}
                        {% include "email-queue/_loops/email-queue.volt" %}
                    {% endfor %}
                {% else %}
                    <tr>
                        <td colspan="8">{{ label.label('NoResult') }}</td>
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

{% include "email-queue/_blocks/script.volt" %}