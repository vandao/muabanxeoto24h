<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.png">

    {{ get_title() }}
    {{ stylesheet_link ('js/bootstrap/css/bootstrap.min.css') }} 
    {{ stylesheet_link ('js/font-awesome/css/font-awesome.min.css') }} 
    {{ stylesheet_link ('js/editable/bootstrap/css/bootstrap-editable.css') }}
    {{ stylesheet_link ('css/styles.css') }} 
     

    </head>
    <body>
        {{ javascript_include ('js/jquery/jquery.min.js') }} 
        {{ javascript_include ('js/jquery/jquery-ui.js') }} 

        {{ content() }}
        
        {{ javascript_include ('js/jquery/jquery.validate.js') }} 
        {{ javascript_include ('js/bootstrap/js/bootstrap.min.js') }}
        {{ javascript_include ('js/bootstrap/js/bootstrap-datepicker.js') }}
        {{ javascript_include ('js/editable/bootstrap/js/bootstrap-editable.min.js') }} 
        {{ javascript_include ('js/tinymce/tinymce.min.js') }}
        <!-- {{ javascript_include ('js/bootstrap/js/typeahead/typeahead.jquery.min.js') }}
        {{ javascript_include ('js/bootstrap/js/typeahead/bloodhound.min.js') }} -->

        {{ javascript_include ('js/bootstrap/js/typeahead/typeahead.js') }}
        {{ javascript_include ('js/bootstrap/js/typeahead/handlebars.js') }}
        
        {{ javascript_include ('js/library.js') }} 
        {{ javascript_include ('js/scripts.js') }}
        {% include "system-label/_blocks/editable.volt" %}
	</body>
</html>