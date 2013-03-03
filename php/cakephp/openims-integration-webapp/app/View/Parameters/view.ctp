<div class="parameters view">
<h2><?php  echo __('Parameter'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($parameter['Parameter']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Utility Function'); ?></dt>
		<dd>
			<?php echo $this->Html->link($parameter['UtilityFunction']['name'], array('controller' => 'utility_functions', 'action' => 'view', $parameter['UtilityFunction']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($parameter['Parameter']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Default'); ?></dt>
		<dd>
			<?php echo h($parameter['Parameter']['default']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Parameter'), array('action' => 'edit', $parameter['Parameter']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Parameter'), array('action' => 'delete', $parameter['Parameter']['id']), null, __('Are you sure you want to delete # %s?', $parameter['Parameter']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Parameters'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Parameter'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Utility Functions'), array('controller' => 'utility_functions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Utility Function'), array('controller' => 'utility_functions', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users Utility Functions'), array('controller' => 'users_utility_functions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Users Utility Function'), array('controller' => 'users_utility_functions', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Users Utility Functions'); ?></h3>
	<?php if (!empty($parameter['UsersUtilityFunction'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Utility Function Id'); ?></th>
		<th><?php echo __('Protocol Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($parameter['UsersUtilityFunction'] as $usersUtilityFunction): ?>
		<tr>
			<td><?php echo $usersUtilityFunction['id']; ?></td>
			<td><?php echo $usersUtilityFunction['user_id']; ?></td>
			<td><?php echo $usersUtilityFunction['utility_function_id']; ?></td>
			<td><?php echo $usersUtilityFunction['protocol_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'users_utility_functions', 'action' => 'view', $usersUtilityFunction['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'users_utility_functions', 'action' => 'edit', $usersUtilityFunction['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'users_utility_functions', 'action' => 'delete', $usersUtilityFunction['id']), null, __('Are you sure you want to delete # %s?', $usersUtilityFunction['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>


</div>
