
<script type="text/javascript">
    var templateSubject = '',
        templateBody    = '',
        previewSubject  = '',
        previewBody     = '',
        variables       = {};

    $(document).ready(function() {
        $('.test-template').click(function() {
            $.get($(this).attr('href'), function(json) {
                var data  = $.parseJSON(json),
                    modal = $('#GeneralModal');

                if (data.status == 'success') {
                    variableHtml     = '<strong>{{ label.label('Variables', false) }}</strong>';
                    variableTemplate = '<div class="form-group">' + 
                                '<input type="text" id="%key%" name="%key%" value="" class="form-control" placeholder="%key%">' + 
                            '</div>';

                    $.each(data.data.template_variable.split(","), function(i, v) {
                        key           = v.replace(RegExp("{", 'g'), '').replace(RegExp("}", 'g'), '');
                        html          = variableTemplate.replace(/%key%/g, key);
                        variableHtml += html;
                    });

                    email = '<div class="form-group">' + 
                                '<label for="key" class="control-label">{{ label.label('Email', false) }}</label>' + 
                                '<input type="text" id="preview-send-to" name="preview-send-to" value="" class="form-control" placeholder="{{ label.label('Email', false) }}">' + 
                            '</div>';

                    subject = '<div class="form-group">' + 
                                '<label for="key" class="control-label">{{ label.label('Subject', false) }}</label>' + 
                                '<div class="preview-subject">' + data.data.template_subject + '</div>' + 
                            '</div>';

                    body = '<div class="form-group">' + 
                                '<label for="key" class="control-label">{{ label.label('Body', false) }}</label>' + 
                                '<div class="preview-body">' + data.data.template_body + '</div>' + 
                            '</div>';
                    
                    moreActions = '<button type="button" class="btn btn-primary" onclick="sendTest()">' +
                                    '<i class="fa fa-check"></i>' +
                                    ' {{ label.button('Send-Name', false) }}' +
                                '</button>';

                    modal.find('.modal-title').html(data.data.subject);
                    modal.find('.modal-body').html(email + variableHtml + subject + body);
                    modal.find('.more-actions').html(moreActions);

                    templateSubject = previewSubject = data.data.subject;
                    templateBody    = previewBody    = data.data.body;

                    $.each(data.data.template_variable.split(","), function(i, v) {
                        var key = v.replace(RegExp("{", 'g'), '').replace(RegExp("}", 'g'), '');

                        $('#' + key).keyup(function() {
                            var value = $(this).val(),
                                name  = $(this).attr('name');

                            variables[name] = value;
                            updatePreviewTemplate();
                        });
                    });

                    $('#preview-send-to').keyup(function() {
                        if ($(this).val() != '') {
                            $(this).parent().removeClass('has-error').removeClass('has-feedback');
                        } else {
                            $(this).parent().addClass('has-error').addClass('has-feedback');
                        }
                    });
                } else {
                    modal.find('.modal-title').html();
                    modal.find('.modal-body').html(data.message);
                }

                modal.find('.modal-dialog').addClass('modal-lg');
                modal.modal('show');
            });

            return false;
        });
    });

    function updatePreviewTemplate() {
        previewSubject = templateSubject;
        previewBody    = templateBody;

        $.each(variables, function(name, value) {
            name = '\{\{' + name + '\}\}';

            previewSubject = previewSubject.replace(new RegExp(name,"g"), value);
            previewBody    = previewBody.replace(new RegExp(name,"g"), value);
        });

        modal = $('#GeneralModal');
        modal.find('.modal-title').html(previewSubject);
        modal.find('.preview-subject').html(previewSubject);
        modal.find('.preview-body').html(previewBody);
    }

    function sendTest() {
        var email = $('#preview-send-to');

        if (email.val() != '') {
            $.post('/template/ajaxSendTestEmail', {subject: previewSubject, body: previewBody, to: email.val()}, function(json) {
                var data  = $.parseJSON(json),
                    modal = $('#GeneralModal');

                if (data.status == 'success') {
                    alert(data.status);
                } else {
                    alert(data.message);
                }
            });
        } else {
            email.parent().addClass('has-error').addClass('has-feedback');
        }
    }
</script>