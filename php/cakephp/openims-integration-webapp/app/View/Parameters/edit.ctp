<div class="parameters form">
<?php echo $this->Form->create('Parameter'); ?>
	<fieldset>
		<legend><?php echo __('Edit Parameter'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('utility_function_id');
		echo $this->Form->input('name');
		echo $this->Form->input('default');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
