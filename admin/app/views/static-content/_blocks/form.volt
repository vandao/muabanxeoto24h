
<?php 
    $languages   = SystemLanguage::find("is_disabled = 0");
?>

{{ form('enctype': 'multipart/form-data') }}
    <div class="row">
        <div class="col-md-6">
            <div class="well">
                {{ form.messageVertical(feedback) }}

                {{ form.renderVertical('static_content_key') }}
                {{ form.renderVertical('is_disabled') }}

                {{ form.renderVertical('csrf', ['value': security.getToken()]) }}

                {{ button.submitForm() }}
                {{ button.resetForm() }}
            </div>
            
            {% if imageUrl is defined %}
                <div class="well">
                    <img src="{{ imageUrl }}" width="400px" />
                </div>
            {% endif %}
        </div>
        <div class="col-md-6">
            {% for language in languages %}
                <div class="well">
                    {{ form.renderVertical('static_content_title_' ~ language.id) }}
                    {{ form.renderVertical('static_content_content_' ~ language.id) }}
                    {{ form.renderVertical('static_content_page_title_' ~ language.id) }}
                </div>
            {% endfor %}
        </div>
    </div>
</form>