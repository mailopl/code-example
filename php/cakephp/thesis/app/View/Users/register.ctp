<h2><?php echo __('Register'); ?></h2>

<div class="well">
    <?php
        echo $this->Form->create('User');


        echo $this->Form->input('email');
    ?>
    <?php
        echo $this->Form->input('password', array('type' => 'password'));
    ?>

    <br /><br />

    <?php echo $this->Form->submit(__('Register'), array('class'=>'btn btn-primary')); ?>
    <?php echo $this->Form->end(); ?>
</div>