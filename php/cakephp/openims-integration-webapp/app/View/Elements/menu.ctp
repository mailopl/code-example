<div class="actions">


    <?php if ($authRole == 'admin'): ?>

    <h4><?php echo $auth['identity'] ?></h4>
    <ul>
        <ul>
            <li><?php echo $this->Html->link(__('Assignements'), array('action' => 'assign_index','controller'=>'protocols','admin'=>true)); ?></li>
        </ul>
        <ul>
            <li><b>1. Protocols</b></li>
            <li><?php echo $this->Html->link(__('Add'), array('action' => 'add','controller'=>'protocols','admin'=>false)); ?></li>
            <li><?php echo $this->Html->link(__('List'), array('controller' => 'protocols', 'action' => 'index','admin'=>false)); ?> </li>
        </ul>
        <ul>
            <li><b>2. Utility functions</b></li>
            <li><?php echo $this->Html->link(__('Add'), array('action' => 'add','controller'=>'utility_functions','admin'=>false)); ?></li>
            <li><?php echo $this->Html->link(__('List'), array('controller' => 'utility_functions', 'action' => 'index','admin'=>false)); ?> </li>
        </ul>

        <ul>
            <li><b>3. Parameters</b></li>
            <li><?php echo $this->Html->link(__('Add'), array('action' => 'add','controller'=>'parameters','admin'=>false)); ?></li>
            <li><?php echo $this->Html->link(__('List'), array('controller' => 'parameters', 'action' => 'index','admin'=>false)); ?> </li>
        </ul>

        <ul>
            <li><b>Users</b></li>
            <li><?php echo $auth ? $this->Html->link(__('Log out the ' . $authRole), array('controller' => 'users', 'action' => 'logout','admin'=>false)) : null?></li>
            <li><?php echo $auth ? $this->Html->link(__('Edit profile'), array('controller' => 'users', 'action' => 'edit','admin'=>false)) : null?></li>
            <li><?php echo $this->Html->link(__('Add'), array('action' => 'add','controller'=>'users','admin'=>true)); ?></li>
            <li><?php echo $this->Html->link(__('List'), array('controller' => 'users', 'action' => 'index','admin'=>true)); ?> </li>
        </ul>

        <ul>
            <li><b>Payments</b></li>
            <li><?php echo $this->Html->link(__('List'), array('controller' => 'payments', 'action' => 'index','admin'=>false)); ?> </li>
        </ul>
    </ul>

    <?php elseif($authRole): ?>
    <h4><?php echo $auth['identity'] ?></h4>
    <ul>
        <li><?php echo $this->Html->link(__('Edit profile'), array('action' => 'edit','controller'=>'users')); ?></li>
        <li><?php echo $this->Html->link(__('Billing'), array('action' => 'billing','controller'=>'payments')); ?></li>
        <li><?php echo $this->Html->link(__('Assign'), array('action' => 'assign','controller'=>'protocols','admin'=>false)); ?></li>
        <li><?php echo $this->Html->link(__('Assignements'), array('action' => 'assign_index','controller'=>'protocols','admin'=>false)); ?></li>
        <li><?php echo $auth ? $this->Html->link(__('Log out the ' . $authRole), array('controller' => 'users', 'action' => 'logout','admin'=>false)) : null?></li>
    </ul>

    <?php endif ?>
</div>
