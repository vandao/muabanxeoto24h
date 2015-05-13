
<tr>
    <td><?php echo $item->id; ?></td>
    <td><?php echo $item->staff_group; ?></td>
    <td><?php echo $item->full_name; ?></td>
    <td><?php echo $item->email; ?></td>
    <td><?php echo $item->date_created; ?></td>
    <td>
        <div class="btn-group">
            <?php echo $this->button->editRow($item->id); ?>
            <?php if ($item->is_custom_permission) { ?>
                <?php echo $this->tag->linkTo(array('permission/manage/staff/' . $item->id, '<i class="fa fa-cog"></i>', 'class' => 'btn btn-danger btn-xs', 'title' => $this->label->button('Custom-Permission', false))); ?>
            <?php } else { ?>
                <?php echo $this->tag->linkTo(array('permission/manage/staff/' . $item->id, '<i class="fa fa-cog"></i>', 'class' => 'btn btn-default btn-xs')); ?>
            <?php } ?>
        </div>
    </td>
</tr>