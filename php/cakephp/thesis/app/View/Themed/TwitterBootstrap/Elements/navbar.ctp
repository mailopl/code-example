<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <a class="brand" href="<?php echo $this->webroot ?>">apigeum</a>
      <div class="nav-collapse">
        <ul class="nav">
            <li class="active"><a href="<?php echo $this->webroot ?>"><?php echo __('Home'); ?></a></li>
            <li><?php echo $this->Html->link( __('Repositories'), array('controller'=>'feeds','action'=>'index')) ?></li>

            <?php if ($currentUser): ?>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <?php echo __('My options'); ?>
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                    <li><?php echo $this->Html->link(__('Add Repository'), array('controller'=>'feeds','action'=>'add')) ?></li>


                    <li><?php echo $this->Html->link(__('My Repositories'), array('controller'=>'feeds','action'=>'my')) ?></li>
                    <li><?php echo $this->Html->link(__('My API keys'), array('controller'=>'keys','action'=>'my')) ?></li>
                    <li><?php echo $this->Html->link(__('Customers'), array('controller'=>'keys','action'=>'sold')) ?></li>
                    <li><?php echo $this->Html->link(__('Favourites'), array('controller'=>'feeds','action'=>'favs')) ?></li>

                </ul>
            </li>
            <?php endif ?>

            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <?php echo __('Help'); ?>
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                    <li><?php echo $this->Html->link(__('How to use the API'), array('controller'=>'pages','action'=>'display','api-usage')) ?></li>
                    <li><?php echo $this->Html->link(__('Premium'), array('controller'=>'pages','action'=>'display','premium')) ?></li>

                </ul>
            </li>
            <?php if (!$currentUser): ?>
                <li><?php echo $this->Html->link(__('Register'), array('controller'=>'users','action'=>'register')) ?></li>
                <li><?php echo $this->Html->link(__('Log in'), array('controller'=>'users','action'=>'login')) ?></li>
            <?php else: ?>
                 <li><?php echo $this->Html->link( __('Log out'), array('controller'=>'users','action'=>'logout')) ?></li>
            <?php endif ?>


        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>
