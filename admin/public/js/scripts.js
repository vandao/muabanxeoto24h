tinymce.init({
    editor_deselector: "mceNoEditor",
    selector: "textarea[is-disable-tinymce!=1]",
    theme: "modern",
    forced_root_block: '',
    force_br_newlines: true,
    force_p_newlines: false,
    menubar: false,
    toolbar_items_size: 'small',
    fontsize_formats: "8pt 9pt 10pt 11pt 12pt 13pt 14pt 15pt 16pt 18pt 20pt 22pt 24pt 26pt 30pt 36pt 40pt",
    plugins: [
        "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
        "table contextmenu directionality emoticons template textcolor paste textcolor colorpicker textpattern"
    ],

    toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
    toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
    toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",

    external_filemanager_path: "/js/filemanager/",
    filemanager_title: "Responsive Filemanager",
    external_plugins: {
        "filemanager": "/js/filemanager/plugin.min.js"
    },

    height: 200,
    style_formats: [{
        title: 'Bold text',
        inline: 'strong'
    }, {
        title: 'Red text',
        inline: 'span',
        styles: {
            color: '#ff0000'
        }
    }, {
        title: 'Red header',
        block: 'h1',
        styles: {
            color: '#ff0000'
        }
    }, {
        title: 'Example 1',
        inline: 'span',
        classes: 'example1'
    }, {
        title: 'Example 2',
        inline: 'span',
        classes: 'example2'
    }, {
        title: 'Table styles'
    }, {
        title: 'Table row 1',
        selector: 'tr',
        classes: 'tablerow1'
    }]
});


$(document).ready(function() {
    $('#date_of_birth').datepicker({
        format: 'yyyy-mm-dd'
    });
    $('input[name=DateFrom], input[name=DateTo]').datepicker({
        format: 'dd-mm-yyyy',
        weekStart: 1
    });
    
    var searchTypeActiveDatepicker = ['date_of_birth', 'date_created'],
        keywordInput = $("input[name='keywordSearch']");
    $("select[name='typeSearch']").change(function() {

        if ($.inArray($(this).val(), searchTypeActiveDatepicker) != -1) {
            keywordInput.datepicker({
                format: 'yyyy-mm-dd'
            });
        } else {
            keywordInput.parent().prepend('<input type="text" placeholder="Keyword" value="" class="form-control" name="keywordSearch">');
            keywordInput.remove();
        }
    });

    if ($.inArray($("select[name='typeSearch']").val(), searchTypeActiveDatepicker) != -1) {
        keywordInput.datepicker({
            format: 'yyyy-mm-dd'
        });
    }
    $('.sort').click(function(){
        $("#sort").val($(this).attr('data-sort'));
        $("#sort_dir").val($(this).attr('data-sort-dir'));
        $("#searchForm").submit();
    })
    // update status approve or disable
    $('.update-status').click(function(){
            var that = $(this);
            $.get($(this).attr('href'), function(response) {
                response = JSON.parse(response);              
                alertMessage(response);
                if (response.status == 'success'){
                    if (that.hasClass('btn-default')){
                        that.removeClass('btn-default').addClass('btn-info');
                    }else{
                        that.removeClass('btn-info').addClass('btn-default');
                    }    
                }
                
            });
            return false;
    })

    // update status approve or disable
    $('.update-disable').click(function() {
        if ($(this).hasClass('btn-default')) {
            $(this).removeClass('btn-default').addClass('btn-danger').html("<i class='fa fa-times'></i>");
        } else {
            $(this).removeClass('btn-danger').addClass('btn-default').html("<i class='fa fa-check'></i>");
        }
        $.get($(this).attr('href'), function(response) {
            alertMessage($.parseJSON(response));
        });
        return false;
    });

    // apply sortable for table
     $(".sortable").sortable({
        helper: function(e, ui) {
            ui.children().each(function() {
                $(this).width($(this).width());
            });
            return ui;
        },
        stop:  function (event, ui) {
            var sortedIds = [];
            $(this).children().each(function(){
                sortedIds.push($(this).attr('id'));
            })
            
            var url = $(this).attr('data-url');
            var that = $(this);
            $.ajax({
              type: "POST",
              url: url,
              data: {listIds: sortedIds}
            })
            .done(function( response ) {
                response = JSON.parse(response);
                alertMessage(response);
                
                if (response.status == 'success'){
                    that.find('.position').each(function(index, element){
                        $(this).html(index + 1);
                    })
                }
            });
        }
    }).disableSelection();
});

