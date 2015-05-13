


<div class="container">
    {% if page.items is defined and page.total_items > 0 %}
        {% for item in page.items %}
            {% if loop.index % 3 %}<div class="row">{% endif %}

                <div class="col-md-4 col-sm-4">
                    <h2>{{ item.application_name }}</h2>
                    
                    <p class="text-center">
                        {% if item.publisher_code is defined %}
                            <a class="btn btn-success" href="/user/application/{{ item.application_code }}" role="button">{{ label.button("Statistics") }}</a>
                        {% else %}
                            <a class="btn btn-primary" href="/user/application/{{ item.application_code }}" role="button">{{ label.button("Join-Us") }}</a>
                        {% endif %}
                    </p>
                </div>

            {% if loop.index % 3 is 2 or loop.last %}</div>{% endif %}
        {% endfor %}
    {% else %}
        <tr>
            <td colspan="7">{{ label.label('NoResult') }}</td>
        </tr>
    {% endif %}
</div>