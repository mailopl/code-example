<div class="users form">
    <?php echo $this->Session->flash('auth'); ?>
    <?php echo $this->Form->create('Impi'); ?>
    <fieldset>
        <legend><?php echo __('Please enter your username and password'); ?></legend>
        <?php
        echo $this->Form->input('identity',array('label' => 'OpenIMS login'));
        echo $this->Form->input('k',array('label' => 'OpenIMS password'));
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Login')); ?>

    <?php echo $this->Html->link('or register', array('action'=>'register'),array('style'=>'color:green;')) ?>
</div>