<h2><?php echo __('Log in'); ?></h2>

<div class="well">
    <?php
        echo $this->Form->create('User');

        echo $this->Form->input('email');

        echo $this->Form->input('password', array('type' => 'password', 'label' => __('Password')));
    ?>


    <br /><br />

    <?php echo $this->Form->submit(__('Log in'), array('class'=>'btn btn-primary')); ?>
    <?php echo $this->Form->end(); ?>
</div>