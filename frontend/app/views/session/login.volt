

<div class="container">
    <div class="page-header">{{ pageHeader }}</div>

    {{ form("id": "login") }}
        {{ form.messageVertical(feedback) }}

        {{ form.renderVertical('email') }}
        {{ form.renderVertical('password') }}

        {{ form.renderVertical('remember_me') }}

        {{ form.renderVertical('csrf', ['value': security.getToken()]) }}

        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-sign-in"></i>
                {{ label.direct("Button-Login") }}
            </button>

            {{ link_to('user/forgot-password', '<i class="fa fa-refresh"></i> ' ~ label.direct("Button-Forgot-Password"), 'class': 'btn btn-default') }}
        </div>
    </form>
</div>



<script>
    $(document).ready(function() {
        $("#login").validate({
            ignore: "",
            errorElement: "span",
            errorClass: "checkbox-inline",
            errorPlacement: function(error, element) {
                element.parent().find('label').after(error);
            },
            highlight: function(element) {
                var parent    = $(element).parent(),
                    errorIcon = '<span class="glyphicon glyphicon-remove form-control-feedback"></span>';

                $(element).parent().addClass('has-error').removeClass('has-success').addClass('has-feedback');

                parent.find('.form-control-feedback').remove();
                parent.append(errorIcon);
            },
            unhighlight: function(element) {
                var parent      = $(element).parent(),
                    successIcon = '<span class="glyphicon glyphicon-ok form-control-feedback"></span>';

                parent.removeClass('has-error').addClass('has-success').addClass('has-feedback');

                parent.find('.form-control-feedback').remove();
                parent.append(successIcon);
            },
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6
                }
            },
            messages: {
                email: {
                    required: "{{ label.direct('Error-Field-Required_FieldName', false, ['_FieldName': label.direct('Label-Email')]) }}",
                    email: "{{ label.direct('Error-Field-Value-Invalid_FieldName', false, ['_FieldName': label.direct('Label-Email')]) }}"
                },
                password: {
                    required: "{{ label.direct('Error-Field-Required_FieldName', false, ['_FieldName': label.direct('Label-Password')]) }}",
                    minlength: "{{ label.direct('Error-Field-Value-Too-Short_FieldName_Min', false, ['_FieldName': label.direct('Label-Password')]) }}",
                }
            }
        });
    });
</script>