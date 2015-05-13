<tr id="{{ item.id }}">
    <td>{{ item.id }}</td>
    <td>{{ item.section }}</td>    
    <td>{{ item.label_key }}</td>
    <td>{{ label.normal(item.label_key, true, [], item.language_id) }}</td>
    <td>
        {{ button.approved(item.system_label_language_id, item.is_approved) }}
    </td>
    <td>
        <div class="btn-group">
            {{ button.editRow(item.id) }}
            {{ button.deleteRow(item.id, 0) }}
        </div>
    </td>
</tr>