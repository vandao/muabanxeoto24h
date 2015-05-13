
                        <tr>
                            <td>{{ item.id }}</td>
                            <td>{{ item.language_name }}</td>
                            <td>{{ item.language_code }}</td>
                            <td>
                                {{ button.disabled(item.id, item.is_disabled) }}
                            </td>
                            <td>
                                <div class="btn-group">
                                    {{ button.editRow(item.id) }}
                                </div>
                            </td>
                        </tr>