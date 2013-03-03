<div class="protocols form">
<?php echo $this->Form->create('Protocol'); ?>
	<fieldset>
		<legend><?php echo __('Edit Protocol'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
