<?php $action = $this->view->getControllerName() . '/' . $this->view->getActionName(); ?>

<?php echo $this->getContent(); ?>


<div class="row search-block">
    <div class="col-md-12">
        <?php echo $this->tag->form(array('class' => 'form-inline well well-sm', 'action' => $action)); ?>
            <?php echo $this->searchAndFilter->searchForm(array('template_subject' => $this->label->label('Subject', false), 'template_body' => $this->label->label('Body', false), 'template_key' => $this->label->label('Key', false))); ?>

            <?php echo $this->button->newRow(); ?>
        </form>
    </div>
</div>


<div class="row">
    <div class="col-md-6">
        <h2><?php echo $pageHeader; ?></h2>
    </div>
    <div class="col-md-6">
        <?php echo $this->pagination->direct($page); ?>
        <?php echo $this->pagination->itemPerPage($page); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?php echo $this->label->label('Group'); ?></th>
                    <th><?php echo $this->label->label('Category'); ?></th>
                    <th><?php echo $this->label->label('Key'); ?></th>
                    <th><?php echo $this->label->label('Subject'); ?></th>
                    <th><?php echo $this->label->label('Status'); ?></th>
                    <th width="65px"><?php echo $this->label->label('Action'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($page->items) && $page->total_items > 0) { ?>
                    <?php foreach ($page->items as $item) { ?>
                        
<tr>
    <td><?php echo $item->id; ?></td>
    <td><?php echo $item->template_group; ?></td>
    <td><?php echo $item->template_category; ?></td>
    <td><?php echo $item->template_key; ?></td>
    <td><?php echo $item->template_subject; ?></td>
    <td><?php echo $this->button->disabled($item->id, $item->is_disabled); ?></td>
    <td>
        <div class="btn-group">
            <?php echo $this->button->editRow($item->id); ?>
            <?php echo $this->tag->linkTo(array($this->view->getControllerName() . '/ajaxReview/' . $item->id, '<i class="fa fa-envelope"></i>', 'class' => 'btn btn-primary btn-xs test-template')); ?>
        </div>
    </td>
</tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="7"><?php echo $this->label->label('NoResult'); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="pagination-footer">
            <?php echo $this->pagination->direct($page); ?>
            <?php echo $this->pagination->itemPerPage($page); ?>
        </div>
    </div>
</div>


<script type="text/javascript">
    var templateSubject = '',
        templateBody    = '',
        previewSubject  = '',
        previewBody     = '',
        variables       = {};

    $(document).ready(function() {
        $('.test-template').click(function() {
            $.get($(this).attr('href'), function(json) {
                var data  = $.parseJSON(json),
                    modal = $('#GeneralModal');

                if (data.status == 'success') {
                    variableHtml     = '<strong><?php echo $this->label->label('Variables', false); ?></strong>';
                    variableTemplate = '<div class="form-group">' + 
                                '<input type="text" id="%key%" name="%key%" value="" class="form-control" placeholder="%key%">' + 
                            '</div>';

                    $.each(data.data.template_variable.split(","), function(i, v) {
                        key           = v.replace(RegExp("{", 'g'), '').replace(RegExp("}", 'g'), '');
                        html          = variableTemplate.replace(/%key%/g, key);
                        variableHtml += html;
                    });

                    email = '<div class="form-group">' + 
                                '<label for="key" class="control-label"><?php echo $this->label->label('Email', false); ?></label>' + 
                                '<input type="text" id="preview-send-to" name="preview-send-to" value="" class="form-control" placeholder="<?php echo $this->label->label('Email', false); ?>">' + 
                            '</div>';

                    subject = '<div class="form-group">' + 
                                '<label for="key" class="control-label"><?php echo $this->label->label('Subject', false); ?></label>' + 
                                '<div class="preview-subject">' + data.data.template_subject + '</div>' + 
                            '</div>';

                    body = '<div class="form-group">' + 
                                '<label for="key" class="control-label"><?php echo $this->label->label('Body', false); ?></label>' + 
                                '<div class="preview-body">' + data.data.template_body + '</div>' + 
                            '</div>';
                    
                    moreActions = '<button type="button" class="btn btn-primary" onclick="sendTest()">' +
                                    '<i class="fa fa-check"></i>' +
                                    ' <?php echo $this->label->button('Send-Name', false); ?>' +
                                '</button>';

                    modal.find('.modal-title').html(data.data.subject);
                    modal.find('.modal-body').html(email + variableHtml + subject + body);
                    modal.find('.more-actions').html(moreActions);

                    templateSubject = previewSubject = data.data.subject;
                    templateBody    = previewBody    = data.data.body;

                    $.each(data.data.template_variable.split(","), function(i, v) {
                        var key = v.replace(RegExp("{", 'g'), '').replace(RegExp("}", 'g'), '');

                        $('#' + key).keyup(function() {
                            var value = $(this).val(),
                                name  = $(this).attr('name');

                            variables[name] = value;
                            updatePreviewTemplate();
                        });
                    });

                    $('#preview-send-to').keyup(function() {
                        if ($(this).val() != '') {
                            $(this).parent().removeClass('has-error').removeClass('has-feedback');
                        } else {
                            $(this).parent().addClass('has-error').addClass('has-feedback');
                        }
                    });
                } else {
                    modal.find('.modal-title').html();
                    modal.find('.modal-body').html(data.message);
                }

                modal.find('.modal-dialog').addClass('modal-lg');
                modal.modal('show');
            });

            return false;
        });
    });

    function updatePreviewTemplate() {
        previewSubject = templateSubject;
        previewBody    = templateBody;

        $.each(variables, function(name, value) {
            name = '\{\{' + name + '\}\}';

            previewSubject = previewSubject.replace(new RegExp(name,"g"), value);
            previewBody    = previewBody.replace(new RegExp(name,"g"), value);
        });

        modal = $('#GeneralModal');
        modal.find('.modal-title').html(previewSubject);
        modal.find('.preview-subject').html(previewSubject);
        modal.find('.preview-body').html(previewBody);
    }

    function sendTest() {
        var email = $('#preview-send-to');

        if (email.val() != '') {
            $.post('/template/ajaxSendTestEmail', {subject: previewSubject, body: previewBody, to: email.val()}, function(json) {
                var data  = $.parseJSON(json),
                    modal = $('#GeneralModal');

                if (data.status == 'success') {
                    alert(data.status);
                } else {
                    alert(data.message);
                }
            });
        } else {
            email.parent().addClass('has-error').addClass('has-feedback');
        }
    }
</script>