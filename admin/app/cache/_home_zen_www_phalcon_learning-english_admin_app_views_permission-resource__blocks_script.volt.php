
<script>
    $(document).ready(function() {
        $('.resource-name').editable({
            url: "/permission-resource/ajaxEditName",
            type: 'text',
            pk: function(){
                var id = $(this).parent().parent().attr('id');

                return id;
            },
            success: function(response, newValue) {
            },
        });
    });
</script>