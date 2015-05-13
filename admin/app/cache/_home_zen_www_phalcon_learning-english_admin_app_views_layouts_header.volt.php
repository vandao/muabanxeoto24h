<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="/" class="navbar-brand">Admin</a>
        </div>
        <div class="navbar-collapse collapse">
            <?php echo $this->layout->getMenu(); ?>
            
            <!-- <form class="navbar-form navbar-right">
                <input type="text" class="form-control" placeholder="Search...">
            </form> -->
        </div>
    </div>
</div>

    
<div class="container-fluid">
    <?php echo $this->getContent(); ?>
</div>