
<tr>
    <td>{{ item.id }}</td>
    <td>{{ item.staff_group }}</td>
    <td>{{ item.full_name }}</td>
    <td>{{ item.email }}</td>
    <td>{{ item.date_created }}</td>
    <td>
        <div class="btn-group">
            {{ button.editRow(item.id) }}
            {% if item.is_custom_permission %}
                {{ link_to("permission/manage/staff/"~item.id, '<i class="fa fa-cog"></i>', 'class': 'btn btn-danger btn-xs', 'title': label.button('Custom-Permission', false)) }}
            {% else %}
                {{ link_to("permission/manage/staff/"~item.id, '<i class="fa fa-cog"></i>', 'class': 'btn btn-default btn-xs') }}
            {% endif %}
        </div>
    </td>
</tr>