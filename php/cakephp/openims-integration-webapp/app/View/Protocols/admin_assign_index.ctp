<div class="parameters form">
    <h3>List of assignements</h3>
    <Table>
        <tr>
            <th>Protocol</th>
            <th>Function</th>
            <th>User</th>
            <th>Parameters</th>
        </tr>

    <?php foreach($assignments as $a): ?>
        <tr>
            <td><?php echo $this->Html->link($a['Protocol']['name'], array('controller'=>'protocols','action'=>'edit',$a['Protocol']['id'])) ?></td>
            <td><?php echo $this->Html->link($a['UtilityFunction']['name'], array('controller'=>'utility_functions','action'=>'edit',$a['UtilityFunction']['id'])) ?></td>
            <td><?php echo $this->Html->link($a['User']['Impi']['identity'], array('controller'=>'users','action'=>'edit',$a['User']['Impi']['id'])) ?></td>
            <td>
                <?php foreach($a['Parameter'] as $p): ?>
                    <?php echo $this->Html->link($p['name'],array('controller'=>'parameters','action'=>'edit',$p['id'])) ?> (<?php echo $p['default'] ?>) |
                <?php endforeach ?>
            </td>
        </tr>
    <?php endforeach ?>
    </Table>
</div>
