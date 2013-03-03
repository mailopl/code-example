<div class="users form">
    <?php echo $this->Form->create('Payment'); ?>
    <fieldset>
        <legend><?php echo __('Supercharge the user'); ?></legend>
        <?php
            echo $this->Form->input('user_id', array('options'=>$users));
            echo $this->Form->input('amount');
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Supercharge')); ?>
</div>

