
                        <tr>
                            <td><?php echo $item->id; ?></td>
                            <td><?php echo $item->staff_group; ?></td>
                            <td>
                                <div class="btn-group">
                                    <?php echo $this->button->editRow($item->id); ?>
                                    <?php echo $this->tag->linkTo(array('permission/manage/staff-group/' . $item->id, '<i class="fa fa-cog"></i>', 'class' => 'btn btn-default btn-xs')); ?>
                                </div>
                            </td>
                        </tr>