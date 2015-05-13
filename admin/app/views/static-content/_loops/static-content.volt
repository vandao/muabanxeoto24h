
                        <tr>
                            <td>{{ item.id }}</td>
                            <td>{{ item.static_content_key }}</td>
                            <td>
                                {{ item.static_content_title }}
                                <br />
                                {{ item.static_content_page_title }}
                            </td>
                            <td>{{ button.disabled(item.id, item.is_disabled) }}</td>
                            <td>
                                <div class="btn-group">
                                    {{ button.editRow(item.id) }}
                                </div>
                            </td>
                        </tr>