<tr id="<?php echo $item->id; ?>">
    <td><?php echo $item->id; ?></td>
    <td><?php echo $item->section; ?></td>    
    <td><?php echo $item->label_key; ?></td>
    <td><?php echo $this->label->normal($item->label_key, true, array(), $item->language_id); ?></td>
    <td>
        <?php echo $this->button->approved($item->system_label_language_id, $item->is_approved); ?>
    </td>
    <td>
        <div class="btn-group">
            <?php echo $this->button->editRow($item->id); ?>
            <?php echo $this->button->deleteRow($item->id, 0); ?>
        </div>
    </td>
</tr>