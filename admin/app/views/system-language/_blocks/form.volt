
<?php 
    $languages = SystemLanguage::find("is_disabled = 0");
?>

{{ form() }}
    <div class="row">
        <div class="col-md-6">
            <div class="well">
                {{ form.messageVertical(feedback) }}

                {{ form.renderVertical('language_code') }}

                {{ form.renderVertical('csrf', ['value': security.getToken()]) }}

                {{ button.submitForm() }}
                {{ button.resetForm() }}
            </div>
        </div>
        <div class="col-md-6">
            {% for language in languages %}
                <div class="well">
                    {{ form.renderVertical('language_name_' ~ language.id) }}
                </div>
            {% endfor %}
        </div>
    </div>
</form>