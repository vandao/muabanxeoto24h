
<?php 
    $languages = SystemLanguage::find("is_disabled = 0");
?>

{{ form() }}
    <div class="row">
        <div class="col-md-6">
            <div class="well">
                {{ form.messageVertical(feedback) }}

                {{ form.renderVertical('is_disabled') }}

                {{ form.renderVertical('csrf', ['value': security.getToken()]) }}

                {{ button.submitForm() }}
                {{ button.resetForm() }}
            </div>
        </div>
        <div class="col-md-6">
            {% for language in languages %}
                <div class="well">
                    {{ form.renderVertical('group_' ~ language.id) }}
                </div>
            {% endfor %}
        </div>
    </div>
</form>