
<tr>
    <td>{{ item.id }}</td>
    <td>{{ item.template_group }}</td>
    <td>{{ item.template_category }}</td>
    <td>{{ item.template_key }}</td>
    <td>{{ item.template_subject }}</td>
    <td>{{ button.disabled(item.id, item.is_disabled) }}</td>
    <td>
        <div class="btn-group">
            {{ button.editRow(item.id) }}
            {{ link_to(view.getControllerName() ~ "/ajaxReview/"~item.id, '<i class="fa fa-envelope"></i>', 'class': 'btn btn-primary btn-xs test-template') }}
        </div>
    </td>
</tr>