<div class="keys index">
	<h2><?php echo __('Keys'); ?></h2>
	    <table cellpadding="0" cellspacing="0" class="table table-bordered table-hover table-striped">
        <tr>
            <th><?php echo $this->Paginator->sort('feed_id', __('Repository')); ?></th>
            <th><?php echo $this->Paginator->sort('transaction_status', __('Transaction status')); ?></th>
            <th><?php echo $this->Paginator->sort('key', __('Key')); ?></th>
            <th><?php echo $this->Paginator->sort('requests', __('Requests')); ?></th>
        </tr>
	    <?php foreach ($keys as $key): ?>
        <tr>
            <td><?php echo $this->Html->link($key['Feed']['name'], array('action'=>'view' ,$key['Feed']['id'])) ?></td>
            <td><?php echo $key['Key']['transaction_status'] == 0 ? __('Waiting') : __('Confirmed') ?></td>
            <td><?php echo h($key['Key']['key']); ?></td>
            <td><?php echo $this->Number->format($key['Key']['requests']); ?></td>
        </tr>
        <?php endforeach; ?>
	</table>

    <?php echo $this->element('paginator'); ?>
</div>
