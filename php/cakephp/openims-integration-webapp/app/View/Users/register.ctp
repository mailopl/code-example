<div class="users form">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Registration'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('Impi.identity',array('label'=>'OpenIMS username'));
		echo $this->Form->input('Impi.k',array('label'=>'OpenIMS password'));
		echo $this->Form->input('name');
		echo $this->Form->input('surname');
		echo $this->Form->input('email');
        //echo $this->Form->input('role', array('options'=>array('admin'=>'Administrator','user'=>'User')));
		///echo $this->Form->input('UtilityFunction');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Register')); ?>
</div>

