
                        <tr id="{{ item.id }}">
                            <td>{{ item.id }}</td>
                            <td>{{ item.from }}</td>
                            <td>{{ item.to }}</td>
                            <td>{{ item.subject }}</td>
                            <td class="send-result">{{ item.sent_result }}</td>
                            <td class="status">{{ item.status }}</td>
                            <td>{{ item.date_created }}</td>
                            <td>
                                <div class="btn-group">
                                    {{ link_to(view.getControllerName() ~ "/ajaxReview/"~item.id, '<i class="fa fa-search"></i>', 'class': 'btn btn-default btn-xs preview-email') }}

                                    <?php if (! in_array($item->status, array(STATUS_PENDING, STATUS_SENT))) : ?> 
                                        {{ link_to(view.getControllerName() ~ "/ajaxReset/"~item.id, '<i class="fa fa-refresh"></i>', 'class': 'btn btn-info btn-xs reset-email') }}
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>