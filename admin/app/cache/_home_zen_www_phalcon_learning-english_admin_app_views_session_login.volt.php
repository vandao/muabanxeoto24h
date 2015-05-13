

  <?php echo $this->getContent(); ?>

  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <?php echo $this->tag->form(array('class' => 'form-horizontal well')); ?>
          <div class="form-group">
            <div class="col-md-offset-2 col-md-10">
              <h2><?php echo $this->label->normal('Page-Login-Header', false); ?></h2>
            </div>
          </div>

          <?php echo $form->messageHorizontal($feedback); ?>

          <?php echo $form->renderHorizontal('email'); ?>
          <?php echo $form->renderHorizontal('password'); ?>
          
          <?php echo $form->renderHorizontal('remember_me'); ?>

          <?php echo $form->renderHorizontal('csrf', array('value' => $this->security->getToken())); ?>

          <div class="form-group">
            <div class="col-md-offset-2 col-md-10">
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-sign-in"></i>
                Sign in
              </button>

              <?php echo $this->tag->linkTo(array('staffs/forgot-password', '<i class="fa fa-refresh"></i> Forgot password', 'class' => 'btn btn-default')); ?>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
