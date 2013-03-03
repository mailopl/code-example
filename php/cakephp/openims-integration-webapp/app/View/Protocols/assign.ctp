<div class="parameters form">
    <?php echo $this->Form->create('UsersUtilityFunction'); ?>
    <fieldset>
        <legend><?php echo __('Assign protocol, utility function and parameters'); ?></legend>
        <?php
        echo $this->Form->input('protocol_id',array('multiple'=>false));
        echo $this->Form->input('utility_function_id',array('options'=>$functions,'multiple'=>false));
        foreach($parameter_values as $p):
        ?>

            <p>
            <input type="checkbox" name="Parameter[<?php echo $p['Parameter']['id'] ?>][checked]">
            <?php echo $p['Parameter']['name'] ?>
            <input type="text" name="Parameter[<?php echo $p['Parameter']['id'] ?>][default]" value="<?php echo $p['Parameter']['default'] ?>">
            </p>
            <?php
        endforeach;
        ?>
    </fieldset>
    <?php echo $this->Form->end(__('Submit')); ?>
</div>
