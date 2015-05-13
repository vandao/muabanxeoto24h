


<div class="container">
    <?php if (isset($page->items) && $page->total_items > 0) { ?>
        <?php $v8305800311iterator = $page->items; $v8305800311incr = 0; $v8305800311loop = new stdClass(); $v8305800311loop->length = count($v8305800311iterator); $v8305800311loop->index = 1; $v8305800311loop->index0 = 1; $v8305800311loop->revindex = $v8305800311loop->length; $v8305800311loop->revindex0 = $v8305800311loop->length - 1; ?><?php foreach ($v8305800311iterator as $item) { ?><?php $v8305800311loop->first = ($v8305800311incr == 0); $v8305800311loop->index = $v8305800311incr + 1; $v8305800311loop->index0 = $v8305800311incr; $v8305800311loop->revindex = $v8305800311loop->length - $v8305800311incr; $v8305800311loop->revindex0 = $v8305800311loop->length - ($v8305800311incr + 1); $v8305800311loop->last = ($v8305800311incr == ($v8305800311loop->length - 1)); ?>
            <?php if ($v8305800311loop->index % 3) { ?><div class="row"><?php } ?>

                <div class="col-md-4 col-sm-4">
                    <h2><?php echo $item->application_name; ?></h2>
                    
                    <p class="text-center">
                        <?php if (isset($item->publisher_code)) { ?>
                            <a class="btn btn-success" href="/user/application/<?php echo $item->application_code; ?>" role="button"><?php echo $this->label->button('Statistics'); ?></a>
                        <?php } else { ?>
                            <a class="btn btn-primary" href="/user/application/<?php echo $item->application_code; ?>" role="button"><?php echo $this->label->button('Join-Us'); ?></a>
                        <?php } ?>
                    </p>
                </div>

            <?php if ($v8305800311loop->index % 3 == 2 || $v8305800311loop->last) { ?></div><?php } ?>
        <?php $v8305800311incr++; } ?>
    <?php } else { ?>
        <tr>
            <td colspan="7"><?php echo $this->label->label('NoResult'); ?></td>
        </tr>
    <?php } ?>
</div>