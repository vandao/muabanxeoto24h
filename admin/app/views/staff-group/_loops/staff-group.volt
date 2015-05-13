
                        <tr>
                            <td>{{ item.id }}</td>
                            <td>{{ item.staff_group }}</td>
                            <td>
                                <div class="btn-group">
                                    {{ button.editRow(item.id) }}
                                    {{ link_to("permission/manage/staff-group/"~item.id, '<i class="fa fa-cog"></i>', 'class': 'btn btn-default btn-xs') }}
                                </div>
                            </td>
                        </tr>