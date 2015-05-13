

<div class="container">
    <div class="page-header">{{ pageHeader }}</div>

    {{ form("id": "login") }}
        {{ form.messageVertical(feedback) }}

        {{ form.renderVertical('full_name') }}
        {{ form.renderVertical('email') }}
        {{ form.renderVertical('password') }}
        {{ form.renderVertical('confirm_password') }}
        {{ form.renderVertical('phone_number') }}

        {{ form.renderVertical('csrf', ['value': security.getToken()]) }}

        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-sign-in"></i>
                {{ label.direct("Button-Sign-Up") }}
            </button>
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
                full_name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6
                },
                confirm_password: {
                    required: true,
                    equalTo: "#password",
                    minlength: 6
                },
                phone_number: {
                    required: true
                }
            },
            messages: {
                full_name: {
                    required: "{{ label.direct('Error-Field-Required_FieldName', false, ['_FieldName': label.direct('Label-Full-Name')]) }}"
                },
                email: {
                    required: "{{ label.direct('Error-Field-Required_FieldName', false, ['_FieldName': label.direct('Label-Email')]) }}",
                    email: "{{ label.direct('Error-Field-Value-Invalid_FieldName', false, ['_FieldName': label.direct('Label-Email')]) }}"
                },
                password: {
                    required: "{{ label.direct('Error-Field-Required_FieldName', false, ['_FieldName': label.direct('Label-Password')]) }}",
                    minlength: "{{ label.direct('Error-Field-Value-Too-Short_FieldName_Min', false, ['_FieldName': label.direct('Label-Password')]) }}",
                },
                confirm_password: {
                    required: "{{ label.direct('Error-Field-Required_FieldName', false, ['_FieldName': label.direct('Label-Confirm-Password')]) }}",
                    equalTo: "{{ label.direct('Error-Field-Value-Not-Match-Confirmation_FieldName', false, ['_FieldName': label.direct('Label-Confirm-Password')]) }}",
                    minlength: "{{ label.direct('Error-Field-Value-Too-Short_FieldName_Min', false, ['_FieldName': label.direct('Label-Confirm-Password'), '_Min': 6]) }}"
                },
                phone_number: {
                    required: "{{ label.direct('Error-Field-Required_FieldName', false, ['_FieldName': label.direct('Label-Email')]) }}"
                },
            }
        });
    });
</script>