<div class="parameters form">
    <h3>Your assignements of protocols, utility functions and parameters </h3>

    <?php foreach($assignments as $a): ?>
        <p>Protocol: <?php echo $this->Html->link($a['Protocol']['name'], array('controller'=>'protocols','action'=>'edit',$a['Protocol']['id'])) ?></p>
        <p>Utility function: <?php echo $this->Html->link($a['UtilityFunction']['name'], array('controller'=>'utility_functions','action'=>'edit',$a['UtilityFunction']['id'])) ?></p>
        <ul>
            <?php foreach($a['Parameter'] as $p): ?>
            <li><?php echo $this->Html->link($p['name'],array('controller'=>'parameters','action'=>'edit',$p['id'])) ?>
                (<?php echo isset($p['UsersUtilityFunctionsParameter']) ? $p['UsersUtilityFunctionsParameter']['value'] : $p['default'] ?>)</li>
            <?php endforeach ?>
        </ul>
        <Br />
        <hr /><Br />
    <?php endforeach ?>
</div>
