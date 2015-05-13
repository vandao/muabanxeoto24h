
                        <tr>
                            <td><?php echo $item->id; ?></td>
                            <td><?php echo $item->static_content_key; ?></td>
                            <td>
                                <?php echo $item->static_content_title; ?>
                                <br />
                                <?php echo $item->static_content_page_title; ?>
                            </td>
                            <td><?php echo $this->button->disabled($item->id, $item->is_disabled); ?></td>
                            <td>
                                <div class="btn-group">
                                    <?php echo $this->button->editRow($item->id); ?>
                                </div>
                            </td>
                        </tr>