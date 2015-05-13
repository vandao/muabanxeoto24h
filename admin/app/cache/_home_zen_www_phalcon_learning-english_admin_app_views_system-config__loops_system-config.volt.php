
                        <tr>
                            <td><?php echo $item->id; ?></td>
                            <td><?php echo $item->key; ?></td>
                            <td><?php echo $item->value; ?></td>
                            <td>
                                <div class="btn-group">
                                    <?php echo $this->button->editRow($item->id); ?>
                                </div>
                            </td>
                        </tr>