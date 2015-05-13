
                        <tr>
                            <td>{{ item.id }}</td>
                            <td>{{ item.key }}</td>
                            <td>{{ item.value }}</td>
                            <td>
                                <div class="btn-group">
                                    {{ button.editRow(item.id) }}
                                </div>
                            </td>
                        </tr>