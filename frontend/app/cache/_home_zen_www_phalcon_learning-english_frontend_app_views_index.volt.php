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
    <?php echo $this->tag->stylesheetLink('css/styles.css'); ?> 
     

    </head>
    <body>
        <?php echo $this->tag->javascriptInclude('js/jquery/jquery.min.js'); ?> 
        <?php echo $this->tag->javascriptInclude('js/jquery/jquery-ui.js'); ?> 

        <?php echo $this->getContent(); ?>
        
        <?php echo $this->tag->javascriptInclude('js/jquery/jquery.validate.js'); ?> 
        <?php echo $this->tag->javascriptInclude('js/bootstrap/js/bootstrap.min.js'); ?>
        
        <?php echo $this->tag->javascriptInclude('js/library.js'); ?> 
        <?php echo $this->tag->javascriptInclude('js/scripts.js'); ?>
	</body>
</html>