<div class="users form">
    <p>
        Balance: <strong><?php echo $this->Number->currency($authUser['balance'], 'PLN') ?></strong>
    </p>
    <h2>Billing</h2>

    <table>
        <tr>
            <th>Date</th>
            <th>Amount</th>
            <th>SID</th>
            <th>Client</th>
            <th>Submitter</th>
        </tr>
        <?php foreach($payments as $pay): ?>
            <tr>
                <td><?php echo $pay['Payment']['created'] ?></td>
                <td><?php echo $pay['Payment']['amount'] ?></td>
                <td><?php echo $pay['Payment']['session_id'] ?></td>
                <td><?php echo $pay['Client']['name'] ?> <?php echo $pay['Client']['surname'] ?></td>
                <td><?php echo $pay['Submitter']['name'] ?> <?php echo $pay['Submitter']['surname'] ?></td>
            </tr>
        <?php endforeach ?>
    </table>
</div>