var loadingButton = {
    show: function(element) {
        var className = element.attr('class').replace('loading-btn', ''),
            loadingIcon = '<buton type="button" class="' + className + ' loading-btn" disabled="disabled"><i class="fa fa-circle-o-notch fa-spin"></i></button>';

        element.after(loadingIcon);
        element.hide();
    },
    remove: function(element) {
        element.show();
        element.next('.loading-btn').remove();
    }
}

jQuery.validator.addMethod("FullName", function(value, element, param) {
    if (validateEstimatedDeliveryTimeMessage == 'TooLate') {
        return false;
    } else {
        return true;
    }
});

function alertMessage(props) {
    var defaults = {
        message: '',
        status: 'info',
        autoDismiss: true,
        class: ''
    }
    if (typeof props === 'string') defaults.message = props;
    var opts = jQuery.extend({}, defaults, props);
    if (opts.status == 'error') opts.status = 'danger';
    var dismissBtn = '<button type="button" class="close" data-dismiss="alert">';
    dismissBtn += '<span aria-hidden="true">&times;</span></button>';

    if (opts.message == "") opts.message = opts.status;
    var alertPopup = $('<div />', {
        'role': 'alert',
        'class': 'alert alert-dismissible alert-popup',
        'style': 'position: fixed; top: 55px; right: 15px; padding: 10px; z-index:100'
    }).html(opts.message + "&nbsp; &nbsp;" + dismissBtn);

    var height = $('.alert-popup').outerHeight();
    var top = 55 + (height + 5) * $('.alert-popup').length;
    alertPopup.css('top', top);
    alertPopup.addClass('alert-' + opts.status + " " + opts.class);

    $('body').append(alertPopup);

    if (opts.autoDismiss) {
        setTimeout(function() {
            alertPopup.remove()
        }, 2000);
    }
}

function markupPlayer(url, id) {
    var markup  = '<span class="btn btn-default btn-xs player-audio" onclick="playSound(this);">';
        markup +=  '<i class="fa fa-play-circle" style="font-size: 30px;"></i>';
        markup +=  '<audio src="' + url + '" preload="auto" controls="" style="margin-top:5px; display:none" id="' + id + '"></audio>';
        markup += '</span>';    
    return markup;    
}

function playSound(obj){
    playerId = $(obj).find('audio').attr('id');
    var soundPlayer = document.getElementById(playerId);
    soundPlayer.pause();
    soundPlayer.play();
    $(obj).removeClass('btn-default').addClass('btn-info');
    
    $(soundPlayer).on("ended", function(e){
       $(obj).removeClass('btn-info').addClass('btn-default');
    });       

}

function autocomplete(obj, languageId){    
    var words = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            limit: 10,
            remote: {
                url: '/flashcard/ajaxSearch/' + languageId + '/%QUERY',
                filter: function(list) {
                    return $.map(list, function(word) {
                        return {id: word.id, word: word.term};
                    });
                }
            }
        });

        words.initialize();

        obj.typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'words',
            displayKey: 'word',
            source: words.ttAdapter(),
            templates: {                
                suggestion: Handlebars.compile('<p class="tt-word">{{word}}</p>')
            }
        })
        // .on('typeahead:selected', function(object, datum) {
        //     $("#Meaning").val(datum.meaning);
        // });

}