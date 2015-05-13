
<?php $auth = $this->session->get(SESSION_LOGIN); ?>


<nav class="navbar navbar-default navbar-static-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">English Crush</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">
        <?php foreach (Menu::getAllByLanguage() as $menu) : ?>
          <?php $menuActiveClass = ''; ?>
          <?php if (isset($menuActive)) { ?>
            <?php if ($menu->menu_key == $menuActive) { ?>
              <?php $menuActiveClass = 'active'; ?>
            <?php } ?>
          <?php } ?>

          <li class="<?php echo $menuActiveClass; ?>">
            <a href="<?php echo $menu->menu_url; ?>"><?php echo $menu->menu_name; ?></a>
          </li>
        <?php endforeach ; ?>

        <?php if ($auth) : ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                <?php echo $this->label->label('Hi'); ?> <?php echo $auth['full_name']; ?> <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="/user/profile"><?php echo $this->label->button('My-Profile'); ?></a></li>
                <li><a href="/session/logout"><?php echo $this->label->button('Logout'); ?></a></li>
              </ul>
            </li>
        <?php else : ?>
            <li><a href="/session/login/Facebook"><i class="fa fa-sign-in"></i> <?php echo $this->label->menu('Login'); ?></a></li>
            <li><a href="/user/signUp"><i class="fa fa-user"></i> <?php echo $this->label->menu('Sign-Up'); ?></a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>


<?php echo $this->getContent(); ?>