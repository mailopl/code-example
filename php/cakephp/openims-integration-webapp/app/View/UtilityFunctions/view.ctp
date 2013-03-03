<div class="utilityFunctions view">
<h2><?php  echo __('Utility Function'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($utilityFunction['UtilityFunction']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($utilityFunction['UtilityFunction']['name']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Utility Function'), array('action' => 'edit', $utilityFunction['UtilityFunction']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Utility Function'), array('action' => 'delete', $utilityFunction['UtilityFunction']['id']), null, __('Are you sure you want to delete # %s?', $utilityFunction['UtilityFunction']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Utility Functions'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Utility Function'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Parameters'), array('controller' => 'parameters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Parameter'), array('controller' => 'parameters', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Parameters'); ?></h3>
	<?php if (!empty($utilityFunction['Parameter'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Utility Function Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Default'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($utilityFunction['Parameter'] as $parameter): ?>
		<tr>
			<td><?php echo $parameter['id']; ?></td>
			<td><?php echo $parameter['utility_function_id']; ?></td>
			<td><?php echo $parameter['name']; ?></td>
			<td><?php echo $parameter['default']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'parameters', 'action' => 'view', $parameter['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'parameters', 'action' => 'edit', $parameter['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'parameters', 'action' => 'delete', $parameter['id']), null, __('Are you sure you want to delete # %s?', $parameter['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Parameter'), array('controller' => 'parameters', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Users'); ?></h3>
	<?php if (!empty($utilityFunction['User'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Login'); ?></th>
		<th><?php echo __('Password'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Surname'); ?></th>
		<th><?php echo __('Email'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($utilityFunction['User'] as $user): ?>
		<tr>
			<td><?php echo $user['id']; ?></td>
			<td><?php echo $user['login']; ?></td>
			<td><?php echo $user['password']; ?></td>
			<td><?php echo $user['name']; ?></td>
			<td><?php echo $user['surname']; ?></td>
			<td><?php echo $user['email']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'users', 'action' => 'view', $user['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'users', 'action' => 'edit', $user['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'users', 'action' => 'delete', $user['id']), null, __('Are you sure you want to delete # %s?', $user['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
