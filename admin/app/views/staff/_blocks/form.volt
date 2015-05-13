
<div class="row">
    <div class="col-md-6">
        {{ form('class': 'well') }}
            {{ form.messageVertical(feedback) }}

            {{ form.renderVertical('staff_group_id') }}
            {{ form.renderVertical('full_name') }}
            {{ form.renderVertical('email') }}
            {{ form.renderVertical('password') }}
            {{ form.renderVertical('confirm_password') }}

            {{ form.renderVertical('is_disabled') }}

            {{ form.renderVertical('csrf', ['value': security.getToken()]) }}

            {{ button.submitForm() }}
            {{ button.resetForm() }}
        </form>
    </div>
    <div class="col-md-6"></div>
</div>