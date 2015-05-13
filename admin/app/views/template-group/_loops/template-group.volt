
                        <tr>
                            <td>{{ item.id }}</td>
                            <td>{{ item.template_group_key }}</td>
                            <td>{{ item.template_group }}</td>
                            <td>{{ button.disabled(item.id, item.is_disabled) }}</td>
                            <td>
                                <div class="btn-group">
                                    {{ button.editRow(item.id) }}
                                </div>
                            </td>
                        </tr>