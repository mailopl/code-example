<h1><?php echo __('Congratulations!'); ?></h1>

<p><?php echo __('You\'ve succesfully bought an API key for'); ?> <strong><?php echo h($feed['Feed']['name']) ?></strong> API</p>

<p><?php echo __('API key'); ?>: <?php echo $key ?></p>
<p><?php echo __('Requests'); ?>: <?php echo $this->Number->format($amount) ?></p>

<?php echo __('Regards!'); ?>