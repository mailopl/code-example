<div class="utilityFunctions form">
<?php echo $this->Form->create('UtilityFunction'); ?>
	<fieldset>
		<legend><?php echo __('Edit Utility Function'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		//echo $this->Form->input('User');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
