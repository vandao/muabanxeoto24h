
                        <tr>
                            <td>{{ item.id }}</td>
                            <td>{{ item.name }}</td>
                            <td>{{ item.filter_prefix }}</td>
                            <td>
                                <div class="btn-group">
                                    {{ button.editRow(item.id) }}
                                </div>
                            </td>
                        </tr>