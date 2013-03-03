<div class="utilityFunctions index">
	<h2><?php echo __('Utility Functions'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
            <th>Parameters</th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
	foreach ($utilityFunctions as $utilityFunction): ?>
	<tr>
		<td><?php echo h($utilityFunction['UtilityFunction']['id']); ?>&nbsp;</td>
        <td><?php echo h($utilityFunction['UtilityFunction']['name']); ?>&nbsp;</td>
        <td>
            <?php if(empty($utilityFunction['Parameter'])): ?>
                None.
            <?php else: ?>
                <?php foreach($utilityFunction['Parameter'] as $p): ?>
                    <?php echo $this->Html->link($p['name'] . '('.$p['default'].')',array('action'=>'edit','controller'=>'parameters',$p['id']))  ; ?>
                <?php endforeach ?>
            <?php endif ?>
        </td>
		<td class="actions">
			<?php //echo $this->Html->link(__('View'), array('action' => 'view', $utilityFunction['UtilityFunction']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $utilityFunction['UtilityFunction']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $utilityFunction['UtilityFunction']['id']), null, __('Are you sure you want to delete # %s?', $utilityFunction['UtilityFunction']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Utility Function'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Parameters'), array('controller' => 'parameters', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Parameter'), array('controller' => 'parameters', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
