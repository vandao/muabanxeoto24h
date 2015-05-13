

<?php echo $this->getContent(); ?>

<div class="row">
    <div class="col-md-12">
        <div class="text-center">Copyright &copy; 2014</div>
    </div>
</div>


<div class="modal fade" id="GeneralModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <span class="more-actions"></span>
        <button type="button" class="btn btn-default close-button" data-dismiss="modal">
          <i class="fa fa-times"></i>
          <?php echo $this->label->button('Close-Name', false); ?>
        </button>
      </div>
    </div>
  </div>
</div>