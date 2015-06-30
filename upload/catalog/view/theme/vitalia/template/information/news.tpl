<?php echo $header; ?>
<?php include('catalog/view/theme/'.$this->config->get('config_template').'/template/new_elements/wrapper_top.tpl'); ?>
 
 
  <?php if (isset($news_info)) { ?>
    <div class="news" <?php if ($image) { echo 'style="min-height:' . $min_height . 'px;"'; } ?>>
      <?php if ($image && false) { ?>
        <div class="image">
          <a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="colorbox"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" id="image" /></a>
        </div>
      <?php } ?>
      <h1><?php echo $heading_title; ?></h1>
      <?php echo $description; ?>
    </div>
  <?php } elseif (isset($news_data)) { ?>
    <h1><?php echo $heading_title; ?></h1>
    <?php foreach ($news_data as $news) { ?>
     <div class="col-sm-3">
        <a href="<?php echo $news['href']; ?>" title="<?php echo $heading_title; ?>" class="colorbox"><img src="<?php echo $news['image']; ?>" alt="<?php echo $heading_title; ?>" id="image" /></a>
      </div>
      <div class="col-sm-9">
        <h2 style="font-weight: bold"> <a href="<?php echo $news['href']; ?>"> <?php echo $news['title']; ?></a></h2>
        <?php echo $news['description']; ?><br />
        <a href="<?php echo $news['href']; ?>"> <?php echo "Chi tiết"; ?></a><br />
        <a href="<?php echo $news['href']; ?>"><img src="catalog/view/theme/default/image/message-news.png" alt="" /></a> <b><?php echo $text_posted; ?></b><?php echo $news['posted']; ?>
      </div>
    <?php } ?>
  <?php } ?>

  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
<?php include('catalog/view/theme/'.$this->config->get('config_template').'/template/new_elements/wrapper_bottom.tpl'); ?>

<script type="text/javascript"><!--
$(document).ready(function() {
  $('.colorbox').colorbox({
    overlayClose: true,
    opacity: 0.5,
    rel: "colorbox"
  });
});
//--></script>

<?php echo $footer; ?>