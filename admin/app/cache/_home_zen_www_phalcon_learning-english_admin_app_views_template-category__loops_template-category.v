
                        <tr>
                            <td><?php echo $item->id; ?></td>
                            <td><?php echo $item->template_category_key; ?></td>
                            <td><?php echo $item->template_category; ?></td>
                            <td><?php echo $this->button->disabled($item->id, $item->is_disabled); ?></td>
                            <td>
                                <div class="btn-group">
                                    <?php echo $this->button->editRow($item->id); ?>
                                </div>
                            </td>
                        </tr>