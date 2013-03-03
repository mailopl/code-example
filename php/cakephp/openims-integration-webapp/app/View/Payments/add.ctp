<div class="payments form">
<?php echo $this->Form->create('Payment'); ?>
	<fieldset>
		<legend><?php echo __('Add Payment'); ?></legend>
	<?php
		echo $this->Form->input('submitter_id');
		echo $this->Form->input('user_id');
		echo $this->Form->input('session_id');
		echo $this->Form->input('amount');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
