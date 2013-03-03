<div class="users form">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Add User'); ?></legend>
	<?php
		echo $this->Form->input('email');
		echo $this->Form->input('password');
		echo $this->Form->input('last_seen');
		echo $this->Form->input('feeds_count');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
