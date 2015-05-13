

<div class="row">
    <div class="col-md-6">
        <?php echo $this->tag->form(array('class' => 'well')); ?>
            <?php echo $form->messageVertical($feedback); ?>

            <?php echo $form->renderVertical('key'); ?>
            <?php echo $form->renderVertical('value'); ?>

            <?php echo $form->renderVertical('csrf', array('value' => $this->security->getToken())); ?>

            <?php echo $this->button->submitForm(); ?>
            <?php echo $this->button->resetForm(); ?>
        </form>
    </div>
    <div class="col-md-6"></div>
</div>