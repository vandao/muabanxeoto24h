

<div class="row">
    <div class="col-md-6">
        {{ form('class': 'well') }}
            {{ form.messageVertical(feedback) }}

            {{ form.renderVertical('group') }}

            {{ form.renderVertical('csrf', ['value': security.getToken()]) }}

            {{ button.submitForm() }}
            {{ button.resetForm() }}
        </form>
    </div>
    <div class="col-md-6"></div>
</div>