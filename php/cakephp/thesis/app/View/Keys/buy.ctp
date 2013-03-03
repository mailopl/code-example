<div class="keys view" xmlns="http://www.w3.org/1999/html">
    <h2><?php echo __('Congratulations'); ?></h2>

    <p><?php echo __('You\'ve succesfully bought an API key for'); ?> <strong><?php echo h($feed['Feed']['name']) ?></strong> API</p>

    <p><?php echo __('API key'); ?>: <input value="<?php echo $key ?>" /></p>
    <p><?php echo __('Requests'); ?>: <?php echo $amount ?></p>

    <p><?php echo __('You may want now to read the'); ?> <?php echo $this->Html->link( __('usage section'), 'pages/display/api-usage') ?>.</p>

</div>