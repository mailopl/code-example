<div class="feeds index">
    <h2><?php echo __('Available repositories'); ?></h2>

	<table cellpadding="0" cellspacing="0" class="table table-bordered table-hover table-striped">
        <tr>
            <th><?php echo $this->Paginator->sort('name'); ?></th>

            <th><?php echo $this->Paginator->sort('views_count', __('Views')); ?></th>
            <th><?php echo $this->Paginator->sort('created', __('Created')); ?></th>
            <th><?php echo $this->Paginator->sort('modified', __('Modified')); ?></th>
            <th><?php echo $this->Paginator->sort('rows_count', __('Entries')); ?></th>
            <th><?php echo $this->Paginator->sort('type', __('Type')); ?></th>
        </tr>

        <?php foreach ($feeds as $feed): ?>
        <tr>
            <td>
                <?php
                echo $this->Html->link(
                    $feed['Feed']['name'],
                    array(
                        'action'=>'view',
                        $feed['Feed']['id']
                    ),
                    array(
                        'title' => $this->Html->url('/', true) . 'api/'. h($feed['Feed']['slug'])
                    )
                )
                ?>
            </td>
            <td><?php echo h($feed['Feed']['views_count']); ?></td>
            <td><?php echo h($this->Time->format($feed['Feed']['created'])); ?></td>
            <td><?php echo h($this->Time->format($feed['Feed']['modified'])); ?></td>


            <td><?php echo h($feed['Feed']['rows_count']); ?></td>
            <td><?php echo $feed['Feed']['type'] == 'free' ? __('Free') : __('Premium'); ?></td>
        </tr>
        <?php endforeach; ?>
	</table>

    <?php echo $this->element('paginator'); ?>
</div>
