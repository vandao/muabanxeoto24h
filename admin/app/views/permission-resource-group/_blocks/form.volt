

<div class="row">
    <div class="col-md-6">
        {{ form('class': 'well') }}
            {{ form.messageVertical(feedback) }}

            {{ form.renderVertical('name') }}
            {{ form.renderVertical('filter_prefix') }}

            {{ form.renderVertical('csrf', ['value': security.getToken()]) }}

            {{ button.submitForm() }}
            {{ button.resetForm() }}
        </form>
    </div>
    <div class="col-md-6"></div>
</div>