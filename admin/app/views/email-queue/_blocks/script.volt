

<script type="text/javascript">
    $(document).ready(function() {
        $('.preview-email').click(function() {
            $.get($(this).attr('href'), function(json) {
                var data  = $.parseJSON(json),
                    modal = $('#GeneralModal');

                if (data.status == 'success') {
                    modal.find('.modal-title').html(data.data.subject);
                    modal.find('.modal-body').html(data.data.body);
                } else {
                    modal.find('.modal-title').html();
                    modal.find('.modal-body').html(data.message);
                }

                modal.find('.modal-dialog').addClass('modal-lg');
                modal.modal('show');
            });

            return false;
        });

        $('.reset-email').click(function() {
            var row = $(this).parent().parent().parent().parent();

            $.get($(this).attr('href'), function(json) {
                var data  = $.parseJSON(json);

                if (data.status == 'success') {
                    row.find('.send-result').html('');
                    row.find('.status').html('<?php echo STATUS_PENDING; ?>');
                } else {
                    alertMessage(data);
                }
            });

            $(this).remove();
            return false;
        });
    });
</script>