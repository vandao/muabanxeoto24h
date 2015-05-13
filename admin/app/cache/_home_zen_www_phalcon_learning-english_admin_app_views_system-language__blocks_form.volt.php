
<?php 
    $languages = SystemLanguage::find("is_disabled = 0");
?>

<?php echo $this->tag->form(array()); ?>
    <div class="row">
        <div class="col-md-6">
            <div class="well">
                <?php echo $form->messageVertical($feedback); ?>

                <?php echo $form->renderVertical('language_code'); ?>

                <?php echo $form->renderVertical('csrf', array('value' => $this->security->getToken())); ?>

                <?php echo $this->button->submitForm(); ?>
                <?php echo $this->button->resetForm(); ?>
            </div>
        </div>
        <div class="col-md-6">
            <?php foreach ($languages as $language) { ?>
                <div class="well">
                    <?php echo $form->renderVertical('language_name_' . $language->id); ?>
                </div>
            <?php } ?>
        </div>
    </div>
</form>