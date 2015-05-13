{% set action     = '/' ~ view.getControllerName() ~ '/' ~ view.getActionName() ~ '/' ~ application.application_code ~ '/' ~ selectedMethod %}
{% set currentUrl = action ~ '/' ~ selectedShowType %}
{% set paramUrl   = '/' ~ application.application_code ~ '/' ~ selectedMethod ~ '/' ~ selectedShowType %}

<div class="container">
    <div class="page-header">{{ pageHeader }}</div>

    {% if publisher is defined %}
        <div class="well well-sm">
            {% set referrerUrl = config.url.frontend ~ 'ca/a/' ~ publisher.publisher_code %}
            <a href="{{ referrerUrl }}">{{ referrerUrl }}</a>
        </div>


		<ul class="nav nav-tabs">
	        {% for method, methodName in methods %}
				<li {% if selectedMethod is method %} class="active" {% endif %}>
					<a href="{{ action ~ '/' ~ selectedShowType }}">{{ methodName }}</a>
				</li>
			{% endfor %}
		</ul>

		<br />

		<ul class="nav nav-pills nav-justified">
	        {% for showType, showTypeName in showTypes %}
				<li {% if selectedShowType is showType %} class="active" {% endif %}>
					<a href="{{ action ~ '/' ~ showType }}">{{ showTypeName }}</a>
				</li>
			{% endfor %}
		</ul>

        <br />


        {% if (pageList is defined) %}
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="50px" class="text-center">#</th>
                                <th>{{ label.label('Full-Name') }}</th>
                                <th width="150px" class="text-right">{{ label.label('Device-Os') }}</th>
                                <th width="150px" class="text-right">{{ label.label('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {% if pageList.items is defined and pageList.total_items > 0 %}
                                {% for item in pageList.items %}
                                    <tr>
                                        <td class="text-center">{{ loop.index }}</td>
                                        <td>{{ item.full_name }}</td>
                                        <td class="text-right">{{ item.device_os }}</td>
                                        <td class="text-right">{{ item.date_created }}</td>
                                    </tr>
                                {% endfor %}
                            {% else %}
                                <tr>
                                    <td colspan="5">{{ label.label('NoResult') }}</td>
                                </tr>
                            {% endif %}
                        </tbody>
                    </table>

                    <div class="pagination-footer">
                        {{ pagination.direct(pageList, paramUrl) }}
                        {{ pagination.itemPerPage(pageList) }}
                    </div>
                </div>
            </div>
        {% else %}
            <div class="row">
                <div class="col-md-12">
                    <script src="/js/highcharts/highcharts.js"></script>
                    <script src="/js/highcharts/modules/exporting.js"></script>

                    <div class="text-right">
                        <a href="{{ currentUrl ~ '/' ~ monthRange['Previous Month']['From'] }}" class="btn btn-default">            
                            <i class="fa fa-arrow-left"></i>
                        </a>
                        <a href="{{ currentUrl ~ '/' ~ monthRange['This Month']['From'] }}" class="btn btn-default">            
                            {{ label.label('This-Month') }}
                        </a>
                        <a href="{{ currentUrl ~ '/' ~ monthRange['Next Month']['From'] }}" class="btn btn-default">            
                            <i class="fa fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="panel panel-default">
                        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </div>


                    <script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: '{{ label.label('Referrer') }}'
        },
        subtitle: {
            //text: 'Source: WorldClimate.com'
        },
        xAxis: {
            categories: [
                {% for date in dates %}
                    '{{ date }}'{% if loop.last is false %},{% endif %}
                {% endfor %}
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: '{{ label.label('Count') }}'
            }
        },
        series: [
            {
                name: '{{ label.label('Referrer') }}',
                data: [
                    {% for date in dates %}
                        {% if pageGraph[date] is defined %}
                            {{ pageGraph[date]['count'] }}
                        {% else %}
                            0
                        {% endif %}
                        {% if loop.last is false %},{% endif %}
                    {% endfor %}
                ]
            }
        ]
    });
});
                    </script>




                </div>
            </div>
        {% endif %}


    {% else %}
    	<div class="alert alert-danger" role="alert">{{ feedback }}</div>
    {% endif %}
</div>