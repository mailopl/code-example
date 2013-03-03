<div class="protocols view">
<h2><?php  echo __('Protocol'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($protocol['Protocol']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($protocol['Protocol']['name']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Protocol'), array('action' => 'edit', $protocol['Protocol']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Protocol'), array('action' => 'delete', $protocol['Protocol']['id']), null, __('Are you sure you want to delete # %s?', $protocol['Protocol']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Protocols'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Protocol'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users Utility Functions'), array('controller' => 'users_utility_functions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Users Utility Function'), array('controller' => 'users_utility_functions', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Users Utility Functions'); ?></h3>
	<?php if (!empty($protocol['UsersUtilityFunction'])): ?>
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
		foreach ($protocol['UsersUtilityFunction'] as $usersUtilityFunction): ?>
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
