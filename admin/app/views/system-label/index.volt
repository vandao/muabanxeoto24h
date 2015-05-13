<?php
    $action = $this->view->getControllerName() . '/' . $this->view->getActionName();
    $languages = SystemLanguage::fetchPair("id", "language_name", false);
?>

{{ content() }}

<div class="row search-block">
    {{ form('class': 'form-inline ', 'action': action, "id": "searchForm") }}
    <div class="col-md-6 ">
        <div class="well well-sm">
            {{ searchAndFilter.searchForm({
                'label_value': label.label('Value', false), 
                'label_key': label.label('Key', false)}) }}
        </div>
    </div>
    <div class="col-md-6">
        <div class="well well-sm">        
            <?php $sections = array_merge(array("" => "All"), SystemLabel::getSections()); ?>
            {{ searchAndFilter.filterOption(label.label('Section', false), 'section', sections) }}            
            {{ searchAndFilter.filterOption(label.normal('Status', false), 'is_approved',
                                            {'':'All',
                                             '2': label.label('Approved', false),
                                             '1': label.label('Inapproved', false)} ) }}
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
                    <th>{{ label.label('Section') }}</th>                    
                    <th>{{ label.label('Key') }}</th>
                    <th>{{ label.label('Value') }}</th>
                    <th>{{ label.label('Status') }}</th>
                    <th width="70px">{{ label.label('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                {% if page.items is defined and page.total_items > 0 %}
                    {% for item in page.items %}
                        {% include "system-label/_loops/system-label.volt" %}
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