
<?php 
    $languages = SystemLanguage::find("is_disabled = 0");
?>

{{ form() }}
    <div class="row">
        <div class="col-md-6">
            <div class="well">
                {{ form.messageVertical(feedback) }}

                {{ form.renderVertical('template_category_id') }}
                {{ form.renderVertical('template_group_id') }}
                {{ form.renderVertical('template_key') }}
                {{ form.renderVertical('template_variable') }}
                {{ form.renderVertical('is_disabled') }}

                {{ form.renderVertical('csrf', ['value': security.getToken()]) }}

                {{ button.submitForm() }}
                {{ button.resetForm() }}
            </div>
        </div>
        <div class="col-md-6">
            {% for language in languages %}
                <div class="well">
                    {{ form.renderVertical('template_subject_' ~ language.id) }}
                    {{ form.renderVertical('template_body_' ~ language.id) }}
                </div>
            {% endfor %}
        </div>
    </div>
</form>