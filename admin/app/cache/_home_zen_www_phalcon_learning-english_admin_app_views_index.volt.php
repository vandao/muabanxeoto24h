<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.png">

    <?php echo $this->tag->getTitle(); ?>
    <?php echo $this->tag->stylesheetLink('js/bootstrap/css/bootstrap.min.css'); ?> 
    <?php echo $this->tag->stylesheetLink('js/font-awesome/css/font-awesome.min.css'); ?> 
    <?php echo $this->tag->stylesheetLink('js/editable/bootstrap/css/bootstrap-editable.css'); ?>
    <?php echo $this->tag->stylesheetLink('css/styles.css'); ?> 
     

    </head>
    <body>
        <?php echo $this->tag->javascriptInclude('js/jquery/jquery.min.js'); ?> 
        <?php echo $this->tag->javascriptInclude('js/jquery/jquery-ui.js'); ?> 

        <?php echo $this->getContent(); ?>
        
        <?php echo $this->tag->javascriptInclude('js/jquery/jquery.validate.js'); ?> 
        <?php echo $this->tag->javascriptInclude('js/bootstrap/js/bootstrap.min.js'); ?>
        <?php echo $this->tag->javascriptInclude('js/bootstrap/js/bootstrap-datepicker.js'); ?>
        <?php echo $this->tag->javascriptInclude('js/editable/bootstrap/js/bootstrap-editable.min.js'); ?> 
        <?php echo $this->tag->javascriptInclude('js/tinymce/tinymce.min.js'); ?>
        <!-- <?php echo $this->tag->javascriptInclude('js/bootstrap/js/typeahead/typeahead.jquery.min.js'); ?>
        <?php echo $this->tag->javascriptInclude('js/bootstrap/js/typeahead/bloodhound.min.js'); ?> -->

        <?php echo $this->tag->javascriptInclude('js/bootstrap/js/typeahead/typeahead.js'); ?>
        <?php echo $this->tag->javascriptInclude('js/bootstrap/js/typeahead/handlebars.js'); ?>
        
        <?php echo $this->tag->javascriptInclude('js/library.js'); ?> 
        <?php echo $this->tag->javascriptInclude('js/scripts.js'); ?>
        
<style type="text/css">
    .editable-label label span { display: inline-block; min-width: 60px; font-size: 14px; font-weight: bold}
    .popover {
      z-index: 1060; /* A value higher than 1010 that solves the problem */
    }
</style>

<script type="text/javascript">
  $(function() {
      $('.label-editable').editable({ 
          container: 'body',         
          url: '/system-label/rename'
      });
  });

  (function($) {
      "use strict";

      var Label = function(options) {
          this.init('label', options, Label.defaults);
      };

      //inherit from Abstract input
      $.fn.editableutils.inherit(Label, $.fn.editabletypes.abstractinput);

      $.extend(Label.prototype, {
          /**
          Renders input from tpl

          @method render() 
          **/
          render: function() {
              this.$input = this.$tpl.find('input,textarea');
          },

          /**
          Default method to show value in element. Can be overwritten by display option.
          
          @method value2html(value, element) 
          **/
          value2html: function(value, element) {
              if (!value) {
                  $(element).empty();
                  return;
              }
              var html = value.label_value;
              $(element).html(html);
          },

          /**
          Gets value from element's html
          
          @method html2value(html) 
          **/
          html2value: function(html) {
              /*
              you may write parsing method to get value by element's html
              e.g. "Moscow, st. Lenina, bld. 15" => {value: "Moscow", hint: "Lenina", building: "15"}
              but for complex structures it's not recommended.
              Better set value directly via javascript, e.g. 
              editable({
                  value: {
                      value: "Moscow", 
                      hint: "Lenina", 
                      building: "15"
                  }
              });
            */
              return null;
          },

          /**
          Converts value to string. 
          It is used in internal comparing (not for sending to server).
          
          @method value2str(value)  
         **/
          value2str: function(value) {
              var str = '';
              if (value) {
                  for (var k in value) {
                      str = str + k + ':' + value[k] + ';';
                  }
              }
              return str;
          },

          /**
           Converts string to value. Used for reading value from 'data-value' attribute.
          
          @method str2value(str)  
          **/
          str2value: function(str) {
              /*
             this is mainly for parsing value defined in data-value attribute. 
             If you will always set value by javascript, no need to overwrite it
             */
              return str;
          },

          /**
          Sets value of input.
          
          @method value2input(value) 
          @param {mixed} value
         **/
          value2input: function(value) {
              if (!value) {
                  return;
              }
              this.$input.filter('[name="label_value"]').val(value.label_value);
              this.$input.filter('[name="label_hint"]').val(value.label_hint);
              this.$input.filter('[name="language_id"]').val(value.language_id);
          },

          /**
          Returns value of input.
          
          @method input2value() 
         **/
          input2value: function() {
              return {
                  label_value: this.$input.filter('[name="label_value"]').val(),
                  label_hint: this.$input.filter('[name="label_hint"]').val(),
                  language_id: this.$input.filter('[name="language_id"]').val()
              };
          },

          /**
          Activates input: sets focus on the first field.
          
          @method activate() 
         **/
          activate: function() {
              this.$input.filter('[name="label_value"]').focus();
          },

          /**
          Attaches handler to submit form in case of 'showbuttons=false' mode
          
          @method autosubmit() 
         **/
          autosubmit: function() {
              this.$input.keydown(function(e) {
                  if (e.which === 13) {
                      $(this).closest('form').submit();
                  }
              });
          }
      });

      Label.defaults = $.extend({}, $.fn.editabletypes.abstractinput.defaults, {
          tpl: '<div class="editable-label"><label><span>Value: </span><textarea type="text" name="label_value" class="form-control input-sm"></textarea></label></div>' +
              '<div class="editable-label hidden"><label><span>Hint: </span><input type="text" name="label_hint" class="form-control input-sm"></label></div>' +
              '<div class="editable-label hidden"><input type="text" name="language_id" class="form-control input-sm"></div>',

          inputclass: ''
      });

      $.fn.editabletypes.label = Label;

  }(window.jQuery));
</script>
	</body>
</html>