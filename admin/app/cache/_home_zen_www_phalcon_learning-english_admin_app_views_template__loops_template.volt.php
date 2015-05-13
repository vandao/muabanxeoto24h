
<tr>
    <td><?php echo $item->id; ?></td>
    <td><?php echo $item->template_group; ?></td>
    <td><?php echo $item->template_category; ?></td>
    <td><?php echo $item->template_key; ?></td>
    <td><?php echo $item->template_subject; ?></td>
    <td><?php echo $this->button->disabled($item->id, $item->is_disabled); ?></td>
    <td>
        <div class="btn-group">
            <?php echo $this->button->editRow($item->id); ?>
            <?php echo $this->tag->linkTo(array($this->view->getControllerName() . '/ajaxReview/' . $item->id, '<i class="fa fa-envelope"></i>', 'class' => 'btn btn-primary btn-xs test-template')); ?>
        </div>
    </td>
</tr>