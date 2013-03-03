<div class="keys index">
	<h2><?php echo __('Payments'); ?></h2>
    <table cellpadding="0" cellspacing="0" class="table table-bordered table-hover table-striped">
        <?php foreach($data as $feedName => $payments): ?>

        <tr>
            <th><?php echo __('Repository'); ?></th>
            <th><?php echo __('Price'); ?></th>
            <th><?php echo __('Date'); ?></th>
            <th><?php echo __('req left'); ?></th>
            <th><?php echo __('Customer'); ?></th>
        </tr>

        <?php foreach($payments as $payment): ?>
            <tr>
                <td><?php echo $this->Html->link($payment['Feed']['name'], array('controller'=>'feeds','action'=>'view' ,$payment['Feed']['id'])) ?></td>
                <td> <?php echo $this->Number->currency($payment['Payment']['price'], 'PLN') ?> </td>
                <td> <?php echo $this->Time->format($payment['Payment']['created']) ?> </td>
                <td> <?php echo $this->Number->format($payment['Key']['requests']) ?> </td>
                <td> <?php echo $payment['User']['email'] ?> </td>
            </tr>
            <?php endforeach ?>
        <?php endforeach ?>
    </table>


</div>
