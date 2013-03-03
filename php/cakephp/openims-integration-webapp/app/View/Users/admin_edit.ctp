<div class="users form">
    <?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php echo __('Edit user #' . $this->data['Impi']['id']); ?></legend>
        <?php
        echo $this->Form->input('id');
        echo $this->Form->input('Impi.identity');
        //echo $this->Form->input('password');
        echo $this->Form->input('name');
        echo $this->Form->input('surname');
        echo $this->Form->input('email');
        echo $this->Form->input('role', array('options'=>array('admin'=>'Administrator','user'=>'User')));
        ///echo $this->Form->input('UtilityFunction');
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Save')); ?>
</div>
<div class="actions">
    <h3><?php echo __('Actions'); ?></h3>
    <ul>

        <li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('User.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('User.id'))); ?></li>
        <li><?php echo $this->Html->link(__('List Users'), array('action' => 'index')); ?></li>
        <li><?php echo $this->Html->link(__('List Utility Functions'), array('controller' => 'utility_functions', 'action' => 'index')); ?> </li>
        <li><?php echo $this->Html->link(__('New Utility Function'), array('controller' => 'utility_functions', 'action' => 'add')); ?> </li>
    </ul>
</div>
