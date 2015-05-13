
<?php 
    $languages = SystemLanguage::fetchPair("id", "language_name", false);
?>

{{ form() }}
    <div class="row">
        <div class="col-md-6">
            <div class="well">
                {{ form.messageVertical(feedback) }}

                {{ form.renderVertical('section') }}
                {{ form.renderVertical('label_key') }}

                {{ form.renderVertical('csrf', ['value': security.getToken()]) }}

                {{ button.submitForm() }}
                {{ button.resetForm() }}
            </div>
        </div>
        <div class="col-md-6">
            {% for languageId, languageName in languages %}
                <div class="well">
                    {{ form.renderVertical('label_value_' ~ languageId) }}
                    {{ form.renderVertical('label_hint_' ~ languageId) }}
                    {{ form.renderVertical('is_approved_' ~ languageId) }}
                </div>
            {% endfor %}
        </div>
    </div>
</form>